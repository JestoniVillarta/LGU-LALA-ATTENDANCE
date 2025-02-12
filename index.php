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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['student_id'])) {
    $student_id = trim($_POST['student_id']);
    $student_id = $conn->real_escape_string($student_id);

    // Fetch student details
    $stu_query = "SELECT FIRST_NAME, LAST_NAME, GENDER FROM student_tbl WHERE STUDENT_ID = ?";
    $stmt = $conn->prepare($stu_query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stu_result = $stmt->get_result();
    $student = $stu_result->fetch_assoc();
    $stmt->close();

    if (!$student) {
        die("❌ Error: Student not found.");
    }

    $name = $student['FIRST_NAME'] . ' ' . $student['LAST_NAME'];
    $gender = $student['GENDER'];

    // Check if attendance record for today exists
    $check_query = "SELECT * FROM attendance_tbl WHERE STUDENT_ID = ? AND DATE = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $student_id, $current_date);
    $stmt->execute();
    $check_result = $stmt->get_result();
    $attendance = $check_result->fetch_assoc();
    $stmt->close();

    if (!$attendance) {
        // Insert a new record if none exists
        $query = "INSERT INTO attendance_tbl (STUDENT_ID, NAME, GENDER, DATE) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $student_id, $name, $gender, $current_date);
        $stmt->execute();
        $stmt->close();
    }

        // Prevent duplicate submission
    if ((isset($_POST['morning_in']) && !empty($attendance['MORNING_TIME_IN'])) ||
        (isset($_POST['morning_out']) && !empty($attendance['MORNING_TIME_OUT'])) ||
        (isset($_POST['afternoon_in']) && !empty($attendance['AFTERNOON_TIME_IN'])) ||
        (isset($_POST['afternoon_out']) && !empty($attendance['AFTERNOON_TIME_OUT']))) {
        die("❌ Error: Duplicate entry detected.");
    }

    // Process attendance update
    if (isset($_POST['morning_in'])) {
        $query = "UPDATE attendance_tbl SET MORNING_TIME_IN = ? WHERE STUDENT_ID = ? AND DATE = ?";
    } elseif (isset($_POST['morning_out'])) {
        $query = "UPDATE attendance_tbl SET MORNING_TIME_OUT = ? WHERE STUDENT_ID = ? AND DATE = ?";
    } elseif (isset($_POST['afternoon_in'])) {
        $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_IN = ? WHERE STUDENT_ID = ? AND DATE = ?";
    } elseif (isset($_POST['afternoon_out'])) {
        $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_OUT = ? WHERE STUDENT_ID = ? AND DATE = ?";
    } else {
        die("❌ Error: Duplicate entry detected.");
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $current_time, $student_id, $current_date);
    $stmt->execute();
    $stmt->close();

    // Recalculate total duty time
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $student_id, $current_date);
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

    $update_query = "UPDATE attendance_tbl SET DUTY_HOURS = ? WHERE STUDENT_ID = ? AND DATE = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sss", $total_time, $student_id, $current_date);
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
    <title>Student Attendance</title>
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
    <h1>TRAINEE ATTENDANCE SYSTEM</h1>
    
    <form action="" method="post">
        <input type="text" id="student_id" placeholder="ENTER YOUR ID:" name="student_id" required>
        <br>
        <?php if ($show_morning_in) echo '<button type="submit" name="morning_in">Morning Time In</button>'; ?>
        <?php if ($show_morning_out) echo '<button type="submit" name="morning_out" style="background-color: red;">Morning Time Out</button>'; ?>
        <?php if ($show_afternoon_in) echo '<button type="submit" name="afternoon_in">Afternoon Time In</button>'; ?>
        <?php if ($show_afternoon_out) echo '<button type="submit" name="afternoon_out">Afternoon Time Out</button>'; ?>
    </form>
</body>
</html>


