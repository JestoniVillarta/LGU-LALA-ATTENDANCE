document.addEventListener("DOMContentLoaded", function () {
    // Sidebar Navigation Active Class
    const buttons = document.querySelectorAll(".sidebar button");
    const currentPage = window.location.pathname.split("/").pop();

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            buttons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            const pageUrl = this.getAttribute("data-url");
            if (pageUrl) {
                window.location.href = pageUrl;
            }
        });

        // Set active button based on URL
        if (button.getAttribute("data-url") === currentPage) {
            button.classList.add("active");
        }
    });

    // Sidebar Toggle Functionality
    const sidebar = document.querySelector(".sidebar");
    const toggleButton = document.getElementById("toggleSidebar");

    if (toggleButton) {
        toggleButton.addEventListener("click", function () {
            sidebar.classList.toggle("collapsed");
        });
    }

    // Logout Modal
    const logoutBtn = document.getElementById("logout");
    const logoutModal = document.getElementById("logoutModal");
    const confirmLogout = document.getElementById("confirmLogout");
    const cancelLogout = document.getElementById("cancelLogout");

    if (logoutBtn) {
        logoutBtn.addEventListener("click", function () {
            logoutModal.style.display = "flex";
        });
    }

    if (confirmLogout) {
        confirmLogout.addEventListener("click", function () {
            window.location.href = "../logout.php";
        });
    }

    if (cancelLogout) {
        cancelLogout.addEventListener("click", function () {
            logoutModal.style.display = "none";
        });
    }
});
