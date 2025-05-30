<?php
// Start output buffering to prevent stray output
ob_start();

// Enable error reporting for logging, but suppress display
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Include database functions
if (!file_exists('db_functions.php')) {
    error_log('remove_entry.php: db_functions.php not found in ' . __DIR__);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Server error: Required file missing'
    ]);
    ob_end_flush();
    exit;
}
require_once 'db_functions.php';

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log('remove_entry.php: Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Use POST.'
    ]);
    ob_end_flush();
    exit;
}

// Get request data
$rawInput = file_get_contents('php://input');
error_log('remove_entry.php: Raw input received: ' . $rawInput);
$data = json_decode($rawInput, true);

// Validate JSON parsing
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log('remove_entry.php: JSON decode error: ' . json_last_error_msg());
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON payload'
    ]);
    ob_end_flush();
    exit;
}

// Validate required fields
if (!isset($data['accountId']) || !is_numeric($data['accountId']) || intval($data['accountId']) <= 0) {
    error_log('remove_entry.php: Invalid accountId: ' . json_encode($data));
    echo json_encode([
        'success' => false,
        'message' => 'Valid positive Account ID is required'
    ]);
    ob_end_flush();
    exit;
}
if (!isset($data['amount']) || !is_numeric($data['amount']) || floatval($data['amount']) <= 0) {
    error_log('remove_entry.php: Invalid amount: ' . json_encode($data));
    echo json_encode([
        'success' => false,
        'message' => 'Valid positive amount is required'
    ]);
    ob_end_flush();
    exit;
}

$accountId = intval($data['accountId']);
$amount = floatval($data['amount']);
error_log("remove_entry.php: Processing withdrawal for accountId: $accountId, amount: $amount");

try {
    // Verify makeWithdrawal function exists
    if (!function_exists('makeWithdrawal')) {
        error_log('remove_entry.php: makeWithdrawal function not defined');
        echo json_encode([
            'success' => false,
            'message' => 'Server error: Withdrawal functionality unavailable'
        ]);
        ob_end_flush();
        exit;
    }

    // Process withdrawal
    error_log('remove_entry.php: Before calling makeWithdrawal');
    $result = makeWithdrawal($accountId, $amount);
    error_log('remove_entry.php: After calling makeWithdrawal, result: ' . json_encode($result));
    echo json_encode($result);
} catch (Exception $e) {
    error_log('remove_entry.php: Error processing withdrawal: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    echo json_encode([
        'success' => false,
        'message' => 'Server error: Unable to process withdrawal - ' . $e->getMessage()
    ]);
} finally {
    ob_end_flush();
}
?>