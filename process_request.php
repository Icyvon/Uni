<?php
// Include database connection
$con = require 'connect_db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirect to loan page if not POST request
    header('Location: Service1.php');
    exit;
}

// Validate required fields
$requiredFields = ['memberID', 'firstname', 'lastname', 'purpose', 'amount', 'interest', 'period', 'total_amount', 'monthly_amortization'];
$missingFields = [];

foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    $_SESSION['error'] = 'Missing required fields: ' . implode(', ', $missingFields);
    header('Location: Service1.php');
    exit;
}

// Sanitize and validate input data
$memberID = (int) $_POST['memberID'];
$firstname = trim($_POST['firstname']);
$middlename = trim($_POST['middlename'] ?? '');
$lastname = trim($_POST['lastname']);
$purpose = trim($_POST['purpose']);
$amount = (float) $_POST['amount'];
$interest = (float) $_POST['interest'];
$period = (int) $_POST['period'];
$total_amount = (float) $_POST['total_amount'];
$monthly_amortization = (float) $_POST['monthly_amortization'];

// Verify member is active
$stmt = mysqli_prepare($con, "SELECT MemberID FROM member WHERE MemberID = ?");
mysqli_stmt_bind_param($stmt, 'i', $memberID);
mysqli_stmt_execute($stmt);
$verifyResult = mysqli_stmt_get_result($stmt);

if (!$verifyResult || mysqli_num_rows($verifyResult) === 0) {
    $_SESSION['error'] = 'Member is not active or does not exist';
    header('Location: Service1.php');
    exit;
}
mysqli_stmt_close($stmt);

// Check for existing pending loan applications for this member
$stmt = mysqli_prepare($con, "SELECT Loan_AppID FROM loan_application WHERE MemberID = ? AND status = 'pending'");
mysqli_stmt_bind_param($stmt, 'i', $memberID);
mysqli_stmt_execute($stmt);
$checkLoanResult = mysqli_stmt_get_result($stmt);

if (!$checkLoanResult) {
    error_log('Error checking existing loan applications: ' . mysqli_error($con));
    $_SESSION['error'] = 'Error checking existing loan applications';
    header('Location: Service1.php');
    exit;
}

if (mysqli_num_rows($checkLoanResult) > 0) {
    $_SESSION['error'] = 'You already have a pending loan application. Please wait for approval or contact support.';
    header('Location: Service1.php');
    exit;
}
mysqli_stmt_close($stmt);

// Verify calculations (redundant check for security)
$calculatedTotalAmount = $amount + ($amount * ($interest / 100));
$calculatedMonthlyAmortization = $calculatedTotalAmount / $period;

// Check if calculated values match within a small epsilon (floating point comparison)
$epsilon = 0.01; // Allow for slight rounding differences
if (abs($calculatedTotalAmount - $total_amount) > $epsilon || 
    abs($calculatedMonthlyAmortization - $monthly_amortization) > $epsilon) {
    $_SESSION['error'] = 'Calculation mismatch. Please try again.';
    header('Location: Service1.php');
    exit;
}

// Generate a reference number for the loan application
$referenceNumber = 'LOAN-' . date('YmdHis') . '-' . rand(1000, 9999);

// Current date for application date
$applicationDate = date('Y-m-d H:i:s');

// Set status
$status = 'pending';

// Insert loan application data into the database
$insertQuery = "INSERT INTO loan_application (
    MemberID, firstname, middlename, lastname, purpose, amount, interest, period, total_amount, monthly_amortization, status, Requested_at, reference_number
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $insertQuery);
mysqli_stmt_bind_param($stmt, 'issssddiddssi', 
    $memberID, $firstname, $middlename, $lastname, $purpose, $amount, $interest, $period, $total_amount, $monthly_amortization, $status, $applicationDate, $referenceNumber
);
$insertResult = mysqli_stmt_execute($stmt);

if (!$insertResult) {
    error_log('Failed to submit loan application: ' . mysqli_error($con));
    $_SESSION['error'] = 'Failed to submit loan application. Please try again later.';
    header('Location: Service1.php');
    exit;
}

// Success - get the inserted loan ID
$loanID = mysqli_insert_id($con);
mysqli_stmt_close($stmt);
mysqli_close($con);

// Set success message and redirect
$_SESSION['success'] = 'Loan application submitted successfully! Reference Number: ' . $referenceNumber;
$_SESSION['loan_id'] = $loanID;
header('Location: submission_success.php');
exit;
?>