<?php
include '../CONNECTION/connection.php'; // Include the database connection file

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$search = "";
$search_date = date("Y-m-d"); // Default to today's date

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

// Build the SQL query to filter by date
$sql = "SELECT `ID`, `STUDENT_ID`, `NAME`, `GENDER`, `MORNING_TIME_IN`, `MORNING_TIME_OUT`, 
               `MORNING_STATUS`, `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT`, 
               `AFTERNOON_STATUS`, `DUTY_HOURS`, `DATE` 
        FROM `attendance_tbl`
        WHERE `DATE` = '$search_date'";


// Apply search filter if provided
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = $_GET['search'];
    $sql .= " AND (`STUDENT_ID` LIKE '%$search%' OR `NAME` LIKE '%$search%')";
}

$result = $conn->query($sql);


// Fetch all student IDs from the students table
$students_query = "SELECT `STUDENT_ID`, `FIRST_NAME`, `LAST_NAME`, `GENDER` FROM `student_tbl`";
$students_result = $conn->query($students_query);

$all_students = [];
while ($student_row = $students_result->fetch_assoc()) {
    // Concatenate first name and last name
    $full_name = $student_row['FIRST_NAME'] . ' ' . $student_row['LAST_NAME'];
    $all_students[$student_row['STUDENT_ID']] = [
        'NAME' => $full_name,
        'GENDER' => $student_row['GENDER']
    ];
}

// Get student IDs who have submitted attendance
$present_students = [];
$attendance_query = "SELECT `STUDENT_ID`, `MORNING_TIME_IN`, `MORNING_TIME_OUT`, `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT` 
                     FROM `attendance_tbl` 
                     WHERE `DATE` = '$search_date'";
$attendance_result = $conn->query($attendance_query);

while ($row = $attendance_result->fetch_assoc()) {
    $present_students[$row['STUDENT_ID']] = $row;
}

// Loop through all students and mark absentees
foreach ($all_students as $student_id => $student_data) {
    // Check if student has an attendance record for the given date
    $check_absent_sql = "SELECT COUNT(*) AS count FROM `attendance_tbl` WHERE `STUDENT_ID` = '$student_id' AND `DATE` = '$search_date'";
    $check_absent_result = $conn->query($check_absent_sql);
    $row = $check_absent_result->fetch_assoc();

    if ($row['count'] == 0) { // No record found for the student on this date
        if (!isset($present_students[$student_id]) || 
            (empty($present_students[$student_id]['MORNING_TIME_IN']) && 
             empty($present_students[$student_id]['MORNING_TIME_OUT']) && 
             empty($present_students[$student_id]['AFTERNOON_TIME_IN']) && 
             empty($present_students[$student_id]['AFTERNOON_TIME_OUT']))) {

            // Student is absent, insert the record
            $name = $student_data['NAME'];
            $gender = $student_data['GENDER'];

            $insert_absent_sql = "INSERT INTO `attendance_tbl` (`STUDENT_ID`, `NAME`, `GENDER`, `MORNING_TIME_IN`, `MORNING_TIME_OUT`, 
                                      `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT`, `DUTY_HOURS`, `DATE`, `MORNING_STATUS`, `AFTERNOON_STATUS`) 
                                  VALUES ('$student_id', '$name', '$gender', '', '', '', '', 0, '$search_date', 'Absent', 'Absent')";
            
            $conn->query($insert_absent_sql);
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
    <link rel="stylesheet" href="CSS/attendance.css"> <!-- Link to the CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />


</head>

<body>

    <div class="container">
        

        <div class="nav">
            <?php include 'sidenav.php'; ?>
        </div>


        <div class="content-container">

            <div class="table-button-wrapper">
                <h3>Student Attendance</h3>

                <!-- Grouping search and calendar filters -->
                <div class="button-search-group">

                    <!-- Date Picker -->
                    <div class="calendar-container">
                        <form method="GET" id="dateForm">
                            <label for="dateSelect">Select Date:</label>
                            <input type="date" name="search_date" id="dateSelect"
                                value="<?php echo htmlspecialchars($search_date); ?>"
                                min="<?php echo !empty($dates) ? min($dates) : ''; ?>"
                                max="<?php echo !empty($dates) ? max($dates) : ''; ?>"
                                onchange="document.getElementById('dateForm').submit()">
                        </form>
                    </div>

                    <!-- Search Bar -->
                    <div class="search-container">
                        <form method="GET">
                            <input type="hidden" name="search_date" value="<?php echo htmlspecialchars($search_date); ?>">
                            <input type="text" name="search" placeholder="Enter Student ID or Name"
                                value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit">🔍</button>
                        </form>
                    </div>

                </div>
            </div>

            <div class="table-wrapper">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Morning Time In</th>
                                <th>Morning Time Out</th>
                                <th>Morning Status</th>
                                <th>Afternoon Time In</th>
                                <th>Afternoon Time Out</th>
                                <th>Afternoon Status</th>
                                <th>Duty Hours</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                    <td>{$row['ID']}</td>
                                    <td>{$row['STUDENT_ID']}</td>
                                    <td>{$row['NAME']}</td>
                                    <td>{$row['GENDER']}</td>
                                    <td>{$row['MORNING_TIME_IN']}</td>
                                    <td>{$row['MORNING_TIME_OUT']}</td>
                                    <td>{$row['MORNING_STATUS']}</td>
                                    <td>{$row['AFTERNOON_TIME_IN']}</td>
                                    <td>{$row['AFTERNOON_TIME_OUT']}</td>
                                    <td>{$row['AFTERNOON_STATUS']}</td>
                                    <td>{$row['DUTY_HOURS']}</td>
                                    <td>{$row['DATE']}</td>
                                </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='11'>No attendance records found for the selected date.</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</body>

</html>