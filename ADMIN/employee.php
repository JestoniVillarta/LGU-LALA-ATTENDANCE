<?php
include '../CONNECTION/connection.php';

$sql = "SELECT * FROM employee_tbl";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/dashboard.css">
    <link rel="stylesheet" href="CSS/employee.css">
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="employee.php">Employee</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="setup_time.php">Setup Time</a></li>
            <li><a href="reports.php">Reports</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
 

        <table>

        <h2>Employee List</h2>
           
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Action</th>
            </tr>

            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row["EMPLOYEE_ID"]) . "</td>
                            <td>" . htmlspecialchars($row["NAME"]) . "</td>
                            <td>" . htmlspecialchars($row["GENDER"]) . "</td>
                            <td>" . htmlspecialchars($row["EMAIL"]) . "</td>
                            <td>" . htmlspecialchars($row["CONTACT"]) . "</td>
                            <td>" . htmlspecialchars($row["ADDRESS"]) . "</td>
                            <td><a href='delete_employee.php?id=" . $row["EMPLOYEE_ID"] . "' class='delete-btn'>Delete</a></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No employees found</td></tr>";
            }
            $conn->close();
            ?>
        </table>

        <br>
        <a href="add.php" class="add-btn">Add Employee</a>
    </div>

</body>
</html>
