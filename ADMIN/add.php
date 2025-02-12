<?php
include '../CONNECTION/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Check if the student already exists
    $check_sql = "SELECT * FROM student_tbl WHERE STUDENT_ID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $student_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        echo "<script>alert('Student ID already exists!');</script>";
    } else {
        // Use Prepared Statements to prevent SQL injection
        $sql = "INSERT INTO student_tbl (STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $student_id, $first_name, $last_name, $gender, $email, $contact, $address);

        if ($stmt->execute()) {
            echo "<script>alert('Student added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding student: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="CSS/add.css">
    <link rel="stylesheet" href="CSS/sidenav.css">
</head>

<body>

<div class="nav">
   <?php include 'sidenav.php'; ?>
</div>

            <div class="header-text">
                <h3>Add Student</h3>
            </div>
                
        
<div class="form-wrapper">
    
    <form method="POST" action="">    

            <input type="text" id="STUDENT_ID" name="student_id" placeholder="Student ID" required><br><br>

            <input type="text" id="FIRST_NAME" name="first_name" placeholder="First Name" required><br><br>
            
            <input type="text" id="LAST_NAME" name="last_name" placeholder="Last Name" required><br><br>

            <select id="GENDER" name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select><br><br>

            <input type="email" id="EMAIL" name="email" placeholder="Email" required><br><br>

            <input type="text" id="PHONE" name="contact" placeholder="Contact" required><br><br>

            <textarea id="ADDRESS" name="address" rows="3" placeholder="Address" required></textarea><br><br>

            <button type="submit" class="add-btn">Add Student</button>
    </form>
</div>

</body>

<script src="JS/add.js"></script>

</html>
