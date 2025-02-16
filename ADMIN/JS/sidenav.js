
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".sidebar button");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            // Remove "active" class from all buttons
            buttons.forEach(btn => btn.classList.remove("active"));

            // Add "active" class to the clicked button
            this.classList.add("active");

            // Navigate to the corresponding page
            const pageUrl = this.getAttribute("data-url");
            if (pageUrl) {
                window.location.href = pageUrl;
            }
        });
    });

    // Highlight the active button based on the current page URL
    const currentPage = window.location.pathname.split("/").pop();
    buttons.forEach(button => {
        if (button.getAttribute("data-url") === currentPage) {
            button.classList.add("active");
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const logoutBtn = document.getElementById("logout");
    const logoutModal = document.getElementById("logoutModal");
    const confirmLogout = document.getElementById("confirmLogout");
    const cancelLogout = document.getElementById("cancelLogout");

    // Show modal when logout button is clicked
    logoutBtn.addEventListener("click", function () {
        logoutModal.style.display = "flex";
    });

    // Redirect to logout page if confirmed
    confirmLogout.addEventListener("click", function () {
        window.location.href = "../logout.php";
    });

    // Hide modal if canceled
    cancelLogout.addEventListener("click", function () {
        logoutModal.style.display = "none";
    });
});
