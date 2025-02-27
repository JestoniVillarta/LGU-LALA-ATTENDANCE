
    // Toggle Sidebar
    $(".menu-btn").click(function () {
        $(".sidebar").toggleClass("active");
        
        if (!$(".sidebar").hasClass("active")) {
            $(".submenu").slideUp(); // Hide submenus when sidebar is collapsed
        }
    });

    // Keep submenu open if an active link is inside
    $(".submenu a").each(function () {
        if ($(this).hasClass("active")) {
            $(this).closest(".submenu").show(); // Keep submenu open
            $(this).closest(".submenu").prev(".menu-link").find(".arrow")
                .removeClass("fa-angle-down").addClass("fa-angle-up");
        }
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






