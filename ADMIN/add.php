<?php

include '../CONNECTION/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
   
    $sql = "INSERT INTO employee_tbl (EMPLOYEE_ID, NAME, GENDER, EMAIL, CONTACT, ADDRESS)
     VALUES ('$emp_id', '$name', '$gender', '$email','$contact','$address')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New employee added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/dashboard.css">
    <link rel="stylesheet" href="CSS/add.css">

</head>
<body>

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

<!-- Container for Form -->
<div class="container">
    <form method="POST">
        <h3>Add Employee</h3>
        Employee ID: <input type="text" name="employee_id" required><br>
        Name: <input type="text" name="name" required><br>
        Gender: <input type="text" name="gender" required><br>
        Email: <input type="text" name="email" required><br>
        Contact: <input type="text" name="contact" required><br>
        Address: <input type="text" name="address" required><br>
        
        <input type="submit" value="Add Employee">
    </form>

    
<a href="employee.php">back</a>
</div>

</body>


</body>
</html>


