<?php
// Include database connection
$conn = require_once 'connect_db.php';

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize variables
$loan = null;
$error = '';
$search_performed = false;

// Check if a loan ID was submitted
if (isset($_GET['loan_id']) && !empty($_GET['loan_id'])) {
    $search_performed = true;
    
    // Sanitize the input
    $loan_id = sanitize_input($_GET['loan_id']);
    
    // Prepare and execute the query with a parameterized statement
    try {
        $stmt = mysqli_prepare($conn, "SELECT * FROM loan_application WHERE Loan_AppID = ?");
        mysqli_stmt_bind_param($stmt, "i", $loan_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $loan = mysqli_fetch_assoc($result);
        } else {
            $error = "No loan found with ID: " . $loan_id;
        }
        
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Search Results</title>
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <span class="logo-icon"></span>
                    <h1>Loan Applicants</h1>
                </div>
                <div class="user-info">
                    <div class="user-avatar"><i class="fas fa-user"></i></div>
                    <div class="user-name">Admin User</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <!-- Search Section -->
            <section class="search-section">
                <div class="search-container">
                    <h2 class="search-title">Search Loan Application</h2>
                    <div class="search-input-group">
                        <form action="search.php" method="GET" id="searchForm">
                            <div class="search-input-wrapper">
                                <i class="fas fa-search"></i>
                                <input type="text" name="loan_id" id="searchInput" class="search-input" 
                                    placeholder="Enter Loan Application ID" 
                                    value="<?php echo isset($_GET['loan_id']) ? sanitize_input($_GET['loan_id']) : ''; ?>" 
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
            </section>
            
            <!-- Results Section -->
            <section class="results-section">
                <?php if ($search_performed): ?>
                    <?php if ($loan): ?>
                        <div class="tabs-container">
                            <div class="tabs">
                                <div class="tab active">Loan Details</div>
                            </div>
                            
                            <div class="tab-content active">
                                <div class="loan-details">
                                    <h3>Loan Application #<?php echo $loan['Loan_AppID']; ?></h3>
                                    
                                    <div class="detail-grid">
                                        <div class="detail-section">
                                            <h4>Applicant Information</h4>
                                            <div class="detail-row">
                                                <div class="detail-label">Name</div>
                                                <div class="detail-value"><?php echo $loan['Firstname'] . ' ' . $loan['Middlename'] . ' ' . $loan['Lastname']; ?></div>
                                            </div>
                                            <?php if ($loan['MemberID']): ?>
                                            <div class="detail-row">
                                                <div class="detail-label">Member ID</div>
                                                <div class="detail-value"><?php echo $loan['MemberID']; ?></div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="detail-section">
                                            <h4>Loan Information</h4>
                                            <div class="detail-row">
                                                <div class="detail-label">Purpose</div>
                                                <div class="detail-value"><?php echo $loan['Purpose']; ?></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">Amount</div>
                                                <div class="detail-value">₱<?php echo number_format($loan['Amount'], 2); ?></div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">Interest</div>
                                                <div class="detail-value"><?php echo $loan['Interest']; ?>%</div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">Total Amount</div>
                                                <div class="detail-value">₱<?php echo number_format($loan['Total_Amount'], 2); ?></div>
                                            </div>
                                        </div>
                                        
                                        <div class="detail-section">
                                            <h4>Payment Terms</h4>
                                            <div class="detail-row">
                                                <div class="detail-label">Period</div>
                                                <div class="detail-value"><?php echo $loan['Period']; ?> months</div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">Monthly Amortization</div>
                                                <div class="detail-value">₱<?php echo number_format($loan['Monthly_Amortization'], 2); ?></div>
                                            </div>
                                        </div>
                                        
                                        <div class="detail-section">
                                            <h4>Status Information</h4>
                                            <div class="detail-row">
                                                <div class="detail-label">Status</div>
                                                <div class="detail-value" id="status-value">
                                                    <span class="status-badge status-<?php echo strtolower($loan['Status']); ?>">
                                                        <?php echo ucfirst($loan['Status']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="detail-row">
                                                <div class="detail-label">Application Date</div>
                                                <div class="detail-value"><?php echo date('F j, Y', strtotime($loan['Requested_at'])); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="action-buttons">
                                    <?php if (strtolower($loan['Status']) === 'pending'): ?>
                                        <button id="approve-btn" data-loan-id="<?php echo $loan['Loan_AppID']; ?>" class="btn btn-success"><i class="fas fa-check"></i> Approve</button>
                                        <button id="reject-btn" data-loan-id="<?php echo $loan['Loan_AppID']; ?>" class="btn btn-danger"><i class="fas fa-times"></i> Reject</button>
                                    <?php endif; ?>
                                    <a href="Service1.php" class="btn btn-primary">New Search</a>
                                    <button onclick="window.print()" class="btn btn-secondary"><i class="fas fa-print"></i> Print</button>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <p class="empty-state-text"><?php echo $error; ?></p>
                            <a href="Service1.php" class="btn btn-primary">Return to Search</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Loan Management System. All rights reserved.</p>
        </div>
    </footer>

    <!-- Toast Container for notifications -->
    <div id="toastContainer" class="toast-container"></div>

    <script>
        // Simple form validation
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            const searchInput = document.getElementById('searchInput');
            if (!searchInput.value.trim()) {
                e.preventDefault();
                showToast('Please enter a Loan Application ID', 'error');
            }
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icon = document.createElement('span');
            icon.className = 'toast-icon';
            
            if (type === 'error') {
                icon.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
            } else if (type === 'success') {
                icon.innerHTML = '<i class="fas fa-check-circle"></i>';
            } else {
                icon.innerHTML = '<i class="fas fa-info-circle"></i>';
            }
            
            const messageSpan = document.createElement('span');
            messageSpan.className = 'toast-message';
            messageSpan.textContent = message;
            
            toast.appendChild(icon);
            toast.appendChild(messageSpan);
            
            const container = document.getElementById('toastContainer');
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('toast-fadeout');
                setTimeout(() => {
                    container.removeChild(toast);
                }, 300);
            }, 3000);
        }

        <?php if ($search_performed && $loan): ?>
        // Show success toast when loan is found
        document.addEventListener('DOMContentLoaded', function() {
            showToast('Loan found successfully', 'success');
        });
        <?php elseif ($search_performed && !$loan): ?>
        // Show error toast when loan is not found
        document.addEventListener('DOMContentLoaded', function() {
            showToast('<?php echo $error; ?>', 'error');
        });
        <?php endif; ?>
    </script>
    
    <!-- Load loan action scripts only if we have a loan -->
    <?php if ($loan): ?>
    <script src="request_actions.js"></script>
    <?php endif; ?>
</body>
</html>
