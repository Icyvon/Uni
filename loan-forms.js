/**
 * Loan Application Form JavaScript
 * Handles member verification and loan calculations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const verifyMemberBtn = document.getElementById('verifyMemberBtn');
    const calculateBtn = document.getElementById('calculateBtn');
    const submitBtn = document.getElementById('submitBtn');
    const memberDetailsSection = document.querySelector('.member-details-section');
    
    // Add event listener for verify member button
    if (verifyMemberBtn) {
        verifyMemberBtn.addEventListener('click', verifyMember);
    }
    
    // Add event listener for calculate button
    if (calculateBtn) {
        calculateBtn.addEventListener('click', calculateLoanDetails);
    }
    
    // Add event listener for amount, interest and period to enable/disable calculation button
    const amountInput = document.getElementById('amount');
    const interestInput = document.getElementById('interest');
    const periodInput = document.getElementById('period');
    
    if (amountInput && interestInput && periodInput) {
        [amountInput, interestInput, periodInput].forEach(input => {
            input.addEventListener('input', function() {
                // Enable calculate button only if all three fields have values
                if (amountInput.value && interestInput.value && periodInput.value) {
                    calculateBtn.disabled = false;
                } else {
                    calculateBtn.disabled = true;
                    submitBtn.disabled = true;
                }
                
                // Clear calculated fields when inputs change
                document.getElementById('totalAmount').value = '';
                document.getElementById('monthlyAmortization').value = '';
            });
        });
    }
    
    // Add event listener for the form submission
    const loanForm = document.getElementById('loanApplicationForm');
    if (loanForm) {
        loanForm.addEventListener('submit', function(e) {
            // Final validation before submission
            if (!validateForm()) {
                e.preventDefault();
                showToast('Please complete all required fields and verify calculations', 'error');
            }
        });
    }
});

/**
 * Function to verify if the member exists and is active
 */
function verifyMember() {
    const memberID = document.getElementById('memberID').value.trim();
    const memberIDFeedback = document.getElementById('memberIDFeedback');
    const memberDetailsSection = document.querySelector('.member-details-section');
    
    if (!memberID) {
        memberIDFeedback.textContent = 'Please enter a Member ID';
        memberIDFeedback.className = 'feedback-text error';
        return;
    }
    
    // Show loading state
    memberIDFeedback.textContent = 'Verifying member...';
    memberIDFeedback.className = 'feedback-text info';
    
    // Create form data
    const formData = new FormData();
    formData.append('member_id', memberID);
    
    // Send AJAX request to verify member
    fetch('verify_member.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Member is active, populate the form fields
            memberIDFeedback.textContent = 'Member verified successfully';
            memberIDFeedback.className = 'feedback-text success';
            
            // Show member details section
            memberDetailsSection.style.display = 'block';
            
            // Populate member information
            document.getElementById('firstname').value = data.member.firstname;
            document.getElementById('middlename').value = data.member.middlename || '';
            document.getElementById('lastname').value = data.member.lastname;
            
            // Focus on the purpose field
            document.getElementById('purpose').focus();
        } else {
            // Member is not active or doesn't exist
            memberIDFeedback.textContent = data.message || 'Invalid Member ID';
            memberIDFeedback.className = 'feedback-text error';
            
            // Hide member details section
            memberDetailsSection.style.display = 'none';
        }
    })
    .catch(error => {
        // Show error message
        memberIDFeedback.textContent = 'Error: ' + error.message;
        memberIDFeedback.className = 'feedback-text error';
        
        // Hide member details section
        memberDetailsSection.style.display = 'none';
    });
}

/**
 * Function to calculate loan details (total amount and monthly amortization)
 */
function calculateLoanDetails() {
    // Get input values
    const amount = parseFloat(document.getElementById('amount').value);
    const interestRate = parseFloat(document.getElementById('interest').value) / 100; // Convert to decimal
    const period = parseInt(document.getElementById('period').value);
    
    // Validate inputs
    if (isNaN(amount) || amount <= 0) {
        showToast('Please enter a valid loan amount', 'error');
        return;
    }
    
    if (isNaN(interestRate) || interestRate < 0) {
        showToast('Please enter a valid interest rate', 'error');
        return;
    }
    
    if (isNaN(period) || period <= 0) {
        showToast('Please enter a valid payment period', 'error');
        return;
    }
    
    // Calculate total amount and monthly amortization
    const totalAmount = amount + (amount * interestRate);
    const monthlyAmortization = totalAmount / period;
    
    // Display results with proper formatting
    document.getElementById('totalAmount').value = totalAmount.toFixed(2);
    document.getElementById('monthlyAmortization').value = monthlyAmortization.toFixed(2);
    
    // Enable submit button
    document.getElementById('submitBtn').disabled = false;
    
    // Show toast notification
    showToast('Loan details calculated successfully', 'success');
}

/**
 * Function to validate the form before submission
 * @returns {boolean} True if form is valid, false otherwise
 */
function validateForm() {
    // Check if member is verified
    const memberID = document.getElementById('memberID').value.trim();
    const memberIDFeedback = document.getElementById('memberIDFeedback');
    
    if (!memberID || memberIDFeedback.className.includes('error')) {
        return false;
    }
    
    // Check if required fields are filled
    const requiredFields = [
        'purpose', 'amount', 'interest', 'period', 'totalAmount', 'monthlyAmortization'
    ];
    
    for (const field of requiredFields) {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            return false;
        }
    }
    
    return true;
}

/**
 * Function to show toast notifications
 * @param {string} message - The message to display
 * @param {string} type - The type of toast (info, success, error)
 */
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