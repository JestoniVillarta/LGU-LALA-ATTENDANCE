<?php
include '../CONNECTION/connection.php';

// Kuhaa ang STUDENT_ID gikan sa URL
if (isset($_GET['id'])) {
    $student_id = $conn->real_escape_string($_GET['id']);
    
    // Kuhaa ang datos sa estudyante
    $sql = "SELECT * FROM student_tbl WHERE STUDENT_ID = '$student_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "Student not found.";
        exit();
    }
} else {
    echo "Invalid student ID.";
    exit();
}

// I-update ang datos kung gi-submit ang form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    
    $update_sql = "UPDATE student_tbl SET 
                   FIRST_NAME = '$first_name',
                   LAST_NAME = '$last_name',
                   GENDER = '$gender',
                   EMAIL = '$email',
                   CONTACT = '$contact',
                   ADDRESS = '$address'
                   WHERE STUDENT_ID = '$student_id'";
    
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Student updated successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
     <link rel="stylesheet" href="CSS/edit_students.css">
</head>
<body>

<div class="nav">
        <?php include 'sidenav.php'; ?>
    </div>

    <div class="header_text">
        <h3>Edit Student Information</h3>
    </div>


    <div class="form-wrapper">

    <form method="POST">

        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo htmlspecialchars($student['FIRST_NAME']); ?>" required>
        
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo htmlspecialchars($student['LAST_NAME']); ?>" required>
        
        <label>Gender:</label>
        <select name="gender" required>
            <option value="Male" <?php if ($student['GENDER'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($student['GENDER'] == 'Female') echo 'selected'; ?>>Female</option>
        </select>
        
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($student['EMAIL']); ?>" required>
        
        <label>Contact:</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($student['CONTACT']); ?>" required>
        
        <label>Address:</label>
        <input type="text" name="address" value="<?php echo htmlspecialchars($student['ADDRESS']); ?>" required>
        
        <button type="submit" class="update-btn">Update Student</button>

        <a href="student.php">Back to Dashboard</a>

    </form>

  
    </div>

    <br>

  

</body>
</html>
