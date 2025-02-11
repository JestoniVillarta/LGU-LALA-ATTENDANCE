<?php
include '../CONNECTION/connection.php'; // Include the database connection file

$sql = "SELECT `ID`, `EMPLOYEE_ID`, `NAME`, `GENDER`, `MORNING_TIME_IN`, `MORNING_TIME_OUT`, 
               `AFTERNOON_TIME_IN`, `AFTERNOON_TIME_OUT`, `DUTY_HOURS`, `DATE` 
        FROM `attendance_tbl`";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance</title>
    <link rel="stylesheet" href="CSS/attendance.css"> <!-- Link to the CSS file -->
</head>

<body>
    <div class="nav">
        <?php include 'sidenav.php'; ?>
    </div>



    <div class="table-button-wrapper">
        <h3>Student Attendance</h3>
    </div>

    <div class="table-wrapper">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee ID</th>
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
                                    <td>{$row['EMPLOYEE_ID']}</td>
                                    <td>{$row['NAME']}</td>
                                    <td>{$row['GENDER']}</td>
                                    <td>{$row['MORNING_TIME_IN']}</td>
                                    <td>{$row['MORNING_TIME_OUT']}</td>
                                    <td>{$row['AFTERNOON_TIME_IN']}</td>
                                    <td>{$row['AFTERNOON_TIME_OUT']}</td>
                                    <td>{$row['DUTY_HOURS']}</td>
                                    <td>{$row['DATE']}</td>
                                    <td><a href='delete_employee.php?id={$row['ID']}' class='delete-btn'>Delete</a></td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='11'>No records found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>