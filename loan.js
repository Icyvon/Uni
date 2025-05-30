/**
 * Loan Form JavaScript Functions
 * Handles calculation and validation for loan applications
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const loanForm = document.getElementById('loanApplicationForm');
    const verifyMemberBtn = document.getElementById('verifyMemberBtn');
    const calculateLoanBtn = document.getElementById('calculateLoanBtn');
    const submitLoanBtn = document.getElementById('submitLoanBtn');
    
    // Input fields
    const memberIDInput = document.getElementById('memberID');
    const amountInput = document.getElementById('amount');
    const interestInput = document.getElementById('interest');
    const periodInput = document.getElementById('period');
    
    // Add event listeners for real-time calculation
    if (amountInput && interestInput && periodInput) {
        [amountInput, interestInput, periodInput].forEach(input => {
            input.addEventListener('input', updateCalculation);
        });
    }
    
    // Verify member button click
    if (verifyMemberBtn) {
        verifyMemberBtn.addEventListener('click', verifyMember);
    }
    
    // Calculate loan button click
    if (calculateLoanBtn) {
        calculateLoanBtn.addEventListener('click', calculateLoan);
    }
    
    // Form submission
    if (loanForm) {
        loanForm.addEventListener('submit', validateForm);
    }
    
    // Format currency on load
    formatCurrencyFields();
});

/**
 * Format amount fields as currency
 */
function formatCurrencyFields() {
    const amountInput = document.getElementById('amount');
    
    if (amountInput) {
        amountInput.addEventListener('blur', function() {
            // Format as currency when losing focus
            if (this.value) {
                const amount = parseFloat(this.value);
                if (!isNaN(amount)) {
                    // Don't update the input value, just for display in the UI
                    document.getElementById('displayAmount').textContent = '$' + amount.toFixed(2);
                }
            }
        });
    }
}

/**
 * Verify member ID
 */
function verifyMember() {
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
            
            // Focus on the purpose field
            document.getElementById('purpose').focus();
        } else {
            // Member not found or not active
            memberStatus.innerHTML = `<i class="fas fa-times-circle"></i> ${data.message}`;
            memberStatus.className = 'member-status error';
            
            // Hide member info section
            document.getElementById('memberInfoSection').style.display = 'none';
            
            // Reset form fields
            resetFormFields();
        }
    })
    .catch(error => {
        memberStatus.innerHTML = '<i class="fas fa-times-circle"></i> Error verifying member';
        memberStatus.className = 'member-status error';
        console.error('Error:', error);
        
        // Hide member info section
        document.getElementById('memberInfoSection').style.display = 'none';
        
        // Reset form fields
        resetFormFields();
    });
}

/**
 * Reset form fields after failed verification
 */
function resetFormFields() {
    const form = document.getElementById('loanApplicationForm');
    if (form) {
        // Reset all inputs except memberID
        const inputs = form.querySelectorAll('input:not(#memberID), textarea');
        inputs.forEach(input => {
            input.value = '';
            input.setAttribute('disabled', 'disabled');
        });
    }
    
    // Hide calculation section
    document.getElementById('loanCalculation').style.display = 'none';
    document.getElementById('submitLoanBtn').style.display = 'none';
}

/**
 * Calculate loan details
 */
function calculateLoan() {
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
}

/**
 * Update calculation based on input changes
 */
function updateCalculation() {
    // Don't auto-calculate unless all fields have values
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const interestRate = parseFloat(document.getElementById('interest').value) || 0;
    const period = parseInt(document.getElementById('period').value) || 0;
    
    if (amount > 0 && interestRate > 0 && period > 0) {
        // Perform calculations for display only
        const interestAmount = amount * (interestRate / 100);
        const totalAmount = amount + interestAmount;
        const monthlyAmortization = totalAmount / period;
        
        // Only update the display, not the hidden fields
        document.getElementById('displayAmount').textContent = '$' + amount.toFixed(2);
        document.getElementById('displayInterestAmount').textContent = '$' + interestAmount.toFixed(2);
        document.getElementById('displayTotalAmount').textContent = '$' + totalAmount.toFixed(2);
        document.getElementById('displayMonthlyAmortization').textContent = '$' + monthlyAmortization.toFixed(2);
    }
}

/**
 * Validate form before submission
 */
function validateForm(e) {
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
    
    // Additional validation for amount and period
    const amount = parseFloat(document.getElementById('amount').value);
    const period = parseInt(document.getElementById('period').value);
    
    if (amount < 100) {
        e.preventDefault();
        showToast('Loan amount must be at least $100', 'error');
        return;
    }
    
    if (period < 1 || period > 60) {
        e.preventDefault();
        showToast('Payment period must be between 1 and 60 months', 'error');
        return;
    }
    
    // If all validations pass, form will be submitted
    showToast('Submitting loan application...', 'info');
}