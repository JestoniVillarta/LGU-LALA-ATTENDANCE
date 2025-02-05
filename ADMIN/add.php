<?php
include '../CONNECTION/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Use Prepared Statements to prevent SQL injection
    $sql = "INSERT INTO employee_tbl (EMPLOYEE_ID, NAME, GENDER, EMAIL, PHONE, ADDRESS) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $emp_id, $name, $gender, $email, $contact, $address);

    if ($stmt->execute()) {
        echo "<script>alert('Employee added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding employee: " . $stmt->error . "');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="CSS/add.css">
    <link rel="stylesheet" href="CSS/sidenav.css">



</head>

<body>


<div class="nav">
   <?php include 'sidenav.php'; ?>
</div>





    <!-- Container for Form -->
    <div class="container">

    <form method="POST" action="">
    <fieldset>
        <legend>
            <h3>Add Employee</h3>
        </legend>

        <input type="text" id="EMPLOYEE_ID" name="employee_id" placeholder="Employee ID" required><br><br>

        <input type="text" id="NAME" name="name" placeholder="Name" required><br><br>

        <select id="GENDER" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select><br><br>

        <input type="email" id="EMAIL" name="email" placeholder="Email" required><br><br>

        <input type="text" id="PHONE" name="contact" placeholder="Contact" required><br><br>

        <textarea id="ADDRESS" name="address" rows="3" placeholder="Address" required></textarea><br><br>

        <input type="submit" value="Add Employee">
    </fieldset>
</form>





    </div>




</body>

<script src="JS/add.js"></script>

</html>