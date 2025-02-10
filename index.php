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

    // Identify which button was clicked
    $action = '';
    if (isset($_POST['morning_in'])) {
        $action = "Morning In";
    } elseif (isset($_POST['morning_out'])) {
        $action = "Morning Out";
    } elseif (isset($_POST['afternoon_in'])) {
        $action = "Afternoon In";
    } elseif (isset($_POST['afternoon_out'])) {
        $action = "Afternoon Out";
    }

    if ($action === "") {
        echo "Invalid action.";
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

    // Check existing attendance record
    $query = "SELECT MORNING_TIME_IN, MORNING_TIME_OUT, AFTERNOON_TIME_IN, AFTERNOON_TIME_OUT FROM attendance_tbl WHERE EMPLOYEE_ID = ? AND DATE = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $employee_id, $current_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $attendance = $result->fetch_assoc();

    if ($action === "Morning In" && !empty($attendance['MORNING_TIME_IN'])) {
        echo "You have already timed in for the morning.";
        exit();
    }
    if ($action === "Morning Out" && !empty($attendance['MORNING_TIME_OUT'])) {
        echo "You have already timed out for the morning.";
        exit();
    }
    if ($action === "Afternoon In" && !empty($attendance['AFTERNOON_TIME_IN'])) {
        echo "You have already timed in for the afternoon.";
        exit();
    }
    if ($action === "Afternoon Out" && !empty($attendance['AFTERNOON_TIME_OUT'])) {
        echo "You have already timed out for the afternoon.";
        exit();
    }

    // Attendance logic
    if ($action === "Morning In") {
        $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER, MORNING_TIME_IN, DATE) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssss', $employee_id, $name, $gender, $current_time, $current_date);
    } elseif ($action === "Morning Out") {
        $query = "UPDATE attendance_tbl SET MORNING_TIME_OUT = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $current_time, $employee_id, $current_date);
    } elseif ($action === "Afternoon In") {
        $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_IN = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $current_time, $employee_id, $current_date);
    } elseif ($action === "Afternoon Out") {
        $morning_in = isset($attendance['MORNING_TIME_IN']) ? strtotime($attendance['MORNING_TIME_IN']) : 0;
        $morning_out = isset($attendance['MORNING_TIME_OUT']) ? strtotime($attendance['MORNING_TIME_OUT']) : 0;
        $afternoon_in = isset($attendance['AFTERNOON_TIME_IN']) ? strtotime($attendance['AFTERNOON_TIME_IN']) : 0;
        $afternoon_out = strtotime($current_time);

        $morning_duration = ($morning_out && $morning_in) ? ($morning_out - $morning_in) : 0;
        $afternoon_duration = ($afternoon_out && $afternoon_in) ? ($afternoon_out - $afternoon_in) : 0;
        $total_duration = $morning_duration + $afternoon_duration;

        $total_hours = floor($total_duration / 3600);
        $total_minutes = floor(($total_duration % 3600) / 60);
        $total_seconds = $total_duration % 60;
        $total_time = sprintf("%02d:%02d:%02d", $total_hours, $total_minutes, $total_seconds);

        $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_OUT = ?, DUTY_HOURS = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $current_time, $total_time, $employee_id, $current_date);
    }

    if ($stmt->execute()) {
        echo "Attendance recorded successfully!";
        if ($action === "Afternoon Out") {
            echo "<br>Total time worked: " . $total_time . " hours.";
        }
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
        <button type="submit" name="morning_in">Morning Time In</button>
        <button type="submit" name="morning_out">Morning Time Out</button>
        <button type="submit" name="afternoon_in">Afternoon Time In</button>
        <button type="submit" name="afternoon_out">Afternoon Time Out</button>
    </form>
</body>
</html>
