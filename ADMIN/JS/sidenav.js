$(document).ready(function () {
    console.log("jQuery loaded!"); // Debugging: Check if jQuery is loaded

    // Toggle submenu when clicking the arrow ONLY
    $(".menu-link").click(function (e) {
        let submenu = $(this).next(".submenu");

        if (submenu.length) {
            if (!$(e.target).hasClass("arrow")) {
                // Allow redirection when clicking "Students" text
                return; 
            }
            e.preventDefault(); // Prevent default only when clicking the arrow
            submenu.slideToggle();
            $(this).find(".arrow").toggleClass("ph-caret-down ph-caret-up");
        }
    });

    // Toggle Sidebar
    $(".menu-btn").click(function () {
        $(".sidebar").toggleClass("active");
        if ($(".sidebar").hasClass("active")) {
            $(".submenu").slideUp(); // Hide submenu when sidebar is collapsed
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    // Get current page URL
    let currentUrl = window.location.pathname.split("/").pop();

    // Select all sidebar links
    let menuLinks = document.querySelectorAll(".nav ul li a");

    menuLinks.forEach(link => {
        let linkHref = link.getAttribute("href");

        // Check if the link matches the current URL
        if (linkHref === currentUrl) {
            link.classList.add("active");
        }
    });
});

