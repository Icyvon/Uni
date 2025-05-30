<?php
header('Content-Type: application/json');

$host = '127.0.0.1';
$dbname = 'pay360';
$username = 'root';
$password = ''; // Update with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch counts
    $total_members = $pdo->query("SELECT COUNT(*) FROM member")->fetchColumn();
    $total_loans = $pdo->query("SELECT SUM(Total_Amount) FROM loan")->fetchColumn();
    $total_savings = $pdo->query("SELECT SUM(Balance) FROM account WHERE Account_Type = 'Savings'")->fetchColumn();
    $total_applicants = $pdo->query("SELECT COUNT(*) FROM membership_application WHERE Status = 'pending'")->fetchColumn();

    // Format response
    $response = [
        'total_members' => $total_members ?: 0,
        'total_loans' => number_format($total_loans ?: 0, 2, '.', ''),
        'total_savings' => number_format($total_savings ?: 0, 2, '.', ''),
        'total_applicants' => $total_applicants ?: 0
    ];

    echo json_encode($response);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>