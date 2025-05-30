<?php
// Include database connection
require_once 'connect_db.php';

// Function to get account by ID
function getAccountById($accountId) {
    global $con;
    
    // Sanitize input
    $accountId = intval($accountId);
    
    $query = "SELECT a.*, m.Firstname, m.Middlename, m.Lastname, t.type_name, t.allows_deposits, 
              t.allows_withdrawals, t.default_interest_rate, t.minimum_opening_balance
              FROM account a
              JOIN member m ON a.MemberID = m.MemberID
              JOIN account_types t ON a.Account_Type = t.type_name
              WHERE a.AccountID = ?";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $accountId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $account = $result->fetch_assoc();
        
        // Check for account restrictions
        $query = "SELECT * FROM account_restrictions WHERE AccountID = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $accountId);
        $stmt->execute();
        $restrictions = $stmt->get_result();
        
        if ($restrictions->num_rows > 0) {
            $account['restrictions'] = $restrictions->fetch_assoc();
        } else {
            $account['restrictions'] = null;
        }
        
        return $account;
    }
    
    return null;
}

// Function to get transaction limits
function getTransactionLimits($accountId, $accountTypeId, $transactionType) {
    global $con;
    
    // First check account-specific limits
    $query = "SELECT * FROM transaction_limits 
              WHERE AccountID = ? AND transaction_type = ?";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param("is", $accountId, $transactionType);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    // Then check account type limits
    $query = "SELECT * FROM transaction_limits 
              WHERE account_type_id = ? AND transaction_type = ? AND AccountID IS NULL";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param("is", $accountTypeId, $transactionType);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    // Default limits if none found
    return [
        'daily_limit' => null,
        'min_transaction_amount' => 1,
        'max_transaction_amount' => null,
        'daily_count_limit' => null
    ];
}

// Function to check daily transaction totals
function getDailyTransactionTotal($accountId, $transactionType) {
    global $con;
    
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    
    $query = "SELECT SUM(amount) as total, COUNT(*) as count 
              FROM transactions 
              WHERE AccountID = ? 
              AND transaction_type = ? 
              AND transaction_date >= ? 
              AND transaction_date < ?
              AND status = 'Completed'";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param("isss", $accountId, $transactionType, $today, $tomorrow);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return ['total' => 0, 'count' => 0];
}

// Function to make a deposit
function makeDeposit($accountId, $amount) {
    global $con;
    
    // Start transaction
    $con->begin_transaction();
    
    try {
        // Get account details
        $account = getAccountById($accountId);
        
        if (!$account) {
            throw new Exception("Account not found");
        }
        
        if ($account['allows_deposits'] != 1) {
            throw new Exception("This account does not allow deposits");
        }
        
        // Get transaction limits
        $limits = getTransactionLimits($accountId, $account['type_id'], 'Deposit');
        $dailyStats = getDailyTransactionTotal($accountId, 'Deposit');
        
        // Check minimum amount
        if ($limits['min_transaction_amount'] !== null && $amount < $limits['min_transaction_amount']) {
            throw new Exception("Minimum deposit amount is $" . $limits['min_transaction_amount']);
        }
        
        // Check maximum amount
        if ($limits['max_transaction_amount'] !== null && $amount > $limits['max_transaction_amount']) {
            throw new Exception("Maximum deposit amount is $" . $limits['max_transaction_amount']);
        }
        
        // Check daily limit
        if ($limits['daily_limit'] !== null && ($dailyStats['total'] + $amount) > $limits['daily_limit']) {
            throw new Exception("Daily deposit limit of $" . $limits['daily_limit'] . " would be exceeded");
        }
        
        // Check transaction count limit
        if ($limits['daily_count_limit'] !== null && $dailyStats['count'] >= $limits['daily_count_limit']) {
            throw new Exception("Daily deposit transaction count limit reached");
        }
        
        // Update account balance
        $newBalance = $account['Balance'] + $amount;
        $updateQuery = "UPDATE account SET Balance = ? WHERE AccountID = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("di", $newBalance, $accountId);
        $stmt->execute();
        
        // Record transaction
        $reference = 'DEP' . time() . rand(100, 999);
        $description = "Deposit to account";
        $memberId = $account['MemberID'];
        
        $transactionQuery = "INSERT INTO transactions 
                            (AccountID, MemberID, transaction_type, amount, balance_after, 
                             description, reference_number, status) 
                            VALUES (?, ?, 'Deposit', ?, ?, ?, ?, 'Completed')";
                            
        $stmt = $con->prepare($transactionQuery);
        $stmt->bind_param("iiddss", $accountId, $memberId, $amount, $newBalance, $description, $reference);
        $stmt->execute();
        
        // Commit transaction
        $con->commit();
        
        return [
            'success' => true,
            'message' => "Successfully deposited $" . number_format($amount, 2) . " to account",
            'new_balance' => $newBalance,
            'reference' => $reference
        ];
        
    } catch (Exception $e) {
        // Rollback on error
        $con->rollback();
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

// Function to make a withdrawal
function makeWithdrawal($accountId, $amount) {
    global $con;
    
    // Start transaction
    $con->begin_transaction();
    
    try {
        // Get account details
        $account = getAccountById($accountId);
        
        if (!$account) {
            throw new Exception("Account not found");
        }
        
        if ($account['allows_withdrawals'] != 1) {
            throw new Exception("This account does not allow withdrawals");
        }
        
        // Get transaction limits
        $limits = getTransactionLimits($accountId, $account['type_id'], 'Withdrawal');
        $dailyStats = getDailyTransactionTotal($accountId, 'Withdrawal');
        
        // Check minimum amount
        if ($limits['min_transaction_amount'] !== null && $amount < $limits['min_transaction_amount']) {
            throw new Exception("Minimum withdrawal amount is $" . $limits['min_transaction_amount']);
        }
        
        // Check maximum amount
        if ($limits['max_transaction_amount'] !== null && $amount > $limits['max_transaction_amount']) {
            throw new Exception("Maximum withdrawal amount is $" . $limits['max_transaction_amount']);
        }
        
        // Check daily limit
        if ($limits['daily_limit'] !== null && ($dailyStats['total'] + $amount) > $limits['daily_limit']) {
            throw new Exception("Daily withdrawal limit of $" . $limits['daily_limit'] . " would be exceeded");
        }
        
        // Check transaction count limit
        if ($limits['daily_count_limit'] !== null && $dailyStats['count'] >= $limits['daily_count_limit']) {
            throw new Exception("Daily withdrawal transaction count limit reached");
        }
        
        // Check if account has sufficient balance
        if ($account['Balance'] < $amount) {
            throw new Exception("Insufficient balance");
        }
        
        // Check minimum balance requirement
        $minimumBalance = 0;
        if ($account['restrictions'] && $account['restrictions']['minimum_balance'] !== null) {
            $minimumBalance = $account['restrictions']['minimum_balance'];
            if (($account['Balance'] - $amount) < $minimumBalance) {
                throw new Exception("Account must maintain a minimum balance of $" . $minimumBalance);
            }
        }
        
        // Calculate penalty if applicable (for fixed accounts)
        $penaltyAmount = 0;
        if ($account['restrictions'] && $account['Account_Type'] == 'Fixed Account') {
            $today = new DateTime();
            $maturityDate = new DateTime($account['restrictions']['maturity_date']);
            
            if ($today < $maturityDate) {
                // Early withdrawal penalty
                $penaltyAmount = $amount * $account['restrictions']['early_withdrawal_penalty'];
            }
        }
        
        // Update account balance
        $newBalance = $account['Balance'] - $amount;
        $updateQuery = "UPDATE account SET Balance = ? WHERE AccountID = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("di", $newBalance, $accountId);
        $stmt->execute();
        
        // Record transaction
        $reference = 'WDR' . time() . rand(100, 999);
        $description = "Withdrawal from account";
        $memberId = $account['MemberID'];
        
        $transactionQuery = "INSERT INTO transactions 
                            (AccountID, MemberID, transaction_type, amount, penalty_amount, balance_after, 
                             description, reference_number, status) 
                            VALUES (?, ?, 'Withdrawal', ?, ?, ?, ?, ?, 'Completed')";
                            
        $stmt = $con->prepare($transactionQuery);
        $stmt->bind_param("iidddss", $accountId, $memberId, $amount, $penaltyAmount, $newBalance, $description, $reference);
        $stmt->execute();
        
        // Commit transaction
        $con->commit();
        
        return [
            'success' => true,
            'message' => "Successfully withdrawn $" . number_format($amount, 2) . " from account",
            'penalty' => $penaltyAmount > 0 ? $penaltyAmount : null,
            'new_balance' => $newBalance,
            'reference' => $reference
        ];
        
    } catch (Exception $e) {
        // Rollback on error
        $con->rollback();
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}

// Function to get account types
function getAccountTypes() {
    global $con;
    
    $query = "SELECT * FROM account_types";
    $result = $con->query($query);
    
    $types = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $types[] = $row;
        }
    }
    
    return $types;
}

// Function to get recent transactions for an account
function getRecentTransactions($accountId, $limit = 10) {
    global $con;
    
    $query = "SELECT * FROM transactions 
              WHERE AccountID = ? 
              ORDER BY transaction_date DESC 
              LIMIT ?";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $accountId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $transactions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
    }
    
    return $transactions;
}
?>