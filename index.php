<?php

include 'CONNECTION/connection.php';

// Ensure the database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $employee_id = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : '';
    
    if (empty($employee_id)) {
        echo "Employee ID cannot be empty.";
        exit();
    }

    // Get current time as DateTime object, assuming UTC for consistency
    date_default_timezone_set('UTC');  // Set timezone to UTC or your desired timezone
    $current_time = new DateTime();

    // Fetch allowed attendance time
    $query = "SELECT START_TIME, END_TIME FROM attendance_settings_tbl";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $attendance_time = $result->fetch_assoc();

        $start_time = new DateTime($attendance_time['START_TIME']);
        $end_time = new DateTime($attendance_time['END_TIME']);

        // Check if the current time is within the allowed attendance window
        if ($current_time >= $start_time && $current_time <= $end_time) {
            
            // Check if employee exists and fetch details
            $query = "SELECT * FROM employee_tbl WHERE EMPLOYEE_ID = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $employee_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Employee found
                $employee = $result->fetch_assoc();
                $name = $employee['NAME'];
                $gender = $employee['GENDER'];

                // Assuming the office start time is 9:00 AM (you can adjust it as needed)
                $office_start_time = new DateTime($attendance_time['START_TIME']);
                
                // Check the employee's status based on the current time and office start time
                if ($current_time > $office_start_time) {
                    $status = 'Late'; // Employee is late if they arrive after start time
                } elseif ($current_time < $office_start_time) {
                    $status = 'Early'; // Employee is early if they arrive before start time
                } else {
                    $status = 'On Time'; // Employee is on time if they arrive exactly at start time
                }

                // Prepare the query to insert attendance with start time and status
                $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER, START_TIME, END_TIME, STATUS) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssssss', $employee_id, $name, $gender, $current_time->format('Y-m-d H:i:s'), $end_time->format('Y-m-d H:i:s'), $status);

                // Execute the statement
                if ($stmt->execute()) {
                    echo "Attendance recorded successfully!";
                } else {
                    echo "Error recording attendance.";
                }

                // Close the prepared statement
                $stmt->close();
            } else {
                echo "Employee not found.";
            }
        } else {
            echo "You are outside of the allowed attendance window.";
        }
    } else {
        echo "Error fetching attendance settings.";
    }

    // Close the connection
    $conn->close();
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
    <h1>Mark Your Attendance</h1>
    <form action="index.php" method="post">
        <label for="EMPLOYEE_ID">Employee ID:</label>
        <input type="text" id="EMPLOYEE_ID" name="employee_id" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
