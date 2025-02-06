<?php
include 'CONNECTION/connection.php';

date_default_timezone_set('Asia/Manila'); // Set timezone

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_id'])) {
    $employee_id = trim($_POST['employee_id']);
    $current_time = date("H:i:s");
    $current_date = date("Y-m-d");

    if (empty($employee_id)) {
        echo "Employee ID is required.";
        exit();
    }

    // Get employee details
    $query = "SELECT NAME, GENDER FROM employee_tbl WHERE EMPLOYEE_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        $name = $employee['NAME'];
        $gender = $employee['GENDER'];
    } else {
        echo "Employee not found.";
        exit();
    }

    // Get attendance settings
    $query = "SELECT MORNING_TIME_IN, MORNING_TIME_OUT FROM attendance_settings_tbl WHERE id = 2";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $start_time = date("H:i:s", strtotime($row['MORNING_TIME_IN']));
        $end_time = date("H:i:s", strtotime($row['MORNING_TIME_OUT']));
    } else {
        echo "Attendance time settings not found.";
        exit();
    }

    // Determine status
    if ($current_time < $start_time) {
        $status = "Early";
    } elseif ($current_time >= $start_time && $current_time <= $end_time) {
        $status = "On Time";
    } else {
        $status = "Late";
    }

    // Check if employee already timed in
    $query = "SELECT * FROM attendance_tbl WHERE EMPLOYEE_ID = ? AND DATE = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $employee_id, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "You have already timed in today.";
        exit();
    }

    // Insert attendance record
    $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER, MORNING_TIME_IN, STATUS) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $employee_id, $name, $gender, $current_time, $status);
    
    if ($stmt->execute()) {
        echo "Attendance recorded successfully! Status: $status";
    } else {
        echo "Error recording attendance.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
</head>
<body>
    <h1>Employee Attendance</h1>
    <form action="" method="post">
        <label for="employee_id">Employee ID:</label>
        <input type="text" id="employee_id" name="employee_id" required>
        <br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
