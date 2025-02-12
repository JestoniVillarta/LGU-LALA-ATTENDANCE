function toggleOptions(row) {
    let options = row.querySelector(".options");
    if (options.style.display === "none" || options.style.display === "") {
        options.style.display = "block";
        row.style.height = "80px"; // Expand row height when options are shown
    } else {
        options.style.display = "none";
        row.style.height = "auto"; // Reset row height when hidden
    }
}
