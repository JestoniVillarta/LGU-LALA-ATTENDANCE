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
               `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT`, `DUTY_HOURS`, `DATE` 
        FROM `attendance_tbl`
        WHERE `DATE` = '$search_date'";

// Apply search filter if provided
if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = $_GET['search'];
    $sql .= " AND (`STUDENT_ID` LIKE '%$search%' OR `NAME` LIKE '%$search%')";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
    <link rel="stylesheet" href="CSS/attendance.css"> <!-- Link to the CSS file -->
</head>

<body>
    <div class="nav">
        <?php include 'sidenav.php'; ?>
    </div>

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
                        <th>Afternoon Time In</th>
                        <th>Afternoon Time Out</th>
                        <th>Duty Hours</th>
                        <th>Date</th>
                        <th>Action</th>
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
                                    <td>{$row['AFTERNOON_TIME_IN']}</td>
                                    <td>{$row['AFTERNOON_TIME_OUT']}</td>
                                    <td>{$row['DUTY_HOURS']}</td>
                                    <td>{$row['DATE']}</td>
                                    <td>
                                      <a href='delete_employee.php?id={$row['ID']}' class='delete-btn' 
                                      onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                                    </td>
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
</body>

</html>
