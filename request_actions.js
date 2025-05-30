/**
 * Loan Actions JavaScript
 * Handles AJAX requests for loan approval and rejection
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get the approve and reject buttons
    const approveBtn = document.getElementById('approve-btn');
    const rejectBtn = document.getElementById('reject-btn');
    
    // Add event listener for approve button
    if (approveBtn) {
        approveBtn.addEventListener('click', function() {
            const loanId = this.getAttribute('data-loan-id');
            if (loanId) {
                approveOrRejectLoan(loanId, 'approve');
            }
        });
    }
    
    // Add event listener for reject button
    if (rejectBtn) {
        rejectBtn.addEventListener('click', function() {
            const loanId = this.getAttribute('data-loan-id');
            if (loanId) {
                approveOrRejectLoan(loanId, 'reject');
            }
        });
    }
});

/**
 * Function to handle loan approval or rejection
 * @param {string} loanId - The ID of the loan application
 * @param {string} action - Either 'approve' or 'reject'
 */
function approveOrRejectLoan(loanId, action) {
    // Show confirmation dialog
    const confirmMessage = action === 'approve' 
        ? 'Are you sure you want to approve this loan application?' 
        : 'Are you sure you want to reject this loan application?';
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    // Create form data
    const formData = new FormData();
    formData.append('loan_id', loanId);
    
    // Determine the endpoint
    const endpoint = action === 'approve' ? 'approve_request.php' : 'reject_request.php';
    
    // Show loading state
    const approveBtn = document.getElementById('approve-btn');
    const rejectBtn = document.getElementById('reject-btn');
    
    // Disable buttons during processing
    if (approveBtn) approveBtn.disabled = true;
    if (rejectBtn) rejectBtn.disabled = true;
    
    // Show processing toast
    showToast(`Processing ${action} action...`, 'info');
    
    // Send AJAX request
    fetch(endpoint, {
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
        // Handle the response
        if (data.success) {
            // Show success message
            showToast(data.message, 'success');
            
            // Update UI to reflect the new status
            updateStatusUI(data.status);
            
            // Hide the action buttons
            hideActionButtons();
        } else {
            // Show error message
            showToast(data.message || 'An error occurred', 'error');
            
            // Re-enable buttons
            if (approveBtn) approveBtn.disabled = false;
            if (rejectBtn) rejectBtn.disabled = false;
        }
    })
    .catch(error => {
        // Show error message
        showToast('Error: ' + error.message, 'error');
        
        // Re-enable buttons
        if (approveBtn) approveBtn.disabled = false;
        if (rejectBtn) rejectBtn.disabled = false;
    });
}

/**
 * Updates the status display in the UI
 * @param {string} status - The new status
 */
function updateStatusUI(status) {
    const statusValue = document.getElementById('status-value');
    if (statusValue) {
        statusValue.innerHTML = `
            <span class="status-badge status-${status.toLowerCase()}">
                ${status.charAt(0).toUpperCase() + status.slice(1)}
            </span>
        `;
    }
}

/**
 * Hides the action buttons after successful action
 */
function hideActionButtons() {
    const approveBtn = document.getElementById('approve-btn');
    const rejectBtn = document.getElementById('reject-btn');
    
    if (approveBtn) approveBtn.style.display = 'none';
    if (rejectBtn) rejectBtn.style.display = 'none';
}
