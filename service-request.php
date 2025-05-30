<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application Form</title>
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
                    <h1>Loan Application</h1>
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
            <div class="back-button-container">
                <a href="Service1.php" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to Loan Page
                </a>
            </div>
            
            <!-- Loan Application Form Section -->
            <section class="form-section">
                <div class="form-container">
                    <h2 class="form-title">New Loan Application</h2>
                    
                    <form id="loanApplicationForm" method="POST" action="process_request.php">
                        <div class="form-group">
                            <label for="memberID">Member ID:</label>
                            <div class="input-with-icon">
                                <input type="text" id="memberID" name="memberID" class="form-control" required>
                                <button type="button" id="verifyMemberBtn" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-search"></i> Verify
                                </button>
                            </div>
                            <small id="memberIDFeedback" class="feedback-text"></small>
                        </div>
                        
                        <div class="member-details-section" style="display: none;">
                            <div class="form-group">
                                <label for="firstname">First Name:</label>
                                <input type="text" id="firstname" name="firstname" class="form-control" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="middlename">Middle Name:</label>
                                <input type="text" id="middlename" name="middlename" class="form-control" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="lastname">Last Name:</label>
                                <input type="text" id="lastname" name="lastname" class="form-control" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="purpose">Purpose of Loan:</label>
                                <textarea id="purpose" name="purpose" class="form-control" rows="3" required></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="amount">Loan Amount (₱):</label>
                                    <input type="number" id="amount" name="amount" class="form-control" min="1000" step="100" required>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="interest">Interest Rate (%):</label>
                                    <input type="number" id="interest" name="interest" class="form-control" min="0" max="100" step="0.01" required>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label for="period">Payment Period (Months):</label>
                                    <input type="number" id="period" name="period" class="form-control" min="1" max="60" required>
                                </div>
                            </div>
                            <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST);
    exit;
}
?>
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="totalAmount">Total Amount to be Paid (₱):</label>
                                    <input type="text" id="totalAmount" name="totalAmount" class="form-control calculated-field" readonly>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="monthlyAmortization">Monthly Amortization (₱):</label>
                                    <input type="text" id="monthlyAmortization" name="monthlyAmortization" class="form-control calculated-field" readonly>
                                </div>
                            </div>
                            
                            <div class="calculation-info">
                                <p><i class="fas fa-info-circle"></i> The values above are automatically calculated based on your inputs.</p>
                                <ul>
                                    <li>Total Amount = Amount + (Amount × Interest Rate)</li>
                                    <li>Monthly Amortization = Total Amount ÷ Period</li>
                                </ul>
                            </div>
                            
                            <div class="form-group">
                                <button type="button" id="calculateBtn" class="btn btn-secondary">
                                    <i class="fas fa-calculator"></i> Calculate
                                </button>
                                <button type="submit" id="submitBtn" class="btn btn-primary" disabled>
                                    <i class="fas fa-paper-plane"></i> Submit Application
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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

    <script src="loan-form.js"></script>
</body>
</html>


