<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['Email'])) {
    header("Location: login.php");
    exit();
}

require_once 'connect_db.php';

// Fetch user data
$user_id = $_SESSION['MemberID'];
$stmt = $con->prepare("SELECT Firstname, email, Lastname FROM member WHERE MemberID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch user's accounts
$account_query = "
    SELECT a.AccountID, a.Account_Type, a.Balance, at.description
    FROM account a
    JOIN account_types at ON a.Account_Type = at.type_name
    WHERE a.MemberID = ?
";
$account_stmt = $con->prepare($account_query);
$account_stmt->bind_param("i", $user_id);
$account_stmt->execute();
$accounts_result = $account_stmt->get_result();
$accounts = $accounts_result->fetch_all(MYSQLI_ASSOC);
$account_stmt->close();

// Fetch active loans with total paid amounts
$loan_query = "
    SELECT l.LoanID, l.Purpose, l.Amount, l.Interest, l.Total_Amount, l.Monthly_Amortization, l.Period, l.Status,
           COALESCE(SUM(lp.Payment_Amount), 0) as total_paid
    FROM loan l
    LEFT JOIN loan_payments lp ON l.LoanID = lp.LoanID AND lp.Status = 'Completed'
    WHERE l.MemberID = ? AND l.Status != 'closed'
    GROUP BY l.LoanID
";
$loan_stmt = $con->prepare($loan_query);
$loan_stmt->bind_param("i", $user_id);
$loan_stmt->execute();
$loans_result = $loan_stmt->get_result();
$loans = $loans_result->fetch_all(MYSQLI_ASSOC);
$loan_stmt->close();

// Fetch recent transactions
$transaction_query = "
    SELECT transaction_id, transaction_type, amount, balance_after, transaction_date, description
    FROM transactions
    WHERE MemberID = ?
    ORDER BY transaction_date DESC
    LIMIT 5
";
$transaction_stmt = $con->prepare($transaction_query);
$transaction_stmt->bind_param("i", $user_id);
$transaction_stmt->execute();
$transactions_result = $transaction_stmt->get_result();
$transactions = $transactions_result->fetch_all(MYSQLI_ASSOC);
$transaction_stmt->close();

// Fetch available account types (restricted to Savings, Time Deposit, Fixed Account)
$account_types_query = "
    SELECT type_name, minimum_opening_balance, description 
    FROM account_types 
    WHERE type_name IN ('Savings', 'Time Deposit', 'Fixed Account') AND allows_deposits = 1
";
$account_types_result = $con->query($account_types_query);
$account_types = $account_types_result->fetch_all(MYSQLI_ASSOC);
$account_types_result->free();

// Handle new account opening with maturity date logic
$account_error = $account_success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['open_account'])) {
    $account_type = $_POST['account_type'];
    $initial_deposit = floatval($_POST['initial_deposit']);
    
    $valid_type = false;
    $min_balance = 0;
    foreach ($account_types as $type) {
        if ($type['type_name'] === $account_type) {
            $valid_type = true;
            $min_balance = $type['minimum_opening_balance'];
            break;
        }
    }
    
    if (!$valid_type) {
        $account_error = "Invalid account type selected. Please choose Savings, Time Deposit, or Fixed Account.";
    } elseif ($initial_deposit < $min_balance) {
        $account_error = "Initial deposit must be at least $" . number_format($min_balance, 2);
    } else {
        // Start a transaction for atomicity
        $con->begin_transaction();
        try {
            // Insert into account table
            $insert_account = $con->prepare("INSERT INTO account (MemberID, Account_Type, Balance) VALUES (?, ?, ?)");
            $insert_account->bind_param("isd", $user_id, $account_type, $initial_deposit);
            
            if (!$insert_account->execute()) {
                throw new Exception("Failed to open account.");
            }
            
            $account_id = $con->insert_id;
            
            // Set maturity date for Time Deposit and Fixed Account
            $lock_in_days = 0;
            if ($account_type === 'Time Deposit') {
                $lock_in_days = 180; // 6 months
            } elseif ($account_type === 'Fixed Account') {
                $lock_in_days = 360; // 12 months
            }
            
            if ($lock_in_days > 0) {
                $maturity_date = date('Y-m-d', strtotime("+$lock_in_days days", strtotime(date('Y-m-d'))));
                $insert_restrictions = $con->prepare("
                    INSERT INTO account_restrictions (AccountID, minimum_balance, early_withdrawal_penalty, maturity_date)
                    VALUES (?, 0.00, 0.05, ?)
                ");
                $insert_restrictions->bind_param("is", $account_id, $maturity_date);
                
                if (!$insert_restrictions->execute()) {
                    throw new Exception("Failed to set account restrictions.");
                }
                $insert_restrictions->close();
            }
            
            // Insert transaction record
            $reference_number = 'DEP-' . date('Ymd') . '-' . sprintf("%03d", $account_id);
            $insert_transaction = $con->prepare("
                INSERT INTO transactions (AccountID, MemberID, transaction_type, amount, balance_after, description, reference_number)
                VALUES (?, ?, 'Deposit', ?, ?, 'Initial deposit', ?)
            ");
            $insert_transaction->bind_param("iidds", $account_id, $user_id, $initial_deposit, $initial_deposit, $reference_number);
            
            if (!$insert_transaction->execute()) {
                throw new Exception("Failed to record initial deposit transaction.");
            }
            
            $insert_transaction->close();
            $insert_account->close();
            
            // Commit the transaction
            $con->commit();
            $account_success = "Account opened successfully!";
            header("Location: MemberDashboard.php?account_success=1");
            exit();
        } catch (Exception $e) {
            // Roll back on error
            $con->rollback();
            $account_error = $e->getMessage() . " Please try again.";
        }
    }
}

// Handle loan application
$loan_error = $loan_success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_loan'])) {
    $purpose = trim($_POST['purpose']);
    $amount = floatval($_POST['amount']);
    $period = intval($_POST['period']);
    
    if (empty($purpose)) {
        $loan_error = "Please specify the loan purpose.";
    } elseif ($amount < 1000) {
        $loan_error = "Loan amount must be at least $1,000.";
    } elseif ($period < 6 || $period > 36) {
        $loan_error = "Loan period must be between 6 and 36 months.";
    } else {
        $loan_check = $con->prepare("SELECT COUNT(*) as active_loans FROM loan WHERE MemberID = ? AND Status != 'closed'");
        $loan_check->bind_param("i", $user_id);
        $loan_check->execute();
        $loan_result = $loan_check->get_result()->fetch_assoc();
        
        if ($loan_result['active_loans'] > 0) {
            $loan_error = "You cannot apply for a new loan until existing loans are closed.";
        } else {
            $interest_rate = 0.05;
            $total_amount = $amount * (1 + $interest_rate);
            $monthly_amortization = $total_amount / $period;
            
            $insert_loan = $con->prepare("
                INSERT INTO loan_application (
                    MemberID, Firstname, Middlename, Lastname, Purpose, Amount, Interest, 
                    Total_Amount, Monthly_Amortization, Period, Status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
            ");
            $interest = $amount * $interest_rate;
            $firstname = $user['Firstname'];
            $middlename = $user['Middlename'] ?? '';
            $lastname = $user['Lastname'] ?? '';

            $insert_loan->bind_param(
                "issssddddi",
                $user_id,
                $firstname,
                $middlename,
                $lastname,
                $purpose,
                $amount,
                $interest,
                $total_amount,
                $monthly_amortization,
                $period
            );
            
            if ($insert_loan->execute()) {
                $insert_loan->close();
                header("Location: MemberDashboard.php?loan_success=1");
                exit();
            } else {
                $loan_error = "Failed to submit loan application. Please try again.";
            }
            $insert_loan->close();
        }
        $loan_check->close();
    }
}

// Handle loan payment
$payment_error = $payment_success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_loan'])) {
    $loan_id = intval($_POST['loan_id']);
    $payment_amount = floatval($_POST['payment_amount']);
    
    if ($payment_amount <= 0) {
        $payment_error = "Payment amount must be greater than $0.";
    } else {
        $loan_check = $con->prepare("SELECT Total_Amount FROM loan WHERE LoanID = ? AND MemberID = ? AND Status != 'closed'");
        $loan_check->bind_param("ii", $loan_id, $user_id);
        $loan_check->execute();
        $loan_result = $loan_check->get_result();
        
        if ($loan_result->num_rows === 0) {
            $payment_error = "Invalid or closed loan selected.";
        } else {
            $loan = $loan_result->fetch_assoc();
            $total_amount = floatval($loan['Total_Amount']);
            
            $payment_sum_query = $con->prepare("SELECT COALESCE(SUM(Payment_Amount), 0) as total_paid FROM loan_payments WHERE LoanID = ? AND Status = 'Completed'");
            $payment_sum_query->bind_param("i", $loan_id);
            $payment_sum_query->execute();
            $total_paid_result = $payment_sum_query->get_result()->fetch_assoc();
            $total_paid = floatval($total_paid_result['total_paid']);
            $remaining_balance = $total_amount - $total_paid - $payment_amount;
            
            if ($remaining_balance < 0) {
                $payment_error = "Payment amount exceeds remaining balance.";
            } else {
                $insert_payment = $con->prepare("
                    INSERT INTO loan_payments (LoanID, MemberID, Payment_Amount, Remaining_Balance, Description)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $description = "Loan payment for Loan ID: $loan_id";
                $insert_payment->bind_param("iidds", $loan_id, $user_id, $payment_amount, $remaining_balance, $description);
                
                if ($remaining_balance <= 0) {
                    $update_loan = $con->prepare("UPDATE loan SET Status = 'closed' WHERE LoanID = ?");
                    $update_loan->bind_param("i", $loan_id);
                    $update_loan->execute();
                    $update_loan->close();
                }
                
                if ($insert_payment->execute()) {
                    $insert_payment->close();
                    header("Location: MemberDashboard.php?payment_success=1");
                    exit();
                } else {
                    $payment_error = "Failed to process payment. Please try again.";
                }
                $insert_payment->close();
            }
            $payment_sum_query->close();
        }
        $loan_check->close();
    }
}

// Handle deposit
$deposit_error = $deposit_success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deposit'])) {
    $account_id = intval($_POST['account_id']);
    $deposit_amount = floatval($_POST['deposit_amount']);
    
    if ($deposit_amount <= 0) {
        $deposit_error = "Deposit amount must be greater than $0.";
    } else {
        $account_check = $con->prepare("
            SELECT a.Balance, at.type_id
            FROM account a
            JOIN account_types at ON a.Account_Type = at.type_name
            WHERE a.AccountID = ? AND a.MemberID = ?
        ");
        $account_check->bind_param("ii", $account_id, $user_id);
        $account_check->execute();
        $account_result = $account_check->get_result();
        
        if ($account_result->num_rows === 0) {
            $deposit_error = "Invalid account selected.";
        } else {
            $account = $account_result->fetch_assoc();
            $type_id = $account['type_id'];
            
            $limit_check = $con->prepare("
                SELECT daily_limit, min_transaction_amount, max_transaction_amount, daily_count_limit
                FROM transaction_limits
                WHERE account_type_id = ? AND transaction_type = 'Deposit'
            ");
            $limit_check->bind_param("i", $type_id);
            $limit_check->execute();
            $limit_result = $limit_check->get_result();
            $limits = $limit_result->num_rows > 0 ? $limit_result->fetch_assoc() : null;
            
            if ($limits && $deposit_amount < $limits['min_transaction_amount']) {
                $deposit_error = "Deposit amount must be at least $" . number_format($limits['min_transaction_amount'], 2) . ".";
            } elseif ($limits && $deposit_amount > $limits['max_transaction_amount']) {
                $deposit_error = "Deposit amount cannot exceed $" . number_format($limits['max_transaction_amount'], 2) . ".";
            } else {
                $new_balance = $account['Balance'] + $deposit_amount;
                $update_account = $con->prepare("UPDATE account SET Balance = ? WHERE AccountID = ?");
                $update_account->bind_param("di", $new_balance, $account_id);
                
                $insert_transaction = $con->prepare("
                    INSERT INTO transactions (AccountID, MemberID, transaction_type, amount, balance_after, description, reference_number)
                    VALUES (?, ?, 'Deposit', ?, ?, 'Member deposit', ?)
                ");
                $reference_number = 'DEP-' . date('Ymd') . '-' . sprintf("%03d", $account_id);
                $insert_transaction->bind_param("iidds", $account_id, $user_id, $deposit_amount, $new_balance, $reference_number);
                
                if ($update_account->execute() && $insert_transaction->execute()) {
                    $update_account->close();
                    $insert_transaction->close();
                    header("Location: MemberDashboard.php?deposit_success=1");
                    exit();
                } else {
                    $deposit_error = "Failed to process deposit. Please try again.";
                }
                $update_account->close();
                $insert_transaction->close();
            }
            $limit_check->close();
        }
        $account_check->close();
    }
}

// Handle withdrawal
$withdrawal_error = $withdrawal_success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw'])) {
    $account_id = intval($_POST['account_id']);
    $withdrawal_amount = floatval($_POST['withdrawal_amount']);
    
    if ($withdrawal_amount <= 0) {
        $withdrawal_error = "Withdrawal amount must be greater than $0.";
    } else {
        $account_check = $con->prepare("
            SELECT a.Balance, a.Account_Type, at.type_id, at.allows_withdrawals, ar.minimum_balance, ar.early_withdrawal_penalty, ar.maturity_date
            FROM account a
            JOIN account_types at ON a.Account_Type = at.type_name
            LEFT JOIN account_restrictions ar ON a.AccountID = ar.AccountID
            WHERE a.AccountID = ? AND a.MemberID = ?
        ");
        $account_check->bind_param("ii", $account_id, $user_id);
        $account_check->execute();
        $account_result = $account_check->get_result();
        
        if ($account_result->num_rows === 0) {
            $withdrawal_error = "Invalid account selected.";
        } else {
            $account = $account_result->fetch_assoc();
            $type_id = $account['type_id'];
            $account_type = $account['Account_Type'];
            
            if (!$account['allows_withdrawals']) {
                $withdrawal_error = "Withdrawals are not allowed for this account type.";
            } elseif ($account['maturity_date'] && strtotime($account['maturity_date']) > time()) {
                $maturity_date = date('F j, Y', strtotime($account['maturity_date']));
                $withdrawal_error = "Cannot withdraw from $account_type before maturity date ($maturity_date). Early withdrawal incurs a penalty.";
            } elseif ($account['minimum_balance'] && ($account['Balance'] - $withdrawal_amount) < $account['minimum_balance']) {
                $withdrawal_error = "Withdrawal would bring balance below minimum of $" . number_format($account['minimum_balance'], 2) . ".";
            } elseif ($account['Balance'] < $withdrawal_amount) {
                $withdrawal_error = "Insufficient funds.";
            } else {
                $limit_check = $con->prepare("
                    SELECT daily_limit, min_transaction_amount, max_transaction_amount, daily_count_limit
                    FROM transaction_limits
                    WHERE account_type_id = ? AND transaction_type = 'Withdrawal'
                ");
                $limit_check->bind_param("i", $type_id);
                $limit_check->execute();
                $limit_result = $limit_check->get_result();
                $limits = $limit_result->num_rows > 0 ? $limit_result->fetch_assoc() : null;
                
                if ($limits && $withdrawal_amount < $limits['min_transaction_amount']) {
                    $withdrawal_error = "Withdrawal amount must be at least $" . number_format($limits['min_transaction_amount'], 2) . ".";
                } elseif ($limits && $withdrawal_amount > $limits['max_transaction_amount']) {
                    $withdrawal_error = "Withdrawal amount cannot exceed $" . number_format($limits['max_transaction_amount'], 2) . ".";
                } else {
                    $penalty_amount = ($account['early_withdrawal_penalty'] && $account['maturity_date'] && strtotime($account['maturity_date']) > time())
                        ? ($withdrawal_amount * $account['early_withdrawal_penalty'])
                        : 0;
                    $total_deduction = $withdrawal_amount + $penalty_amount;
                    
                    if ($account['Balance'] < $total_deduction) {
                        $withdrawal_error = "Insufficient funds including penalty.";
                    } else {
                        $new_balance = $account['Balance'] - $total_deduction;
                        $update_account = $con->prepare("UPDATE account SET Balance = ? WHERE AccountID = ?");
                        $update_account->bind_param("di", $new_balance, $account_id);
                        
                        $insert_transaction = $con->prepare("
                            INSERT INTO transactions (AccountID, MemberID, transaction_type, amount, penalty_amount, balance_after, description, reference_number)
                            VALUES (?, ?, 'Withdrawal', ?, ?, ?, 'Member withdrawal', ?)
                        ");
                        $reference_number = 'WDR-' . date('Ymd') . '-' . sprintf("%03d", $account_id);
                        $insert_transaction->bind_param("iiddds", $account_id, $user_id, $withdrawal_amount, $penalty_amount, $new_balance, $reference_number);
                        
                        if ($update_account->execute() && $insert_transaction->execute()) {
                            $update_account->close();
                            $insert_transaction->close();
                            header("Location: MemberDashboard.php?withdrawal_success=1");
                            exit();
                        } else {
                            $withdrawal_error = "Failed to process withdrawal. Please try again.";
                        }
                        $update_account->close();
                        $insert_transaction->close();
                    }
                }
                $limit_check->close();
            }
        }
        $account_check->close();
    }
}

// Display success messages from redirect
if (isset($_GET['account_success'])) {
    $account_success = "Account opened successfully!";
}
if (isset($_GET['loan_success'])) {
    $loan_success = "Loan application submitted successfully!";
}
if (isset($_GET['payment_success'])) {
    $payment_success = "Loan payment processed successfully!";
}
if (isset($_GET['deposit_success'])) {
    $deposit_success = "Deposit processed successfully!";
}
if (isset($_GET['withdrawal_success'])) {
    $withdrawal_success = "Withdrawal processed successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="member_dashboard.css">
    <script src="member_dashboard.js" defer></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <h2>Pay360</h2>
                </div>
                <button class="sidebar-toggle" aria-label="Toggle Sidebar"><i class="fas fa-bars"></i></button>
            </div>
            <ul class="nav-links">
                <li><a href="#dashboard" class="active"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li><a href="#actions"><i class="fas fa-tasks"></i><span>Actions</span></a></li>
                <li><a href="#accounts"><i class="fas fa-wallet"></i><span>Accounts</span></a></li>
                <li><a href="#loans"><i class="fas fa-hand-holding-usd"></i><span>Loans</span></a></li>
                <li><a href="#transactions"><i class="fas fa-exchange-alt"></i><span>Transactions</span></a></li>
                <li><a href="#profile"><i class="fas fa-user"></i><span>Profile</span></a></li>
                <li><a href="#settings"><i class="fas fa-cog"></i><span>Settings</span></a></li>
                <li><a href="Home.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            </ul>
            <div class="sidebar-footer">
                <p>&copy; 2025 Pay360</p>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1><i class="fas fa-tachometer-alt"></i> Welcome, <?php echo htmlspecialchars($user['Firstname'] ?? 'Member'); ?>!</h1>
            </header>

            <!-- Dashboard Overview -->
            <section class="dashboard-section" id="dashboard">
                <div class="dashboard-card">
                    <h2>Dashboard Overview</h2>
                    <div class="overview-grid">
                        <div class="overview-item">
                            <h3>Total Accounts</h3>
                            <p><?php echo count($accounts); ?></p>
                        </div>
                        <div class="overview-item">
                            <h3>Active Loans</h3>
                            <p><?php echo count($loans); ?></p>
                        </div>
                        <div class="overview-item">
                            <h3>Recent Transactions</h3>
                            <p><?php echo count($transactions); ?></p>
                        </div>
                    </div>
                </div>
                <div class="profile-card">
                    <h3><i class="fas fa-user-circle"></i> Profile Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['Firstname'] . ' ' . $user['Lastname']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </section>

            <!-- Accounts Section -->
            <section class="dashboard-section" id="accounts">
                <div class="dashboard-card">
                    <h2><i class="fas fa-wallet"></i> Your Accounts</h2>
                    <?php if (count($accounts) > 0): ?>
                        <div class="table-wrapper">
                            <table class="accounts-table">
                                <thead>
                                    <tr>
                                        <th>Account Type</th>
                                        <th>Balance</th>
                                        <th>Description</th>
                                        <th>Maturity Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch accounts with maturity dates
                                    $account_maturity_query = "
                                        SELECT a.AccountID, a.Account_Type, a.Balance, at.description, ar.maturity_date
                                        FROM account a
                                        JOIN account_types at ON a.Account_Type = at.type_name
                                        LEFT JOIN account_restrictions ar ON a.AccountID = ar.AccountID
                                        WHERE a.MemberID = ?
                                    ";
                                    $account_maturity_stmt = $con->prepare($account_maturity_query);
                                    $account_maturity_stmt->bind_param("i", $user_id);
                                    $account_maturity_stmt->execute();
                                    $accounts_with_maturity = $account_maturity_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                                    $account_maturity_stmt->close();
                                    ?>
                                    <?php foreach ($accounts_with_maturity as $account): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($account['Account_Type']); ?></td>
                                            <td>$<?php echo number_format($account['Balance'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($account['description'] ?? 'N/A'); ?></td>
                                            <td><?php echo $account['maturity_date'] ? date('F j, Y', strtotime($account['maturity_date'])) : 'N/A'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No accounts found. Open a new account in the Actions section.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Loans Section -->
            <section class="dashboard-section" id="loans">
                <div class="dashboard-card">
                    <h2><i class="fas fa-hand-holding-usd"></i> Your Loans</h2>
                    <?php if (count($loans) > 0): ?>
                        <div class="table-wrapper">
                            <table class="loans-table">
                                <thead>
                                    <tr>
                                        <th>Purpose</th>
                                        <th>Total Amount</th>
                                        <th>Monthly Amortization</th>
                                        <th>Remaining Balance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($loans as $loan): ?>
                                        <?php
                                        $remaining_balance = $loan['Total_Amount'] - $loan['total_paid'];
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($loan['Purpose']); ?></td>
                                            <td>$<?php echo number_format($loan['Total_Amount'], 2); ?></td>
                                            <td>$<?php echo number_format($loan['Monthly_Amortization'], 2); ?></td>
                                            <td>$<?php echo number_format($remaining_balance, 2); ?></td>
                                            <td><?php echo htmlspecialchars($loan['Status']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No active loans found. Apply for a loan in the Actions section.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Transactions Section -->
            <section class="dashboard-section" id="transactions">
                <div class="dashboard-card">
                    <h2><i class="fas fa-exchange-alt"></i> Recent Transactions</h2>
                    <?php if (count($transactions) > 0): ?>
                        <div class="table-wrapper">
                            <table class="transactions-table">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Balance After</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($transaction['transaction_type']); ?></td>
                                            <td>$<?php echo number_format($transaction['amount'], 2); ?></td>
                                            <td>$<?php echo number_format($transaction['balance_after'], 2); ?></td>
                                            <td><?php echo date('F j, Y', strtotime($transaction['transaction_date'])); ?></td>
                                            <td><?php echo htmlspecialchars($transaction['description'] ?? 'N/A'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No recent transactions found.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Actions Section -->
            <section class="dashboard-section" id="actions">
                <div class="dashboard-card">
                    <h2><i class="fas fa-tasks"></i> Actions</h2>
                    <div class="accordion">
                        <!-- Open New Account -->
                        <div class="accordion-item">
                            <button class="accordion-header"><i class="fas fa-plus-circle"></i> Open New Account <i class="fas fa-chevron-down"></i></button>
                            <div class="accordion-content">
                                <div class="form-card">
                                    <?php if ($account_error): ?>
                                        <p class="error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($account_error); ?></p>
                                    <?php endif; ?>
                                    <?php if ($account_success): ?>
                                        <p class="success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($account_success); ?></p>
                                    <?php endif; ?>
                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label for="account_type">Account Type</label>
                                            <select name="account_type" id="account_type" required>
                                                <option value="">Select Account Type</option>
                                                <?php foreach ($account_types as $type): ?>
                                                    <?php
                                                    $lock_in = $type['type_name'] === 'Time Deposit' ? ' (6-month lock-in)' : ($type['type_name'] === 'Fixed Account' ? ' (12-month lock-in)' : '');
                                                    ?>
                                                    <option value="<?php echo htmlspecialchars($type['type_name']); ?>">
                                                        <?php echo htmlspecialchars($type['type_name'] . ' (Min: $' . number_format($type['minimum_opening_balance'], 2) . ')' . $lock_in); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="initial_deposit">Initial Deposit ($)</label>
                                            <input type="number" name="initial_deposit" id="initial_deposit" step="0.01" min="0" required>
                                        </div>
                                        <button type="submit" name="open_account" class="btn">Open Account</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Apply for a Loan -->
                        <div class="accordion-item">
                            <button class="accordion-header"><i class="fas fa-hand-holding-usd"></i> Apply for a Loan <i class="fas fa-chevron-down"></i></button>
                            <div class="accordion-content">
                                <div class="form-card">
                                    <?php if ($loan_error): ?>
                                        <p class="error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($loan_error); ?></p>
                                    <?php endif; ?>
                                    <?php if ($loan_success): ?>
                                        <p class="success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($loan_success); ?></p>
                                    <?php endif; ?>
                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label for="purpose">Loan Purpose</label>
                                            <input type="text" name="purpose" id="purpose" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="amount">Loan Amount ($)</label>
                                            <input type="number" name="amount" id="amount" step="0.01" min="1000" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="period">Loan Period (Months)</label>
                                            <input type="number" name="period" id="period" min="6" max="36" required>
                                        </div>
                                        <button type="submit" name="apply_loan" class="btn">Apply for Loan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Panel -->
                    <div class="transactions-panel">
                        <h3><i class="fas fa-exchange-alt"></i> Manage Transactions</h3>
                        <div class="tabs">
                            <button class="tab-button active" data-tab="deposit"><i class="fas fa-plus"></i> Deposit</button>
                            <button class="tab-button" data-tab="withdrawal"><i class="fas fa-minus"></i> Withdrawal</button>
                            <button class="tab-button" data-tab="loan-payment"><i class="fas fa-money-check-alt"></i> Loan Payment</button>
                        </div>
                        <div class="tab-content">
                            <!-- Deposit -->
                            <div class="tab-pane active" id="deposit">
                                <?php if ($deposit_error): ?>
                                    <p class="error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($deposit_error); ?></p>
                                <?php endif; ?>
                                <?php if ($deposit_success): ?>
                                    <p class="success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($deposit_success); ?></p>
                                <?php endif; ?>
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="account_id_deposit">Select Account</label>
                                        <select name="account_id" id="account_id_deposit" required>
                                            <option value="">Select Account</option>
                                            <?php foreach ($accounts as $account): ?>
                                                <option value="<?php echo $account['AccountID']; ?>">
                                                    <?php echo htmlspecialchars($account['Account_Type'] . ' ($' . number_format($account['Balance'], 2) . ')'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="deposit_amount">Deposit Amount ($)</label>
                                        <input type="number" name="deposit_amount" id="deposit_amount" step="0.01" min="0" required>
                                    </div>
                                    <button type="submit" name="deposit" class="btn">Deposit</button>
                                </form>
                            </div>

                            <!-- Withdrawal -->
                            <div class="tab-pane" id="withdrawal">
                                <?php if ($withdrawal_error): ?>
                                    <p class="error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($withdrawal_error); ?></p>
                                <?php endif; ?>
                                <?php if ($withdrawal_success): ?>
                                    <p class="success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($withdrawal_success); ?></p>
                                <?php endif; ?>
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="account_id_withdraw">Select Account</label>
                                        <select name="account_id" id="account_id_withdraw" required>
                                            <option value="">Select Account</option>
                                            <?php foreach ($accounts as $account): ?>
                                                <option value="<?php echo $account['AccountID']; ?>">
                                                    <?php echo htmlspecialchars($account['Account_Type'] . ' ($' . number_format($account['Balance'], 2) . ')'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="withdrawal_amount">Withdrawal Amount ($)</label>
                                        <input type="number" name="withdrawal_amount" id="withdrawal_amount" step="0.01" min="0" required>
                                    </div>
                                    <button type="submit" name="withdraw" class="btn">Withdraw</button>
                                </form>
                            </div>

                            <!-- Loan Payment -->
                            <div class="tab-pane" id="loan-payment">
                                <?php if ($payment_error): ?>
                                    <p class="error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($payment_error); ?></p>
                                <?php endif; ?>
                                <?php if ($payment_success): ?>
                                    <p class="success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($payment_success); ?></p>
                                <?php endif; ?>
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="loan_id">Select Loan</label>
                                        <select name="loan_id" id="loan_id" required>
                                            <option value="">Select Loan</option>
                                            <?php foreach ($loans as $loan): ?>
                                                <option value="<?php echo $loan['LoanID']; ?>">
                                                    <?php echo htmlspecialchars($loan['Purpose'] . ' ($' . number_format($loan['Total_Amount'], 2) . ')'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_amount">Payment Amount ($)</label>
                                        <input type="number" name="payment_amount" id="payment_amount" step="0.01" min="0" required>
                                    </div>
                                    <button type="submit" name="pay_loan" class="btn">Make Payment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <?php
    $con->close();
    ?>
</body>
</html>