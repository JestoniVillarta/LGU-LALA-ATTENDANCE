<?php
include '../CONNECTION/connection.php'; // Include the database connection file

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$search = "";
$search_date = date("Y-m-d"); // Default to today's date

// Get available dates
$date_query = "SELECT DISTINCT `DATE` FROM `attendance_tbl` ORDER BY `DATE` DESC";
$date_result = $conn->query($date_query);

$dates = [];
while ($date_row = $date_result->fetch_assoc()) {
    $dates[] = $date_row['DATE'];
}

// Check if a date is selected
if (!empty($_GET['search_date'])) {
    $search_date = $conn->real_escape_string($_GET['search_date']);
}

// Check if a search term is entered
if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// **Filter only one student based on search input**
$sql = "SELECT * FROM `attendance_tbl` WHERE `DATE` = '$search_date'";

if (!empty($search)) {
    $sql .= " AND (`STUDENT_ID` = '$search' OR `NAME` LIKE '%$search%')";  // Only exact ID match or partial name match
}

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <link rel="stylesheet" href="CSS/attendance.css">
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
                <div class="button-search-group">
                    <div class="calendar-container">
                        <form method="GET" id="dateForm">
                            <label for="dateSelect">Select Date:</label>
                            <input type="date" name="search_date" id="dateSelect" value="<?php echo htmlspecialchars($search_date); ?>" onchange="document.getElementById('dateForm').submit()">
                        </form>
                    </div>
                    <div class="search-container">
                        <form method="GET">
                            <input type="hidden" name="search_date" value="<?php echo htmlspecialchars($search_date); ?>">
                            <input type="text" name="search" placeholder="Enter Student ID or Name" value="<?php echo htmlspecialchars($search); ?>">
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
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Morning Time In</th>
                                <th>Morning Time Out</th>
                                <th>Morning Status</th>
                                <th>Afternoon Time In</th>
                                <th>Afternoon Time Out</th>
                                <th>Afternoon Status</th>
                                <th>Date</th>
                                <th>Duty Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $morning_status_color = ($row['MORNING_STATUS'] == 'Present') ? 'green' : 'red';
                                    $afternoon_status_color = ($row['AFTERNOON_STATUS'] == 'Present') ? 'green' : 'red';
                                    echo "<tr>";
                                    echo "<td>{$row['STUDENT_ID']}</td>";
                                    echo "<td>{$row['NAME']}</td>";
                                    echo "<td>{$row['GENDER']}</td>";
                                    echo "<td>{$row['MORNING_TIME_IN']}</td>";
                                    echo "<td>{$row['MORNING_TIME_OUT']}</td>";
                                    echo "<td style='color: $morning_status_color; font-weight: bold;'>{$row['MORNING_STATUS']}</td>";
                                    echo "<td>{$row['AFTERNOON_TIME_IN']}</td>";
                                    echo "<td>{$row['AFTERNOON_TIME_OUT']}</td>";
                                    echo "<td style='color: $afternoon_status_color; font-weight: bold;'>{$row['AFTERNOON_STATUS']}</td>";
                                    echo "<td>{$row['DATE']}</td>";
                                    echo "<td>{$row['DUTY_HOURS']}</td>";
                                    echo "</tr>";
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
