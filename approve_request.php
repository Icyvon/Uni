<?php
// Include database connection
$conn = require_once 'connect_db.php';

// Set header to respond with JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors directly, we'll handle them in JSON response

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

// Log for debugging
error_log("approve_request.php received request: " . json_encode($_POST));

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if loan ID is provided
    if (isset($_POST['loan_id']) && !empty($_POST['loan_id'])) {
        // Get and sanitize loan ID
        $loan_id = sanitize_input($_POST['loan_id']);
        error_log("Processing loan ID: $loan_id");
        
        // Start transaction for data integrity
        mysqli_begin_transaction($conn);
        
        try {
            // First, get the loan application data
            $stmt = mysqli_prepare($conn, "SELECT * FROM loan_application WHERE Loan_AppID = ?");
            if (!$stmt) {
                throw new Exception("Database error: " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($stmt, "i", $loan_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $loan_app = mysqli_fetch_assoc($result);
                error_log("Found loan application with status: " . $loan_app['Status']);
                
                // Check if the loan is already approved or rejected
                if (strtolower($loan_app['Status']) !== 'pending') {
                    throw new Exception("This loan application has already been " . strtolower($loan_app['Status']));
                }
                
                // Update the loan application status to 'approved'
                $update_stmt = mysqli_prepare($conn, "UPDATE loan_application SET Status = 'approved' WHERE Loan_AppID = ?");
                if (!$update_stmt) {
                    throw new Exception("Database error on update: " . mysqli_error($conn));
                }
                
                mysqli_stmt_bind_param($update_stmt, "i", $loan_id);
                mysqli_stmt_execute($update_stmt);
                
                if (mysqli_stmt_affected_rows($update_stmt) <= 0) {
                    throw new Exception("Failed to update loan application status");
                }
                
                error_log("Updated loan application status to approved");
                
                // Insert the loan application data into the loan table
                $insert_stmt = mysqli_prepare($conn, 
                    "INSERT INTO loan (
                        MemberID, Firstname, Middlename, Lastname, Purpose, 
                        Amount, Interest, Total_Amount, Monthly_Amortization, Period, Status
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')"
                );
                
                if (!$insert_stmt) {
                    throw new Exception("Database error on insert: " . mysqli_error($conn));
                }
                
                mysqli_stmt_bind_param($insert_stmt, "issssddddi", 
                    $loan_app['MemberID'], 
                    $loan_app['Firstname'], 
                    $loan_app['Middlename'], 
                    $loan_app['Lastname'], 
                    $loan_app['Purpose'], 
                    $loan_app['Amount'], 
                    $loan_app['Interest'], 
                    $loan_app['Total_Amount'], 
                    $loan_app['Monthly_Amortization'], 
                    $loan_app['Period']
                );
                
                mysqli_stmt_execute($insert_stmt);
                
                if (mysqli_stmt_affected_rows($insert_stmt) <= 0) {
                    throw new Exception("Failed to insert loan data");
                }
                
                error_log("Inserted new loan record");
                
                // If we reached here, all operations were successful
                mysqli_commit($conn);
                
                $response['success'] = true;
                $response['message'] = "Loan application approved successfully";
                $response['status'] = "approved";
            } else {
                throw new Exception("Loan application not found");
            }
        } catch (Exception $e) {
            // Rollback the transaction if any operation failed
            mysqli_rollback($conn);
            
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            error_log("Error in approve_request.php: " . $e->getMessage());
        }
    } else {
        $response['message'] = "No loan ID provided";
        error_log("Error: No loan ID provided");
    }
} else {
    $response['message'] = "Invalid request method";
    error_log("Error: Invalid request method " . $_SERVER['REQUEST_METHOD']);
}

// Return JSON response
error_log("Sending response: " . json_encode($response));
echo json_encode($response);
exit;