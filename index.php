<?php
include 'CONNECTION/connection.php';

// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

$current_time = new DateTime(); // Current time with the default timezone set to 'Asia/Manila'

// Fetch attendance settings from the database
$query = "SELECT START_TIME, END_TIME FROM attendance_settings_tbl LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $attendance_time = $result->fetch_assoc();

    // Convert stored times from the database (assuming they are stored in 24-hour format)
    $start_time = DateTime::createFromFormat('H:i:s', $attendance_time['START_TIME'], new DateTimeZone('Asia/Manila'));
    $end_time = DateTime::createFromFormat('H:i:s', $attendance_time['END_TIME'], new DateTimeZone('Asia/Manila'));

    // Set the date for the start and end time to match today's date
    $start_time->setDate($current_time->format('Y'), $current_time->format('m'), $current_time->format('d'));
    $end_time->setDate($current_time->format('Y'), $current_time->format('m'), $current_time->format('d'));
} else {
    echo "Error fetching attendance settings.";
    exit();
}

// Check if the current time exceeds the attendance window (END_TIME)
$attendance_window_exceeded = $current_time > $end_time;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id'])) {
    // Sanitize input
    $employee_id = filter_input(INPUT_POST, 'employee_id', FILTER_SANITIZE_STRING);

    if (empty($employee_id)) {
        echo "Employee ID cannot be empty.";
        exit();
    }

    // Get current time in 24-hour format
    $formatted_time_in = $current_time->format('Y-m-d H:i:s'); // Store formatted time in 24-hour format

    // Fetch Employee Details
    $query = "SELECT NAME, GENDER FROM employee_tbl WHERE EMPLOYEE_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $name = $employee['NAME'];
        $gender = $employee['GENDER'];

        // Determine Status based on current time and the attendance window
        if ($attendance_window_exceeded) {
            $status = 'Closed'; // Attendance window closed
        } elseif ($current_time < $start_time) {
            $status = 'Early';
        } elseif ($current_time >= $start_time && $current_time <= $end_time) {
            $status = 'On Time';
        }

        // Insert Attendance Record
        $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER, TIME_IN, STATUS) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $employee_id, $name, $gender, $formatted_time_in, $status);

        if ($stmt->execute()) {
            echo "Attendance recorded successfully! Status: $status";
        } else {
            echo "Error recording attendance.";
        }

        $stmt->close();
    } else {
        echo "Employee not found.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - Mark Attendance</title>
</head>
<body>
    <h1>Mark Your Attendance</h1>
    <?php if ($attendance_window_exceeded): ?>
        <p>Sorry, the attendance window has closed for today. You can no longer mark your attendance.</p>
    <?php else: ?>
        <form action="" method="post">
            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id" required>
            <button type="submit">Submit</button>
        </form>
    <?php endif; ?>
</body>
</html>
 