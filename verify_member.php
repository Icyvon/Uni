<?php
// Database connection
$con = require_once 'connect_db.php'; // Use the same connection pattern as in process_loan.php

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Check if MemberID is provided
if (!isset($_POST['memberID']) || empty($_POST['memberID'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Member ID is required'
    ]);
    exit;
}

// Get the member ID from POST data
$memberID = mysqli_real_escape_string($con, $_POST['memberID']);

// Query to check if member exists
// Note: There is no 'status' column in the member table based on the SQL dump
// We'll simply check if the member exists for now
$query = "SELECT * FROM member WHERE MemberID = '$memberID'";
$result = mysqli_query($con, $query);

if (!$result) {
    // Query error
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . mysqli_error($con)
    ]);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    // Member found
    $member = mysqli_fetch_assoc($result);
    
    echo json_encode([
        'success' => true,
        'message' => 'Member verified successfully',
        'member' => [
            'id' => $member['MemberID'],
            'firstname' => $member['Firstname'],
            'middlename' => $member['Middlename'],
            'lastname' => $member['Lastname']
        ]
    ]);
} else {
    // Member not found
    echo json_encode([
        'success' => false,
        'message' => 'Member not found'
    ]);
}

// Close the database connection
mysqli_close($con);
?>