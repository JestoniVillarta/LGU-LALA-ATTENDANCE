<?php
include '../CONNECTION/connection.php';

$sql = "SELECT EMPLOYEE_ID, NAME, GENDER, EMAIL, CONTACT, ADDRESS, STATUS FROM employee_tbl";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/employee.css">
    <link rel="stylesheet" href="CSS/sidenav.css">
</head>

<body>

    <div class="nav">
        <?php include 'sidenav.php'; ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <h2>Employee List</h2>
        <a href="add.php" class="add-btn">Add Employee</a>

        <div class="table-container">
            <table>
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Status</th> <!-- New Column for Status -->
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
                                <td>" . htmlspecialchars($row["STATUS"]) . "</td> <!-- Display Status -->
                                <td><a href='delete_employee.php?id=" . $row["EMPLOYEE_ID"] . "' class='delete-btn'>Delete</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No employees found</td></tr>";
                }
                $conn->close();
                ?>
            </table>
        </div>

        <br>
      
    </div>

</body>

</html>
