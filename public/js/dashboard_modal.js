// Dashboard Modal Functions

// Message Modal
function showMessage(message) {
    document.getElementById('messageText').textContent = message;
    document.getElementById('messageModal').style.display = 'block';
    setTimeout(() => lucide.createIcons(), 0);
}

function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
}

// Admin Notes Modal
function showAdminNotes(notes) {
    document.getElementById('adminNotesText').textContent = notes;
    document.getElementById('adminNotesModal').style.display = 'block';
    setTimeout(() => lucide.createIcons(), 0);
}

function closeAdminNotesModal() {
    document.getElementById('adminNotesModal').style.display = 'none';
}

// Action Confirmation Modal
function openModal(requestId, status, userName, petName) {
    const modal = document.getElementById('actionModal');
    const form = document.getElementById('actionForm');
    const title = document.getElementById('actionModalTitle');
    const confirmText = document.getElementById('actionConfirmText');
    const submitBtn = document.getElementById('actionSubmitBtn');
    const statusInput = document.getElementById('actionStatus');
    
    // Set form action URL
    form.action = `/admin/adoption-requests/${requestId}/status`;
    
    // Set status value
    statusInput.value = status;
    
    // Update modal content based on action
    if (status === 'approved') {
        title.innerHTML = '<i data-lucide="check-circle"></i> Approve Request';
        confirmText.textContent = `Are you sure you want to APPROVE ${userName}'s adoption request for ${petName}? This will mark the pet as adopted.`;
        submitBtn.className = 'btn btn-success';
        submitBtn.textContent = 'Approve';
    } else {
        title.innerHTML = '<i data-lucide="x-circle"></i> Reject Request';
        confirmText.textContent = `Are you sure you want to REJECT ${userName}'s adoption request for ${petName}?`;
        submitBtn.className = 'btn btn-danger';
        submitBtn.textContent = 'Reject';
    }
    
    // Clear previous notes
    document.getElementById('admin_notes').value = '';
    
    // Show modal
    modal.style.display = 'block';
    setTimeout(() => lucide.createIcons(), 0);
}

function closeActionModal() {
    document.getElementById('actionModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const messageModal = document.getElementById('messageModal');
    const actionModal = document.getElementById('actionModal');
    const adminNotesModal = document.getElementById('adminNotesModal');
    
    if (event.target === messageModal) {
        closeMessageModal();
    }
    if (event.target === actionModal) {
        closeActionModal();
    }
    if (event.target === adminNotesModal) {
        closeAdminNotesModal();
    }
}

// Initialize lucide icons on page load
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
