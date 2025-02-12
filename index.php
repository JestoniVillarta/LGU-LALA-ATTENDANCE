<?php
include 'CONNECTION/connection.php';

date_default_timezone_set("Asia/Manila");
$current_time = date("h:i A"); // Convert current time to 12-hour format
$current_date = date("Y-m-d");

// Fetch attendance settings
$query = "SELECT * FROM attendance_settings_tbl WHERE id = 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Convert all times to 12-hour format for display
    $morning_start = date("h:i A", strtotime($row['MORNING_TIME_IN']));
    $morning_end = date("h:i A", strtotime($row['TIME_IN_END']));
    $morning_out = date("h:i A", strtotime($row['MORNING_TIME_OUT']));
    $morning_out_end = date("h:i A", strtotime($row['TIME_OUT_END']));
    $afternoon_in = date("h:i A", strtotime($row['AFTERNOON_TIME_IN']));
    $afternoon_in_end = date("h:i A", strtotime($row['AFTERNOON_TIME_IN_END']));
    $afternoon_out = date("h:i A", strtotime($row['AFTERNOON_TIME_OUT']));
    $afternoon_out_end = date("h:i A", strtotime($row['AFTERNOON_TIME_OUT_END']));
}

// Convert $current_time to 24-hour format for comparison
$current_time_24 = date("H:i");

// Button visibility (using 24-hour format for comparison)
$show_morning_in = ($current_time_24 >= date("H:i", strtotime($row['MORNING_TIME_IN'])) && $current_time_24 <= date("H:i", strtotime($row['TIME_IN_END'])));
$show_morning_out = ($current_time_24 >= date("H:i", strtotime($row['MORNING_TIME_OUT'])) && $current_time_24 <= date("H:i", strtotime($row['TIME_OUT_END'])));
$show_afternoon_in = ($current_time_24 >= date("H:i", strtotime($row['AFTERNOON_TIME_IN'])) && $current_time_24 <= date("H:i", strtotime($row['AFTERNOON_TIME_IN_END'])));
$show_afternoon_out = ($current_time_24 >= date("H:i", strtotime($row['AFTERNOON_TIME_OUT'])) && $current_time_24 <= date("H:i", strtotime($row['AFTERNOON_TIME_OUT_END'])));

// Process attendance submission
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
        die("❌ Error: Employee not found.");
    }

    $name = $employee['NAME'];
    $gender = $employee['GENDER'];

    // Check if attendance record for today exists
    $check_query = "SELECT * FROM attendance_tbl WHERE EMPLOYEE_ID = ? AND DATE = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $employee_id, $current_date);
    $stmt->execute();
    $check_result = $stmt->get_result();
    $attendance = $check_result->fetch_assoc();
    $stmt->close();

    if (!$attendance) {
        // Insert a new record if none exists
        $query = "INSERT INTO attendance_tbl (EMPLOYEE_ID, NAME, GENDER, DATE) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $employee_id, $name, $gender, $current_date);
        $stmt->execute();
        $stmt->close();

        // Fetch the newly inserted record
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ss", $employee_id, $current_date);
        $stmt->execute();
        $check_result = $stmt->get_result();
        $attendance = $check_result->fetch_assoc();
        $stmt->close();
    }

    // Ensure attendance ID exists
    if (empty($attendance['ID'])) {
        die("Error: Attendance ID is missing!");
    }

    $attendance_id = intval($attendance['ID']); // Ensure it's an integer

    // Prevent duplicate entries for each time slot
    if (isset($_POST['morning_in']) && empty($attendance['MORNING_TIME_IN'])) {
        $query = "UPDATE attendance_tbl SET MORNING_TIME_IN = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
    } elseif (isset($_POST['morning_out']) && empty($attendance['MORNING_TIME_OUT'])) {
        $query = "UPDATE attendance_tbl SET MORNING_TIME_OUT = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
    } elseif (isset($_POST['afternoon_in']) && empty($attendance['AFTERNOON_TIME_IN'])) {
        $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_IN = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
    } elseif (isset($_POST['afternoon_out']) && empty($attendance['AFTERNOON_TIME_OUT'])) {
        $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_OUT = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
    } else {
        die("❌ Error: Duplicate entry detected.");
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $current_time, $employee_id, $current_date);

    if ($stmt->execute()) {
        echo "✅ Attendance recorded successfully!";
    } else {
        echo "❌ Error recording attendance.";
    }
    $stmt->close();

    if (empty($attendance['ID'])) {
        die("Error: Attendance ID is missing!");
    }

    $attendance_id = intval($attendance['ID']);

    if (isset($_POST['morning_in']) && empty($attendance['MORNING_TIME_IN'])) {
        $query = "UPDATE attendance_tbl SET MORNING_TIME_IN = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
    } elseif (isset($_POST['morning_out']) && empty($attendance['MORNING_TIME_OUT'])) {
        $query = "UPDATE attendance_tbl SET MORNING_TIME_OUT = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
    } elseif (isset($_POST['afternoon_in']) && empty($attendance['AFTERNOON_TIME_IN'])) {
        $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_IN = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
    } elseif (isset($_POST['afternoon_out']) && empty($attendance['AFTERNOON_TIME_OUT'])) {
        $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_OUT = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
    } else {
        die("❌ Error: Duplicate entry detected.");
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $current_time, $employee_id, $current_date);
    $stmt->execute();
    $stmt->close();

    // Recalculate total duty time
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $employee_id, $current_date);
    $stmt->execute();
    $check_result = $stmt->get_result();
    $attendance = $check_result->fetch_assoc();
    $stmt->close();

    function compute_total_time($time_in, $time_out) {
        if ($time_in && $time_out) {
            $start = strtotime($time_in);
            $end = strtotime($time_out);
            return $end - $start;
        }
        return 0;
    }

    $morning_seconds = compute_total_time($attendance['MORNING_TIME_IN'], $attendance['MORNING_TIME_OUT']);
    $afternoon_seconds = compute_total_time($attendance['AFTERNOON_TIME_IN'], $attendance['AFTERNOON_TIME_OUT']);
    
    $total_seconds = $morning_seconds + $afternoon_seconds;
    $total_hours = floor($total_seconds / 3600);
    $total_minutes = round(($total_seconds % 3600) / 60);
    $total_time = sprintf("%d.%02d", $total_hours, $total_minutes);

    $update_query = "UPDATE attendance_tbl SET DUTY_HOURS = ? WHERE EMPLOYEE_ID = ? AND DATE = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sss", $total_time, $employee_id, $current_date);
    $stmt->execute();
    $stmt->close();

    echo "✅ Attendance recorded successfully! Total Duty Time: " . $total_time . " hours.";
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
    <h1>TRAINEE ATTENDANCE SYSTEM</h1>
   

    <form action="" method="post">
      
        <input type="text" id="employee_id" placeholder="ENTER YOUR ID:" name="employee_id"  required>
        <br>

        <?php if ($show_morning_in) echo '<button type="submit" name="morning_in" >Morning Time In </button>'; ?>
        <?php if ($show_morning_out) echo '<button type="submit" name="morning_out" style="background-color: red;">Morning Time Out</button>'; ?>
        <?php if ($show_afternoon_in) echo '<button type="submit" name="afternoon_in">Afternoon Time In </button>'; ?>
        <?php if ($show_afternoon_out) echo '<button type="submit" name="afternoon_out">Afternoon Time Out </button>'; ?>
    </form>
</body>
</html>
