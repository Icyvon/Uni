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

// Check if request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

try {
    $query = "SELECT * FROM account_types";
    $result = $con->query($query);
    
    $types = [];
    while ($row = $result->fetch_assoc()) {
        $types[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'account_types' => $types
    ]);
} catch (Exception $e) {
    error_log('Error in get_account_types.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Server error: Unable to fetch account types'
    ]);
}

$con->close();
?>