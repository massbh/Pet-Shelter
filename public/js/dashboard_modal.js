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

// Pet Details Modal
async function showPetDetails(petId) {
    const modal = document.getElementById('petDetailsModal');
    const content = document.getElementById('petDetailsContent');
    
    modal.style.display = 'block';
    content.innerHTML = '<div style="text-align: center; padding: 2rem;"><p>Loading pet details...</p></div>';
    
    try {
        const response = await fetch('/api/pets');
        if (!response.ok) throw new Error('Failed to fetch pets');
        
        const pets = await response.json();
        const pet = pets.find(p => p.id === petId);
        
        if (!pet) {
            content.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ff6b6b;"><p>Pet not found</p></div>';
            return;
        }
        
        content.innerHTML = `
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="text-align: center;">
                    <img src="${pet.image_url || '/images/default-pet.jpg'}" 
                         alt="${pet.name}" 
                         style="max-width: 100%; height: auto; border-radius: 12px; max-height: 300px; object-fit: cover;">
                </div>
                <div>
                    <h2 style="color: #FF6B00; margin-bottom: 1rem;">${pet.name}</h2>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                        <div><strong>Species:</strong> ${pet.species}</div>
                        <div><strong>Age:</strong> ${pet.age} years old</div>
                        <div><strong>Sex:</strong> ${pet.sex}</div>
                        <div>
                            <strong>Status:</strong> 
                            <span class="badge badge-${pet.status}">${pet.status.charAt(0).toUpperCase() + pet.status.slice(1)}</span>
                        </div>
                    </div>
                    ${pet.description ? `
                        <div style="margin-top: 1rem; padding: 1rem; background: #fff5e6; border-radius: 8px; border-left: 4px solid #FF6B00;">
                            <strong style="display: block; margin-bottom: 0.5rem;">About ${pet.name}:</strong>
                            <p style="margin: 0; line-height: 1.6;">${pet.description}</p>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
    } catch (error) {
        console.error('Error fetching pet details:', error);
        content.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ff6b6b;"><p>Error loading pet details. Please try again.</p></div>';
    }
}

function closePetDetailsModal() {
    document.getElementById('petDetailsModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const messageModal = document.getElementById('messageModal');
    const actionModal = document.getElementById('actionModal');
    const adminNotesModal = document.getElementById('adminNotesModal');
    const petDetailsModal = document.getElementById('petDetailsModal');
    
    if (event.target === messageModal) {
        closeMessageModal();
    }
    if (event.target === actionModal) {
        closeActionModal();
    }
    if (event.target === adminNotesModal) {
        closeAdminNotesModal();
    }
    if (event.target === petDetailsModal) {
        closePetDetailsModal();
    }
}

// Initialize lucide icons on page load
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
