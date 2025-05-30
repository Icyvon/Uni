<?php
// Enable error reporting for logging, but suppress display
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Include database functions
require_once 'db_functions.php';

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

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
error_log('submit_entry.php: Raw input received: ' . $rawInput);
$data = json_decode($rawInput, true);

// Validate JSON parsing
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log('submit_entry.php: JSON decode error: ' . json_last_error_msg());
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON payload'
    ]);
    exit;
}

// Validate required fields
if (!isset($data['accountId']) || !is_numeric($data['accountId']) || intval($data['accountId']) <= 0) {
    error_log('submit_entry.php: Invalid accountId: ' . json_encode($data));
    echo json_encode([
        'success' => false,
        'message' => 'Valid positive Account ID is required'
    ]);
    exit;
}
if (!isset($data['amount']) || !is_numeric($data['amount']) || floatval($data['amount']) <= 0) {
    error_log('submit_entry.php: Invalid amount: ' . json_encode($data));
    echo json_encode([
        'success' => false,
        'message' => 'Valid positive amount is required'
    ]);
    exit;
}

$accountId = intval($data['accountId']);
$amount = floatval($data['amount']);
error_log("submit_entry.php: Processing deposit for accountId: $accountId, amount: $amount");

try {
    // Verify makeDeposit function exists
    if (!function_exists('makeDeposit')) {
        error_log('submit_entry.php: makeDeposit function not defined');
        echo json_encode([
            'success' => false,
            'message' => 'Server error: Deposit functionality unavailable'
        ]);
        exit;
    }

    // Process deposit
    error_log('submit_entry.php: Before calling makeDeposit');
    $result = makeDeposit($accountId, $amount);
    error_log('submit_entry.php: After calling makeDeposit, result: ' . json_encode($result));
    echo json_encode($result);
} catch (Exception $e) {
    error_log('submit_entry.php: Error processing deposit: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    echo json_encode([
        'success' => false,
        'message' => 'Server error: Unable to process deposit'
    ]);
}
?>