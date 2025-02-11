<?php
include 'CONNECTION/connection.php';

date_default_timezone_set("Asia/Manila");
$current_time = date("H:i");
$current_date = date("Y-m-d");

// Fetch attendance settings
$query = "SELECT * FROM attendance_settings_tbl WHERE id = 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    $morning_start = date("H:i", strtotime($row['MORNING_TIME_IN']));
    $morning_end = date("H:i", strtotime($row['TIME_IN_END']));
    $morning_out = date("H:i", strtotime($row['MORNING_TIME_OUT']));
    $morning_out_end = date("H:i", strtotime($row['TIME_OUT_END']));
    $afternoon_in = date("H:i", strtotime($row['AFTERNOON_TIME_IN']));
    $afternoon_in_end = date("H:i", strtotime($row['AFTERNOON_TIME_IN_END']));
    $afternoon_out = date("H:i", strtotime($row['AFTERNOON_TIME_OUT']));
    $afternoon_out_end = date("H:i", strtotime($row['AFTERNOON_TIME_OUT_END']));
}

// Button visibility
$show_morning_in = ($current_time >= $morning_start && $current_time <= $morning_end);
$show_morning_out = ($current_time >= $morning_out && $current_time <= $morning_out_end);
$show_afternoon_in = ($current_time >= $afternoon_in && $current_time <= $afternoon_in_end);
$show_afternoon_out = ($current_time >= $afternoon_out && $current_time <= $afternoon_out_end);

// Process attendance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id'])) {
    $employee_id = trim($_POST['employee_id']);
    $employee_id = $conn->real_escape_string($employee_id);

    // Fetch employee details
    $emp_query = "SELECT NAME, GENDER FROM employee_tbl WHERE EMPLOYEE_ID = ?";
    $stmt = $conn->prepare($emp_query);
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $emp_result = $stmt->get_result();
    $employee = $emp_result->fetch_assoc();
    $stmt->close();

    if (!$employee) {
        die("Error: Employee not found.");
    }

    $name = $employee['NAME'];
    $gender = $employee['GENDER'];

    // Check existing attendance record for today
    $check_query = "SELECT * FROM attendance_tbl WHERE EMPLOYEE_ID = ? AND DATE = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $employee_id, $current_date);
    $stmt->execute();
    $check_result = $stmt->get_result();
    $attendance = $check_result->fetch_assoc();
    $stmt->close();

    if (isset($_POST['morning_in'])) {
        if (!empty($attendance['MORNING_TIME_IN'])) {
            die("You have already timed in for the morning.");
        }
        $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER, MORNING_TIME_IN, DATE) 
                  VALUES (?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE MORNING_TIME_IN = VALUES(MORNING_TIME_IN)";
    } elseif (isset($_POST['morning_out'])) {
        if (!empty($attendance['MORNING_TIME_OUT'])) {
            die("You have already timed out for the morning.");
        }
        $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER, MORNING_TIME_OUT, DATE) 
                  VALUES (?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE MORNING_TIME_OUT = VALUES(MORNING_TIME_OUT)";
    } elseif (isset($_POST['afternoon_in'])) {
        if (!empty($attendance['AFTERNOON_TIME_IN'])) {
            die("You have already timed in for the afternoon.");
        }
        $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER, AFTERNOON_TIME_IN, DATE) 
                  VALUES (?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE AFTERNOON_TIME_IN = VALUES(AFTERNOON_TIME_IN)";
    } elseif (isset($_POST['afternoon_out'])) {
        if (!empty($attendance['AFTERNOON_TIME_OUT'])) {
            die("You have already timed out for the afternoon.");
        }
        $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER, AFTERNOON_TIME_OUT, DATE) 
                  VALUES (?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE AFTERNOON_TIME_OUT = VALUES(AFTERNOON_TIME_OUT)";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $employee_id, $name, $gender, $current_time, $current_date);

    if ($stmt->execute()) {
        echo "Attendance recorded successfully!";
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
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <h1>Employee Attendance</h1>
    <form action="" method="post">
        <label for="employee_id">Employee ID:</label>
        <input type="text" id="employee_id" name="employee_id" required>
        <br>

        <?php if ($show_morning_in) echo '<button type="submit" name="morning_in">Morning Time In</button>'; ?>
        <?php if ($show_morning_out) echo '<button type="submit" name="morning_out">Morning Time Out</button>'; ?>
        <?php if ($show_afternoon_in) echo '<button type="submit" name="afternoon_in">Afternoon Time In</button>'; ?>
        <?php if ($show_afternoon_out) echo '<button type="submit" name="afternoon_out">Afternoon Time Out</button>'; ?>
    </form>
</body>
</html>
