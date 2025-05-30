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

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Use POST.'
    ]);
    exit;
}

// Get request data
$rawInput = file_get_contents('php://input');
error_log('Raw input received: ' . $rawInput);
$data = json_decode($rawInput, true);

// Validate JSON parsing
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log('JSON decode error: ' . json_last_error_msg());
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON payload'
    ]);
    exit;
}

// Validate required fields
if (!isset($data['accountId']) || !is_numeric($data['accountId']) || intval($data['accountId']) <= 0) {
    error_log('Invalid accountId: ' . json_encode($data));
    echo json_encode([
        'success' => false,
        'message' => 'Valid positive Account ID is required'
    ]);
    exit;
}

$accountId = intval($data['accountId']);
error_log('Processing accountId: ' . $accountId);

try {
    // Get account details
    $account = getAccountById($accountId);

    if ($account) {
        echo json_encode([
            'success' => true,
            'account' => $account
        ]);
    } else {
        error_log('Account not found for ID: ' . $accountId);
        echo json_encode([
            'success' => false,
            'message' => 'Account not found'
        ]);
    }
} catch (Exception $e) {
    error_log('Error in get_member_data.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Server error: Unable to fetch account'
    ]);
}

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
    if (!$stmt) {
        error_log('Prepare failed: ' . $con->error);
        return null;
    }
    $stmt->bind_param("i", $accountId);
    if (!$stmt->execute()) {
        error_log('Execute failed: ' . $stmt->error);
        return null;
    }
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $account = $result->fetch_assoc();
        
        // Check for account restrictions
        $query = "SELECT * FROM account_restrictions WHERE AccountID = ?";
        $stmt = $con->prepare($query);
        if (!$stmt) {
            error_log('Prepare failed for restrictions: ' . $con->error);
            return $account;
        }
        $stmt->bind_param("i", $accountId);
        if (!$stmt->execute()) {
            error_log('Execute failed for restrictions: ' . $stmt->error);
            return $account;
        }
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

$con->close();
?>