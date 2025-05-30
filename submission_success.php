<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if success message exists
$successMessage = $_SESSION['success'] ?? null;
$loanID = $_SESSION['loan_id'] ?? null;

// Clear session variables
unset($_SESSION['success']);
unset($_SESSION['loan_id']);

// Redirect if no success message
if (!$successMessage) {
    header('Location: Service1.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application Submitted</title>
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
                    <span class="logo-icon">🏦</span>
                    <h1>Loan Management System</h1>
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
            <div class="success-container">
                <div class="success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Loan Application Submitted Successfully!</h2>
                <p class="success-message"><?php echo htmlspecialchars($successMessage); ?></p>
                
                <?php if ($loanID): ?>
                <div class="loan-details-summary">
                    <p>Your loan application is now pending review.</p>
                    <p>You will be notified once your application has been processed.</p>
                </div>
                <?php endif; ?>
                
                <div class="success-actions">
                    <a href="Service1.php" class="btn btn-primary">Back to Loan Management</a>
                    
                    <?php if ($loanID): ?>
                    <a href="search.php?loan_id=<?php echo $loanID; ?>" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> View Application
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Loan Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>