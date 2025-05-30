<?php
// Include database connection
$conn = require_once 'connect_db.php';

// Set header to respond with JSON
header('Content-Type: application/json');

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'status' => ''
];

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if loan ID is provided
    if (isset($_POST['loan_id']) && !empty($_POST['loan_id'])) {
        // Get and sanitize loan ID
        $loan_id = sanitize_input($_POST['loan_id']);
        
        try {
            // First, check if the loan application exists and its current status
            $stmt = mysqli_prepare($conn, "SELECT Status FROM loan_application WHERE Loan_AppID = ?");
            mysqli_stmt_bind_param($stmt, "i", $loan_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $loan_app = mysqli_fetch_assoc($result);
                
                // Check if the loan is already approved or rejected
                if (strtolower($loan_app['Status']) !== 'pending') {
                    throw new Exception("This loan application has already been " . strtolower($loan_app['Status']));
                }
                
                // Update the loan application status to 'rejected'
                $update_stmt = mysqli_prepare($conn, "UPDATE loan_application SET Status = 'rejected' WHERE Loan_AppID = ?");
                mysqli_stmt_bind_param($update_stmt, "i", $loan_id);
                mysqli_stmt_execute($update_stmt);
                
                if (mysqli_stmt_affected_rows($update_stmt) <= 0) {
                    throw new Exception("Failed to update loan application status");
                }
                
                $response['success'] = true;
                $response['message'] = "Loan application rejected successfully";
                $response['status'] = "rejected";
            } else {
                throw new Exception("Loan application not found");
            }
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
    } else {
        $response['message'] = "No loan ID provided";
    }
} else {
    $response['message'] = "Invalid request method";
}

// Return JSON response
echo json_encode($response);
exit;
