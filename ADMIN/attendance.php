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
            WHERE `DATE` = ?";

    // Apply search filter if provided
    if (isset($_GET['search']) && $_GET['search'] !== "") {
        $search = $_GET['search'];
        $sql .= " AND (`STUDENT_ID` LIKE ? OR `NAME` LIKE ?)";
    }

    $stmt = $conn->prepare($sql);
    if ($search !== "") {
        $like_search = "%$search%";
        $stmt->bind_param("sss", $search_date, $like_search, $like_search);
    } else {
        $stmt->bind_param("s", $search_date);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all student data from the students table
    $students_query = "SELECT `STUDENT_ID`, `FIRST_NAME`, `LAST_NAME`, `GENDER` FROM `student_tbl`";
    $students_result = $conn->query($students_query);

    $all_students = [];
    while ($student_row = $students_result->fetch_assoc()) {
        $full_name = $student_row['FIRST_NAME'] . ' ' . $student_row['LAST_NAME'];
        $all_students[$student_row['STUDENT_ID']] = [
            'NAME' => $full_name,
            'GENDER' => $student_row['GENDER']
        ];
    }

    // Check attendance records and update or insert if necessary
    foreach ($all_students as $student_id => $student_data) {
        // Check if student has an attendance record for the given date
        $check_attendance_sql = "SELECT `MORNING_TIME_IN`, `MORNING_TIME_OUT`, `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT` 
                                FROM `attendance_tbl` 
                                WHERE `STUDENT_ID` = ? AND `DATE` = ?";
        $check_stmt = $conn->prepare($check_attendance_sql);
        $check_stmt->bind_param("ss", $student_id, $search_date);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            // Student has an attendance record, check if present or absent
            $attendance_data = $check_result->fetch_assoc();

            $morning_status = (!empty($attendance_data['MORNING_TIME_IN']) && !empty($attendance_data['MORNING_TIME_OUT'])) ? 'Present' : 'Absent';
            $afternoon_status = (!empty($attendance_data['AFTERNOON_TIME_IN']) && !empty($attendance_data['AFTERNOON_TIME_OUT'])) ? 'Present' : 'Absent';

            // Update the attendance record with the correct status
            $update_status_sql = "UPDATE `attendance_tbl` 
                                SET `MORNING_STATUS` = ?, 
                                    `AFTERNOON_STATUS` = ? 
                                WHERE `STUDENT_ID` = ? AND `DATE` = ?";
            $update_stmt = $conn->prepare($update_status_sql);
            $update_stmt->bind_param("ssss", $morning_status, $afternoon_status, $student_id, $search_date);
            $update_stmt->execute();
        } else {
            // No record found, insert a new record as absent
            $name = $student_data['NAME'];
            $gender = $student_data['GENDER'];

            $insert_absent_sql = "INSERT INTO `attendance_tbl` (`STUDENT_ID`, `NAME`, `GENDER`, `MORNING_TIME_IN`, `MORNING_TIME_OUT`, 
                                    `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT`, `DUTY_HOURS`, `DATE`, `MORNING_STATUS`, `AFTERNOON_STATUS`) 
                                VALUES (?, ?, ?, '', '', '', '', 0, ?, 'Absent', 'Absent')";
            $insert_stmt = $conn->prepare($insert_absent_sql);
            $insert_stmt->bind_param("ssss", $student_id, $name, $gender, $search_date);
            $insert_stmt->execute();
        }
    }

    $conn->close();
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
                               
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>