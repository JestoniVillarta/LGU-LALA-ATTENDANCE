
window.onload = function() {
    if (sessionStorage.getItem("scrollPosition") !== null) {
        window.scrollTo(0, sessionStorage.getItem("scrollPosition"));
        sessionStorage.removeItem("scrollPosition"); // Clear after use
    }
};

document.querySelector("form").addEventListener("submit", function() {
    sessionStorage.setItem("scrollPosition", window.scrollY);
});
