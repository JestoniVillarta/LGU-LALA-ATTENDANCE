<?php
include '../CONNECTION/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['start_time']) && isset($_POST['end_time'])) {
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    if (empty($start_time) || empty($end_time)) {
        echo "Start time and end time are required.";
        exit();
    }

    // Convert to 12-hour format with AM/PM
    $start_time_12hr = date("h:i A", strtotime($start_time));
    $end_time_12hr = date("h:i A", strtotime($end_time));

    // Check if start time is earlier than end time
    $start_time_object = new DateTime($start_time_12hr);
    $end_time_object = new DateTime($end_time_12hr);

    if ($start_time_object >= $end_time_object) {
        echo "Start time must be earlier than end time.";
        exit();
    }

    // Check if settings already exist
    $query = "SELECT * FROM attendance_settings_tbl";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Update existing settings
        $query = "UPDATE attendance_settings_tbl SET MORNING_TIME_IN = ?, MORNING_TIME_OUT = ? WHERE id = 2";
    } else {
        // Insert new settings
        $query = "INSERT INTO attendance_settings_tbl (MORNING_TIME_IN, MORNING_TIME_OUT) VALUES (?, ?)";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $start_time_12hr, $end_time_12hr);

    if ($stmt->execute()) {
        echo "Attendance time updated successfully!";
    } else {
        echo "Error updating attendance time!";
    }

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
    <h1>Set Attendance Time (Admin)</h1>
    <form action="" method="post">
        <label for="start_time">MORNING TIME IN:</label>
        <input type="time" id="start_time" name="start_time" required>
        <br>
        <label for="end_time">MORNING TIME OUT:</label>
        <input type="time" id="end_time" name="end_time" required>
        <br>
        <!-- <label for="end_time">AFTERNOON TIME IN:</label>
        <input type="time" id="end_time" name="end_time" required>
        <br>
        <label for="end_time">AFTERNOON TIME OUT:</label>
        <input type="time" id="end_time" name="end_time" >
        <br> -->
        <button type="submit">Set Time</button>
    </form>
</body>

</html>