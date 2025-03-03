<?php
include '../CONNECTION/connection.php';

// Get student ID from URL securely
$student_id = isset($_GET['id']) ? $_GET['id'] : '';

$student = null;

// Fetch student details using a prepared statement
if (!empty($student_id)) {
    $stmt = $conn->prepare("SELECT FIRST_NAME, LAST_NAME FROM student_tbl WHERE STUDENT_ID = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $student_result = $stmt->get_result();
    $student = $student_result->fetch_assoc();
    $stmt->close();
}

// Set default search date to today
$search_date = date("Y-m-d");

// Get unique available dates from the database
$date_query = "SELECT DISTINCT `DATE` FROM `attendance_tbl` ORDER BY `DATE` DESC";
$date_result = $conn->query($date_query);

$dates = [];
while ($date_row = $date_result->fetch_assoc()) {
    $dates[] = $date_row['DATE'];
}

// If user selects a date, use that date
if (isset($_GET['search_date']) && $_GET['search_date'] !== "") {
    $search_date = $_GET['search_date'];
}

// Build the SQL query to filter attendance by date
$sql = "SELECT `DATE`, `MORNING_TIME_IN`, `MORNING_TIME_OUT`, `MORNING_STATUS`,
                `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT`, `AFTERNOON_STATUS`, `DUTY_HOURS`
        FROM `attendance_tbl`
        WHERE `DATE` = ? AND `STUDENT_ID` = ?";

// Use a prepared statement to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $search_date, $student_id);
$stmt->execute();
$attendance_result = $stmt->get_result();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
    <link rel="stylesheet" href="CSS/student_records.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />


</head>
<body>


<div class="container">


    <div class="nav">
        <?php include 'sidenav.php'; ?>
    </div>

    <div class="content-container">

    <div class="table-calendar-wrapper">
        
        <h3>
        <label> Name: </label>
            <?php 
                echo isset($student['FIRST_NAME']) && isset($student['LAST_NAME']) 
                    ? htmlspecialchars($student['FIRST_NAME'] . ' ' . $student['LAST_NAME']) 
                    : "Student Not Found";
            ?>
        </h3>

        <!-- Date Picker -->
        <div class="calendar-container">
            <form method="GET" id="dateForm">
                <label for="dateSelect">Select Date:</label>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($student_id); ?>">
                <input type="date" name="search_date" id="dateSelect" 
                    value="<?php echo htmlspecialchars($search_date); ?>"
                    <?php if (!empty($dates)) { ?>
                        min="<?php echo min($dates); ?>" max="<?php echo max($dates); ?>"
                    <?php } ?>
                    onchange="document.getElementById('dateForm').submit()">
            </form>
        </div>

        <a href="student.php" class="back-btn"><i class="fa-solid fa-backward"></i> Back</a>
    </div>

    <div class="table-wrapper">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Morning In</th>
                        <th>Morning Out</th>
                        <th>Morning Status</th>
                        <th>Afternoon In</th>
                        <th>Afternoon Out</th>
                        <th>Afternoon Status</th>
                        <th>Duty Hours</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($attendance_result->num_rows > 0) {
                        while ($row = $attendance_result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . htmlspecialchars($row["DATE"]) . "</td>
                                <td>" . htmlspecialchars($row["MORNING_TIME_IN"]) . "</td>
                                <td>" . htmlspecialchars($row["MORNING_TIME_OUT"]) . "</td>
                                <td>" . htmlspecialchars($row["MORNING_STATUS"]) . "</td>
                                <td>" . htmlspecialchars($row["AFTERNOON_TIME_IN"]) . "</td>
                                <td>" . htmlspecialchars($row["AFTERNOON_TIME_OUT"]) . "</td>
                                <td>" . htmlspecialchars($row["AFTERNOON_STATUS"]) . "</td>
                                <td>" . htmlspecialchars($row["DUTY_HOURS"]) . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No attendance records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
