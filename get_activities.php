<?php
// Enable error reporting for logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Include database connection
require_once 'connect_db.php';

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['accountId']) || !is_numeric($data['accountId']) || intval($data['accountId']) <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Valid positive Account ID is required'
    ]);
    exit;
}

$accountId = intval($data['accountId']);

try {
    $query = "SELECT t.*, a.Account_Type as type_name 
              FROM transactions t 
              JOIN account a ON t.AccountID = a.AccountID 
              WHERE t.AccountID = ? 
              ORDER BY t.transaction_date DESC 
              LIMIT 10";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $accountId);
    $stmt->execute();
    $result = $stmt->get_result();

    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

    echo json_encode([
        'success' => true,
        'transactions' => $transactions
    ]);
} catch (Exception $e) {
    error_log('Error in get_activities.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Server error: Unable to fetch transactions'
    ]);
}

$con->close();
?>