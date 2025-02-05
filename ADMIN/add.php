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

        <form method="POST">
            <fieldset>
                <legend>
                    <h3>Add Employee</h3>
                </legend>

                <input type="text" id="employee_id" name="employee_id" placeholder="Employee ID:" required><br><br>


                <input type="text" id="name" name="name" placeholder="Name" required><br><br>


                <select id="gender" name="gender" placeholder="Gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select><br><br>

                <input type="email" id="email" name="email" placeholder="Email" required><br><br>

                <input type="tel" id="contact" name="contact" pattern="[0-9]{10}" placeholder="Contact" required><br><br>

                <textarea id="address" name="address" rows="3" placeholder="Address" required></textarea><br><br>

                <input type="submit" value="Add Employee">
            </fieldset>
        </form>




    </div>




</body>

<script src="JS/add.js"></script>

</html>