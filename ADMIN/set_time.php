<?php
include '../CONNECTION/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Validate that the times are not empty and properly formatted
    if (empty($start_time) || empty($end_time)) {
        echo "Start time and end time are required.";
        exit();
    }

    // Check if settings already exist
    $query = "SELECT * FROM attendance_settings_tbl";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Update existing settings
        $query = "UPDATE attendance_settings_tbl SET START_TIME = ?, END_TIME = ? WHERE id = 1"; // Assuming 'id' is the unique identifier for the settings
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $start_time, $end_time);
    } else {
        // Insert new settings
        $query = "INSERT INTO attendance_settings_tbl (START_TIME, END_TIME) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $start_time, $end_time);
    }

    if ($stmt->execute()) {
        echo "Attendance time updated successfully!";
    } else {
        echo "Error updating attendance time!";
    }

    // Close the prepared statement
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Set Attendance Time</title>
</head>
<body>
    <h1>Set Attendance Time</h1>
    <form action="" method="post">
        <label for="start_time">Start Time:</label>
        <input type="time" id="start_time" name="start_time" required>
        <br>
        <label for="end_time">End Time:</label>
        <input type="time" id="end_time" name="end_time" required>
        <br>
        <button type="submit">Set Time</button>
    </form>
</body>
</html>
