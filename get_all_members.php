<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Include database connection
require_once 'connect_db.php';

// Set headers
header('Content-Type: application/json');

try {
    $query = "SELECT a.*, m.Firstname, m.Lastname, t.type_name, t.allows_deposits, 
              t.allows_withdrawals, r.restriction_id, r.restriction_description, 
              r.early_withdrawal_penalty, r.minimum_balance, r.maturity_date
              FROM account a
              JOIN member m ON a.MemberID = m.MemberID
              JOIN account_types t ON a.Account_Type = t.type_name
              LEFT JOIN account_restrictions r ON a.AccountID = r.AccountID";
    
    $result = $con->query($query);
    
    if (!$result) {
        error_log('Query failed: ' . $con->error);
        echo json_encode([
            'success' => false,
            'message' => 'Database query failed'
        ]);
        exit;
    }
    
    $accounts = [];
    while ($row = $result->fetch_assoc()) {
        if ($row['restriction_id']) {
            $row['restrictions'] = [
                'restriction_id' => $row['restriction_id'],
                'restriction_description' => $row['restriction_description'],
                'early_withdrawal_penalty' => $row['early_withdrawal_penalty'],
                'minimum_balance' => $row['minimum_balance'],
                'maturity_date' => $row['maturity_date']
            ];
        } else {
            $row['restrictions'] = null;
        }
        unset($row['restriction_id'], $row['restriction_description'], 
              $row['early_withdrawal_penalty'], $row['minimum_balance'], 
              $row['maturity_date']);
        $accounts[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'accounts' => $accounts
    ]);
} catch (Exception $e) {
    error_log('Error in get_all_members.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}

$con->close();
?>