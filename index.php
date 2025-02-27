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

// Initialize modal message and flag
$modalMessage = "";
$showModal = false;

// Determine the title
$currentHour = date('H');  // Gets the current hour in 24-hour format.


$currentHour = date('G'); // Gets the current hour in 24-hour format without leading zeros

// Initialize a variable to hold the message
$attendanceMessage = '';

// Determine the message based on the current hour
if ($currentHour < 12) {
    $attendanceMessage = 'Morning Attendance';
} else {
    $attendanceMessage = 'Afternoon Attendance';
}



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
        $modalMessage = "❌ Error: Student not found.";
        $showModal = true;
    } else {
        $name = trim($student['FIRST_NAME'] . ' ' . $student['LAST_NAME']);
        $gender = $student['GENDER'] ?? 'Unknown';

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
        $date_today = date('Y-m-d'); // Get the current date

        if (
            (isset($_POST['morning_in']) && !empty($attendance['MORNING_TIME_IN']) && $attendance['DATE'] === $date_today) ||
            (isset($_POST['morning_out']) && !empty($attendance['MORNING_TIME_OUT']) && $attendance['DATE'] === $date_today) ||
            (isset($_POST['afternoon_in']) && !empty($attendance['AFTERNOON_TIME_IN']) && $attendance['DATE'] === $date_today) ||
            (isset($_POST['afternoon_out']) && !empty($attendance['AFTERNOON_TIME_OUT']) && $attendance['DATE'] === $date_today)
        ) {
            $modalMessage = "❌ Error: Duplicate entry detected for today.";
            $showModal = true;
        }
         else {
            // Process attendance update
            if (isset($_POST['morning_in'])) {
                $query = "UPDATE attendance_tbl SET MORNING_TIME_IN = ?, MORNING_STATUS = 'Present' WHERE STUDENT_ID = ? AND DATE = ?";
            } elseif (isset($_POST['morning_out'])) {
                $query = "UPDATE attendance_tbl SET MORNING_TIME_OUT = ? WHERE STUDENT_ID = ? AND DATE = ?";
            } elseif (isset($_POST['afternoon_in'])) {
                $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_IN = ?, AFTERNOON_STATUS = 'Present' WHERE STUDENT_ID = ? AND DATE = ?";
            } elseif (isset($_POST['afternoon_out'])) {
                $query = "UPDATE attendance_tbl SET AFTERNOON_TIME_OUT = ? WHERE STUDENT_ID = ? AND DATE = ?";
            } else {
                $modalMessage = "❌ Error: Invalid entry.";
                $showModal = true;
            }
    

            if (!empty($query)) {
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

                $modalMessage = "✅ Attendance recorded successfully!";
                $showModal = true;
            }
        }
    }
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


<div class="container">

<h1 class="title">TRAINEE ATTENDANCE SYSTEM</h1>

<h2>  <?php echo $attendanceMessage; ?>  </h2>


<form action="" method="post">

    <input type="text" id="STUDENT_ID" placeholder="ENTER YOUR ID:" name="student_id" required>

    <br>

    <div class="submit-btn">

    <?php if ($show_morning_in) echo '<button type="submit" name="morning_in" >Morning Time In</button>'; ?>
    <?php if ($show_morning_out) echo '<button type="submit" name="morning_out" class="morning_out">Morning Time Out</button>'; ?>
    <?php if ($show_afternoon_in) echo '<button type="submit" name="afternoon_in">Afternoon Time In</button>'; ?>
    <?php if ($show_afternoon_out) echo '<button type="submit" name="afternoon_out" class="afternoon_out">Afternoon Time Out</button>'; ?>

    </div>
   

</form>

</div>



    <div id="successModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage">✅ Attendance recorded successfully!</p>
        </div>
    </div>

    <div id="errorModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-error">&times;</span>
            <p id="errorModalMessage"></p>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const successModal = document.getElementById("successModal");
        const successCloseButton = document.querySelector(".close");

        const errorModal = document.getElementById("errorModal");
        const errorCloseButton = document.querySelector(".close-error");

        function showModal(modal) {
            modal.style.display = "block";
            setTimeout(function () {
                modal.style.display = "none";
            }, 1000);
        }

        successCloseButton.addEventListener("click", function () {
            successModal.style.display = "none";
        });

        errorCloseButton.addEventListener("click", function () {
            errorModal.style.display = "none";
        });

        window.onclick = function (event) {
            if (event.target == successModal) {
                successModal.style.display = "none";
            } else if (event.target == errorModal) {
                errorModal.style.display = "none";
            }
        };

        // Show the appropriate modal based on the PHP flag
        <?php if ($showModal) { ?>
            document.getElementById('modalMessage').textContent = "<?php echo $modalMessage; ?>";
            showModal(successModal);
        <?php } elseif (!empty($modalMessage)) { ?>
            document.getElementById('errorModalMessage').textContent = "<?php echo $modalMessage; ?>";
            showModal(errorModal);
        <?php } ?>
    });
    </script>
    
<script src="JS/index.js"></script>


</body>
</html>

