<?php
/**
 * Update Handler
 * 
 * This script handles AJAX requests to update loan application status
 * and serves as a generic endpoint for various loan-related updates
 */

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
    'data' => null
];

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if action is provided
    if (isset($_POST['action']) && !empty($_POST['action'])) {
        $action = sanitize_input($_POST['action']);
        
        switch ($action) {
            case 'get_loan_status':
                // Get the status of a loan application
                if (isset($_POST['loan_id']) && !empty($_POST['loan_id'])) {
                    $loan_id = sanitize_input($_POST['loan_id']);
                    
                    try {
                        $stmt = mysqli_prepare($conn, "SELECT Status FROM loan_application WHERE Loan_AppID = ?");
                        mysqli_stmt_bind_param($stmt, "i", $loan_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if (mysqli_num_rows($result) > 0) {
                            $loan = mysqli_fetch_assoc($result);
                            $response['success'] = true;
                            $response['data'] = [
                                'status' => $loan['Status']
                            ];
                        } else {
                            throw new Exception("Loan application not found");
                        }
                    } catch (Exception $e) {
                        $response['message'] = $e->getMessage();
                    }
                } else {
                    $response['message'] = "No loan ID provided";
                }
                break;
                
            case 'check_loan_exists':
                // Check if a loan exists in the loan table
                if (isset($_POST['member_id']) && !empty($_POST['member_id'])) {
                    $member_id = sanitize_input($_POST['member_id']);
                    
                    try {
                        $stmt = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM loan WHERE MemberID = ? AND Status = 'active'");
                        mysqli_stmt_bind_param($stmt, "i", $member_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if ($row = mysqli_fetch_assoc($result)) {
                            $response['success'] = true;
                            $response['data'] = [
                                'exists' => ($row['count'] > 0),
                                'count' => $row['count']
                            ];
                        }
                    } catch (Exception $e) {
                        $response['message'] = $e->getMessage();
                    }
                } else {
                    $response['message'] = "No member ID provided";
                }
                break;
                
            default:
                $response['message'] = "Unknown action";
                break;
        }
    } else {
        $response['message'] = "No action specified";
    }
} else {
    $response['message'] = "Invalid request method";
}

// Return JSON response
echo json_encode($response);
exit;
