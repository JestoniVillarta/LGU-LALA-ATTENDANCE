<?php
include '../CONNECTION/connection.php';

$sql = "SELECT EMPLOYEE_ID, NAME, GENDER, EMAIL, CONTACT, ADDRESS FROM employee_tbl";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
         />
</head>

<body>

    <div class="nav">
        <?php include 'sidenav.php'; ?>
    </div>


  

    
    <div class="table-button-wrapper">
    <h1>Employee List</h1>
  <a href="add.php" class="add-btn"> <i class="fa-solid fa-user-plus"></i> Add Student</a>
</div>



    <!-- Wrapper div to center the table -->
    <div class="table-wrapper">


        <div class="table-container">

            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>