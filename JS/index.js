// Function to display the success modal
function showSuccessModal(form) {
    var modal = document.getElementById("successModal");
    var span = document.getElementsByClassName("close")[0];
    var modalMessage = document.getElementById("modalMessage");

    // Set the modal message without the total duty time
    modalMessage.textContent = "✅ Attendance recorded successfully!";

    // Display the modal
    modal.style.display = "block";

    // Close the modal when the user clicks on the 'x'
    span.onclick = function () {
        modal.style.display = "none";
    };

    // Close the modal when the user clicks outside of the modal
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    // Submit the form after showing the modal (e.g., 2 seconds delay)
    setTimeout(function () {
        form.submit();
    }, 1000);
}

// Add event listener to the form submit event
document.getElementById("attendanceForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent immediate submission

    // Show the modal and then submit the form
    showSuccessModal(this);
});
