
function openEditModal(studentId, firstName, lastName, gender, email, contact, address) {
    // Set values in the form
    document.getElementById('originalStudentId').value = studentId;
    document.getElementById('editStudentId').value = studentId;
    document.getElementById('editFirstName').value = firstName;
    document.getElementById('editLastName').value = lastName;
    document.getElementById('editGender').value = gender;
    document.getElementById('editEmail').value = email;
    document.getElementById('editContact').value = contact;
    document.getElementById('editAddress').value = address;
    
    // Display the modal using flex instead of block
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside the content
window.onclick = function(event) {
    let modal = document.getElementById('editModal');
    
    if (event.target === modal) {
        closeEditModal();
    }
};
