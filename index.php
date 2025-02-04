<?php

include 'CONNECTION/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];

    // Check if employee exists and fetch details (name and gender)
    $query = "SELECT * FROM employee_tbl WHERE EMPLOYEE_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Employee found, fetch name and gender
        $employee = $result->fetch_assoc();
        $name = $employee['NAME'];
        $gender = $employee['GENDER'];

        // Mark attendance and insert name, gender, and status into the attendance table

      
       
        $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('sss', $employee_id, $name, $gender);
$stmt->execute();

        echo "Attendance marked successfully for $name!";
    } else {
        echo "Employee not found!";
    }
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








