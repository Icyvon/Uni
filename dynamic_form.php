<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Banking System</title>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Smoother transaction with PAY360</h1>
            <p>Manage your accounts with ease</p>
        </header>

            <div class="container">
        <!-- Back Button -->
        <div class="back-button-container">
            <a href="Admin.php" class="back-button">
                <i class="fas fa-arrow-left"></i>Back to Dashboard
            </a>
        </div>

        <div class="dashboard">
            <!-- Deposit Section -->
            <div class="card">
                <h2>Make a Deposit</h2>
                <form id="depositForm">
                    <div class="form-group">
                        <label for="depositAccountId">Account ID</label>
                        <input type="text" id="depositAccountId" placeholder="Enter your Account ID" required>
                        <div class="error-message" id="depositIdError"></div>
                    </div>
                    
                    <div class="form-group">
                        <button type="button" id="checkDepositAccount">Check Account</button>
                    </div>

                    <div id="depositDetails" class="hide">
                        <div class="account-info">
                            <h3 id="depositAccountType"></h3>
                            <p>Account ID: <span id="displayDepositId"></span></p>
                            <p>Account Holder: <span id="depositAccountHolder"></span></p>
                            <div class="balance">Current Balance: $<span id="depositCurrentBalance"></span></div>
                        </div>

                        <div class="form-group">
                            <label for="depositAmount">Deposit Amount ($)</label>
                            <input type="number" id="depositAmount" min="1" step="0.01" placeholder="Enter deposit amount" required>
                            <div class="error-message" id="depositAmountError"></div>
                        </div>

                        <div class="form-group">
                            <button type="button" id="submitDeposit">Complete Deposit</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Withdrawal Section -->
            <div class="card">
                <h2>Make a Withdrawal</h2>
                <form id="withdrawalForm">
                    <div class="form-group">
                        <label for="withdrawalAccountId">Account ID</label>
                        <input type="text" id="withdrawalAccountId" placeholder="Enter your Account ID" required>
                        <div class="error-message" id="withdrawalIdError"></div>
                    </div>
                    
                    <div class="form-group">
                        <button type="button" id="checkWithdrawalAccount">Check Account</button>
                    </div>

                    <div id="withdrawalDetails" class="hide">
                        <div class="account-info">
                            <h3 id="withdrawalAccountType"></h3>
                            <p>Account ID: <span id="displayWithdrawalId"></span></p>
                            <p>Account Holder: <span id="withdrawalAccountHolder"></span></p>
                            <div class="balance">Current Balance: $<span id="withdrawalCurrentBalance"></span></div>
                        </div>

                        <div id="withdrawalRestrictions" class="restriction-notice hide">
                            <p id="restrictionMessage"></p>
                        </div>

                        <div class="form-group">
                            <label for="withdrawalAmount">Withdrawal Amount ($)</label>
                            <input type="number" id="withdrawalAmount" min="1" step="0.01" placeholder="Enter withdrawal amount" required>
                            <div class="error-message" id="withdrawalAmountError"></div>
                        </div>

                        <div id="penaltySection" class="hide">
                            <div class="info-box">
                                <p>Early withdrawal will incur a penalty of <span id="penaltyAmount">0%</span>.</p>
                                <p>Amount after penalty: $<span id="amountAfterPenalty">0.00</span></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" id="submitWithdrawal">Complete Withdrawal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transaction History Section -->
        <div class="card action-history" id="transactionHistory">
            <h2>Recent Transactions</h2>
            <div id="transactionsList"></div>
        </div>

        <!-- Available Accounts (For Demo Purposes) -->
        <div class="card" id="availableAccounts">
            <h2>Accounts</h2>
            <p class="info-box"></p>
            <div class="accounts-list" id="accountsList"></div>
        </div>
    </div>

    <script>
        // DOM elements - Deposit
        const depositForm = document.getElementById('depositForm');
        const depositAccountId = document.getElementById('depositAccountId');
        const checkDepositAccount = document.getElementById('checkDepositAccount');
        const depositDetails = document.getElementById('depositDetails');
        const depositAccountType = document.getElementById('depositAccountType');
        const displayDepositId = document.getElementById('displayDepositId');
        const depositAccountHolder = document.getElementById('depositAccountHolder');
        const depositCurrentBalance = document.getElementById('depositCurrentBalance');
        const depositAmount = document.getElementById('depositAmount');
        const submitDeposit = document.getElementById('submitDeposit');
        const depositIdError = document.getElementById('depositIdError');
        const depositAmountError = document.getElementById('depositAmountError');

        // DOM elements - Withdrawal
        const withdrawalForm = document.getElementById('withdrawalForm');
        const withdrawalAccountId = document.getElementById('withdrawalAccountId');
        const checkWithdrawalAccount = document.getElementById('checkWithdrawalAccount');
        const withdrawalDetails = document.getElementById('withdrawalDetails');
        const withdrawalAccountType = document.getElementById('withdrawalAccountType');
        const displayWithdrawalId = document.getElementById('displayWithdrawalId');
        const withdrawalAccountHolder = document.getElementById('withdrawalAccountHolder');
        const withdrawalCurrentBalance = document.getElementById('withdrawalCurrentBalance');
        const withdrawalAmount = document.getElementById('withdrawalAmount');
        const submitWithdrawal = document.getElementById('submitWithdrawal');
        const withdrawalIdError = document.getElementById('withdrawalIdError');
        const withdrawalAmountError = document.getElementById('withdrawalAmountError');
        const withdrawalRestrictions = document.getElementById('withdrawalRestrictions');
        const restrictionMessage = document.getElementById('restrictionMessage');
        const penaltySection = document.getElementById('penaltySection');
        const penaltyAmount = document.getElementById('penaltyAmount');
        const amountAfterPenalty = document.getElementById('amountAfterPenalty');

        // DOM elements - Transaction History
        const transactionsList = document.getElementById('transactionsList');
        const accountsList = document.getElementById('accountsList');

        // Function to fetch available accounts from the database
        async function fetchAvailableAccounts() {
            try {
                const response = await fetch('get_all_members.php', {
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' }
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                if (data.success) {
                    return data.accounts;
                } else {
                    console.error('Error fetching accounts:', data.message);
                    return [];
                }
            } catch (error) {
                console.error('Network error fetching accounts:', error.message);
                return [];
            }
        }

        // Function to render available accounts
        async function renderAvailableAccounts() {
            const accounts = await fetchAvailableAccounts();
            accountsList.innerHTML = '';
            accounts.forEach(account => {
                const accountCard = document.createElement('div');
                accountCard.className = 'account-card';
                
                let typeClass = '';
                switch(account.type_name) {
                    case 'Savings': typeClass = 'savings'; break;
                    case 'Time Deposit': typeClass = 'time'; break;
                    case 'Checking': typeClass = 'checking'; break;
                    case 'Emergency Fund': typeClass = 'emergency'; break;
                    case 'Youth Savings': typeClass = 'youth'; break;
                }
                
                accountCard.innerHTML = `
                    <h3>${account.Firstname} ${account.Lastname}</h3>
                    <p>ID: ${account.AccountID}</p>
                    <p>Balance: $${parseFloat(account.Balance).toFixed(2)}</p>
                    <div class="pills">
                        <span class="pill ${typeClass}">${account.type_name}</span>
                        ${account.restrictions ? '<span class="pill restricted">Restricted</span>' : ''}
                    </div>
                `;
                accountsList.appendChild(accountCard);
            });
        }

        // Function to fetch account by ID
        async function findAccount(accountId) {
            if (!accountId || isNaN(accountId) || parseInt(accountId) <= 0) {
                console.error('Invalid accountId:', accountId);
                return null;
            }
            try {
                const payload = { accountId: parseInt(accountId) };
                console.log('Sending payload:', JSON.stringify(payload));
                const response = await fetch('get_member_data.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                console.log('Response data:', data);
                if (data.success) {
                    return data.account;
                } else {
                    console.error('Server error:', data.message);
                    return null;
                }
            } catch (error) {
                console.error('Error fetching account:', error.message);
                return null;
            }
        }

        // Function to fetch recent transactions
        async function fetchRecentTransactions(accountId) {
            if (!accountId) return [];
            try {
                const response = await fetch('get_activities.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ accountId: parseInt(accountId) })
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                if (data.success) {
                    return data.transactions;
                } else {
                    console.error('Error fetching transactions:', data.message);
                    return [];
                }
            } catch (error) {
                console.error('Error fetching transactions:', error.message);
                return [];
            }
        }

        // Function to update transaction history
        async function updateTransactionHistory(accountId) {
            transactionsList.innerHTML = '';
            
            if (!accountId || isNaN(accountId) || parseInt(accountId) <= 0) {
                transactionsList.innerHTML = '<p>Please select an account to view transactions.</p>';
                return;
            }
            
            const transactions = await fetchRecentTransactions(accountId);
            
            if (!transactions || transactions.length === 0) {
                transactionsList.innerHTML = '<p>No transactions found for this account.</p>';
                return;
            }
            
            transactions.forEach(transaction => {
                const transactionItem = document.createElement('div');
                transactionItem.className = `action-item ${transaction.transaction_type.toLowerCase()}`;
                
                transactionItem.innerHTML = `
                    <p><strong>${transaction.transaction_type}</strong> - ${new Date(transaction.transaction_date).toLocaleString()}</p>
                    <p>Account: ${transaction.AccountID} (${transaction.type_name || 'Unknown'})</p>
                    <p>Amount: $${parseFloat(transaction.amount).toFixed(2)}</p>
                    ${transaction.penalty_amount ? `<p>Penalty: $${parseFloat(transaction.penalty_amount).toFixed(2)}</p>` : ''}
                    <p>Balance after: $${parseFloat(transaction.balance_after).toFixed(2)}</p>
                `;
                
                transactionsList.appendChild(transactionItem);
            });
        }

        // Event Listener for Deposit Account Check
        checkDepositAccount.addEventListener('click', async () => {
            const accountId = depositAccountId.value.trim();
            depositIdError.textContent = '';
            
            if (!accountId) {
                depositIdError.textContent = 'Account ID cannot be empty';
                return;
            }
            if (isNaN(accountId) || parseInt(accountId) <= 0) {
                depositIdError.textContent = 'Please enter a valid positive Account ID';
                return;
            }
            
            const account = await findAccount(accountId);
            
            if (!account) {
                depositIdError.textContent = 'Account not found';
                return;
            }
            
            // Check if account type allows deposits
            if (!account.allows_deposits) {
                depositIdError.textContent = 'This account type does not allow deposits';
                return;
            }
            
            // Populate account details
            displayDepositId.textContent = account.AccountID;
            depositAccountType.textContent = account.type_name;
            depositAccountHolder.textContent = `${account.Firstname} ${account.Lastname}`;
            depositCurrentBalance.textContent = parseFloat(account.Balance).toFixed(2);
            
            // Show deposit details section
            depositDetails.classList.remove('hide');
            // Update transaction history
            await updateTransactionHistory(accountId);
        });

        // Event Listener for Withdrawal Account Check
        checkWithdrawalAccount.addEventListener('click', async () => {
            const accountId = withdrawalAccountId.value.trim();
            withdrawalIdError.textContent = '';
            
            if (!accountId) {
                withdrawalIdError.textContent = 'Account ID cannot be empty';
                return;
            }
            if (isNaN(accountId) || parseInt(accountId) <= 0) {
                withdrawalIdError.textContent = 'Please enter a valid positive Account ID';
                return;
            }
            
            const account = await findAccount(accountId);
            
            if (!account) {
                withdrawalIdError.textContent = 'Account not found';
                return;
            }
            
            // Check if account type allows withdrawals
            if (!account.allows_withdrawals) {
                withdrawalIdError.textContent = 'This account type does not allow withdrawals';
                return;
            }
            
            // Populate account details
            displayWithdrawalId.textContent = account.AccountID;
            withdrawalAccountType.textContent = account.type_name;
            withdrawalAccountHolder.textContent = `${account.Firstname} ${account.Lastname}`;
            withdrawalCurrentBalance.textContent = parseFloat(account.Balance).toFixed(2);
            
            // Check for restrictions
            withdrawalRestrictions.classList.add('hide');
            penaltySection.classList.add('hide');
            
            if (account.restrictions) {
                withdrawalRestrictions.classList.remove('hide');
                restrictionMessage.textContent = account.restrictions.restriction_description || 'This account has restrictions.';
                
                // Show penalty section for restricted accounts
                penaltySection.classList.remove('hide');
                penaltyAmount.textContent = `${(account.restrictions.early_withdrawal_penalty * 100).toFixed(2)}%`;
                
                // Remove existing listener to prevent duplicates
                withdrawalAmount.removeEventListener('input', updatePenalty);
                // Add new listener
                withdrawalAmount.addEventListener('input', updatePenalty);
                
                function updatePenalty() {
                    const amount = parseFloat(withdrawalAmount.value) || 0;
                    const penalty = amount * account.restrictions.early_withdrawal_penalty;
                    amountAfterPenalty.textContent = (amount - penalty).toFixed(2);
                }
            }
            
            // Show withdrawal details section
            withdrawalDetails.classList.remove('hide');
            // Update transaction history
            await updateTransactionHistory(accountId);
        });

        // Event Listener for Deposit Submission
        submitDeposit.addEventListener('click', async () => {
            const accountId = depositAccountId.value.trim();
            const amount = parseFloat(depositAmount.value);
            depositAmountError.textContent = '';
            
            if (isNaN(amount) || amount <= 0) {
                depositAmountError.textContent = 'Please enter a valid deposit amount';
                return;
            }
            
            // Show loading state
            submitDeposit.disabled = true;
            submitDeposit.textContent = 'Processing...';
            
            try {
                const response = await fetch('submit_entry.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ accountId: parseInt(accountId), amount })
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const result = await response.json();
                
                if (result.success) {
                    // Update UI
                    depositCurrentBalance.textContent = parseFloat(result.new_balance).toFixed(2);
                    depositAmount.value = '';
                    
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.className = 'success-message';
                    successMessage.textContent = result.message;
                    depositForm.appendChild(successMessage);
                    
                    // Remove success message after 3 seconds
                    setTimeout(() => {
                        successMessage.remove();
                    }, 3000);
                    
                    // Update transaction history and account list
                    await updateTransactionHistory(accountId);
                    await renderAvailableAccounts();
                } else {
                    depositAmountError.textContent = result.message;
                }
            } catch (error) {
                depositAmountError.textContent = 'Error processing deposit: ' + error.message;
                console.error('Deposit error:', error);
            } finally {
                // Reset button state
                submitDeposit.disabled = false;
                submitDeposit.textContent = 'Complete Deposit';
            }
        });

        // Event Listener for Withdrawal Submission
        submitWithdrawal.addEventListener('click', async () => {
            const accountId = withdrawalAccountId.value.trim();
            const amount = parseFloat(withdrawalAmount.value);
            withdrawalAmountError.textContent = '';
            
            if (isNaN(amount) || amount <= 0) {
                withdrawalAmountError.textContent = 'Please enter a valid withdrawal amount';
                return;
            }
            
            // Show loading state
            submitWithdrawal.disabled = true;
            submitWithdrawal.textContent = 'Processing...';
            
            try {
                const response = await fetch('remove_entry.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ accountId: parseInt(accountId), amount })
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const result = await response.json();
                
                if (result.success) {
                    // Update UI
                    withdrawalCurrentBalance.textContent = parseFloat(result.new_balance).toFixed(2);
                    withdrawalAmount.value = '';
                    
                    // Show success message
                    const successMessage = document.createElement('div');
                    successMessage.className = 'success-message';
                    successMessage.textContent = result.penalty 
                        ? `${result.message} (Penalty: $${parseFloat(result.penalty).toFixed(2)})`
                        : result.message;
                    withdrawalForm.appendChild(successMessage);
                    
                    // Remove success message after 3 seconds
                    setTimeout(() => {
                        successMessage.remove();
                    }, 3000);
                    
                    // Update transaction history and account list
                    await updateTransactionHistory(accountId);
                    await renderAvailableAccounts();
                } else {
                    withdrawalAmountError.textContent = result.message;
                }
            } catch (error) {
                withdrawalAmountError.textContent = 'Error processing withdrawal: ' + error.message;
                console.error('Withdrawal error:', error);
            } finally {
                // Reset button state
                submitWithdrawal.disabled = false;
                submitWithdrawal.textContent = 'Complete Withdrawal';
            }
        });

        // Initialize the UI
        renderAvailableAccounts();
        updateTransactionHistory(null);
    </script>
</body>
</html>