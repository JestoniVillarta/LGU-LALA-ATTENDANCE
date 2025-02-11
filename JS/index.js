
    document.addEventListener("DOMContentLoaded", function () {
        const now = new Date();
        const hours = now.getHours();
        const morningIn = document.querySelector("button[name='morning_in']");
        const morningOut = document.querySelector("button[name='morning_out']");
        const afternoonIn = document.querySelector("button[name='afternoon_in']");
        const afternoonOut = document.querySelector("button[name='afternoon_out']");
        
        // Define time ranges
        if (hours >= 6 && hours < 12) {
            morningIn.style.display = "block";
        }
        if (hours >= 12 && hours < 13) {
            morningOut.style.display = "block";
        }
        if (hours >= 13 && hours < 17) {
            afternoonIn.style.display = "block";
        }
        if (hours >= 17 && hours < 22) {
            afternoonOut.style.display = "block";
        }
    });

