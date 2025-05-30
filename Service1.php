    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loan Search System</title>
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
                    <a href="Admin.php" class="btn btn-secondary back-button">
                        <i class="fas fa-arrow-left"></i> Back to Admin
                    </a>
                </div>


                <!-- Tabs for Navigation -->
                <div class="page-tabs">
                    <div class="tab active" data-tab="search">Search Loan</div>
                    <div class="tab" data-tab="apply">Apply for Loan</div>
                </div>

                <!-- Search Section -->
                <section class="search-section tab-panel active" id="search-panel">
                    <div class="search-container">
                        <h2 class="search-title">Search Loan Application</h2>
                        <div class="search-input-group">
                            <form action="search.php" method="GET" id="searchForm">
                                <div class="search-input-wrapper">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="loan_id" id="searchInput" class="search-input" placeholder="Enter Loan Application ID" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="instructions-container">
                        <h3><i class="fas fa-info-circle"></i> How to search for loans</h3>
                        <ul>
                            <li>Enter the Loan Application ID in the search box above</li>
                            <li>Click the search button or press Enter</li>
                            <li>The system will display all relevant information for the matching loan</li>
                            <li>If no loan is found, you'll see a notification</li>
                        </ul>
                    </div>
                </section>

                <!-- Loan Application Form Section -->
                <section class="loan-form-section tab-panel" id="apply-panel">
                    <div class="form-container">
                        <h2 class="form-title">Apply for Loan</h2>
                        
                        <form id="loanApplicationForm" method="POST" action="process_request.php">
                            <div class="form-grid">
                                <!-- Member ID Search -->
                                <div class="form-group">
                                    <label for="memberID">Member ID <span class="required">*</span></label>
                                    <div class="input-with-button">
                                        <input type="text" id="memberID" name="memberID" required>
                                        <button type="button" id="verifyMemberBtn" class="btn btn-secondary">Verify</button>
                                    </div>
                                    <div id="memberStatus" class="member-status"></div>
                                </div>
                                
                                <!-- Member Info Section -->
                                <div class="form-member-info" id="memberInfoSection" style="display: none;">
                                    <div class="form-group">
                                        <label for="firstname">First Name</label>
                                        <input type="text" id="firstname" name="firstname" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" id="middlename" name="middlename" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="lastname">Last Name</label>
                                        <input type="text" id="lastname" name="lastname" readonly>
                                    </div>
                                    
                                    <div class="form-group full-width">
                                        <label for="purpose">Purpose of Loan <span class="required">*</span></label>
                                        <textarea id="purpose" name="purpose" rows="3" required></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="amount">Loan Amount <span class="required">*</span></label>
                                        <div class="input-prefix">
                                            <span class="prefix">$</span>
                                            <input type="number" id="amount" name="amount" min="100" step="100" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="interest">Interest Rate (%) <span class="required">*</span></label>
                                        <div class="input-suffix">
                                            <input type="number" id="interest" name="interest" min="1" max="20" step="0.1" required>
                                            <span class="suffix">%</span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="period">Payment Period (Months) <span class="required">*</span></label>
                                        <input type="number" id="period" name="period" min="1" max="60" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Loan Calculation Summary -->
                            <div class="loan-calculation" id="loanCalculation" style="display: none;">
                                <h3>Loan Summary</h3>
                                <div class="calculation-grid">
                                    <div class="calculation-item">
                                        <div class="calculation-label">Principal Amount:</div>
                                        <div class="calculation-value" id="displayAmount">$0.00</div>
                                    </div>
                                    <div class="calculation-item">
                                        <div class="calculation-label">Interest Amount:</div>
                                        <div class="calculation-value" id="displayInterestAmount">$0.00</div>
                                    </div>
                                    <div class="calculation-item highlight">
                                        <div class="calculation-label">Total Amount:</div>
                                        <div class="calculation-value" id="displayTotalAmount">$0.00</div>
                                        <input type="hidden" name="total_amount" id="totalAmount">
                                    </div>
                                    <div class="calculation-item highlight">
                                        <div class="calculation-label">Monthly Payment:</div>
                                        <div class="calculation-value" id="displayMonthlyAmortization">$0.00</div>
                                        <input type="hidden" name="monthly_amortization" id="monthlyAmortization">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" id="calculateLoanBtn">Calculate Loan</button>
                                <button type="submit" class="btn btn-primary" id="submitLoanBtn" style="display: none;">Submit Application</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Instructions -->
                    <div class="instructions-container">
                        <h3><i class="fas fa-info-circle"></i> How to apply for a loan</h3>
                        <ul>
                            <li>Enter your Member ID and click "Verify" to confirm your eligibility</li>
                            <li>Complete all required fields marked with an asterisk (*)</li>
                            <li>Click "Calculate Loan" to see the total amount and monthly payment</li>
                            <li>Review the loan details before clicking "Submit Application"</li>
                            <li>Only active members are eligible to apply for loans</li>
                        </ul>
                    </div>
                </section>
            </div>
        </main>

        <!-- Footer -->
        <footer>
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> Multipurposes System. All rights reserved.</p>
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

            // Tab switching functionality
            const tabs = document.querySelectorAll('.tab');
            const tabPanels = document.querySelectorAll('.tab-panel');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const targetPanel = tab.getAttribute('data-tab');
                    
                    // Remove active class from all tabs and panels
                    tabs.forEach(t => t.classList.remove('active'));
                    tabPanels.forEach(p => p.classList.remove('active'));
                    
                    // Add active class to clicked tab and corresponding panel
                    tab.classList.add('active');
                    document.getElementById(`${targetPanel}-panel`).classList.add('active');
                });
            });

            // Member verification functionality
            document.getElementById('verifyMemberBtn').addEventListener('click', function() {
                const memberID = document.getElementById('memberID').value.trim();
                
                if (!memberID) {
                    showToast('Please enter a Member ID', 'error');
                    return;
                }
                
                // Show loading state
                const memberStatus = document.getElementById('memberStatus');
                memberStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying member...';
                memberStatus.className = 'member-status loading';
                
                // AJAX request to verify member
                fetch('verify_member.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `memberID=${encodeURIComponent(memberID)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Member is active
                        memberStatus.innerHTML = '<i class="fas fa-check-circle"></i> Active member verified';
                        memberStatus.className = 'member-status success';
                        
                        // Show member info section
                        document.getElementById('memberInfoSection').style.display = 'grid';
                        
                        // Fill member information
                        document.getElementById('firstname').value = data.member.firstname;
                        document.getElementById('middlename').value = data.member.middlename || '';
                        document.getElementById('lastname').value = data.member.lastname;
                        
                        // Enable purpose and other fields
                        document.getElementById('purpose').removeAttribute('disabled');
                        document.getElementById('amount').removeAttribute('disabled');
                        document.getElementById('interest').removeAttribute('disabled');
                        document.getElementById('period').removeAttribute('disabled');
                    } else {
                        // Member not found or not active
                        memberStatus.innerHTML = `<i class="fas fa-times-circle"></i> ${data.message}`;
                        memberStatus.className = 'member-status error';
                        
                        // Hide member info section
                        document.getElementById('memberInfoSection').style.display = 'none';
                    }
                })
                .catch(error => {
                    memberStatus.innerHTML = '<i class="fas fa-times-circle"></i> Error verifying member';
                    memberStatus.className = 'member-status error';
                    console.error('Error:', error);
                });
            });

            // Loan calculation functionality
            document.getElementById('calculateLoanBtn').addEventListener('click', function() {
                // Get input values
                const amount = parseFloat(document.getElementById('amount').value) || 0;
                const interestRate = parseFloat(document.getElementById('interest').value) || 0;
                const period = parseInt(document.getElementById('period').value) || 0;
                
                // Validate inputs
                if (amount <= 0 || interestRate <= 0 || period <= 0) {
                    showToast('Please enter valid values for amount, interest rate, and period', 'error');
                    return;
                }
                
                // Perform calculations
                const interestAmount = amount * (interestRate / 100);
                const totalAmount = amount + interestAmount;
                const monthlyAmortization = totalAmount / period;
                
                // Display results
                document.getElementById('displayAmount').textContent = '$' + amount.toFixed(2);
                document.getElementById('displayInterestAmount').textContent = '$' + interestAmount.toFixed(2);
                document.getElementById('displayTotalAmount').textContent = '$' + totalAmount.toFixed(2);
                document.getElementById('displayMonthlyAmortization').textContent = '$' + monthlyAmortization.toFixed(2);
                
                // Set hidden input values
                document.getElementById('totalAmount').value = totalAmount.toFixed(2);
                document.getElementById('monthlyAmortization').value = monthlyAmortization.toFixed(2);
                
                // Show the loan calculation section and submit button
                document.getElementById('loanCalculation').style.display = 'block';
                document.getElementById('submitLoanBtn').style.display = 'inline-block';
            });

            // Form submission validation
            document.getElementById('loanApplicationForm').addEventListener('submit', function(e) {
                const memberStatus = document.getElementById('memberStatus');
                const totalAmount = document.getElementById('totalAmount').value;
                
                // Check if member is verified
                if (!memberStatus.classList.contains('success')) {
                    e.preventDefault();
                    showToast('Please verify member ID first', 'error');
                    return;
                }
                
                // Check if loan has been calculated
                if (!totalAmount) {
                    e.preventDefault();
                    showToast('Please calculate loan details first', 'error');
                    return;
                }
                
                // Validate all required fields
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('error');
                    } else {
                        field.classList.remove('error');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    showToast('Please fill in all required fields', 'error');
                    return;
                }
                
                // If all validations pass, form will be submitted
            });
        </script>
    </body>
    </html>