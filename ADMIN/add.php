<?php
include '../CONNECTION/connection.php'; // Ensure this is included for database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);

    // Check if student ID already exists
    $check_sql = "SELECT STUDENT_ID FROM student_tbl WHERE STUDENT_ID = '$student_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "error: Student ID already exists!";
    } else {
        // Proceed with insertion if ID does not exist
        $sql = "INSERT INTO student_tbl (STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS) 
                VALUES ('$student_id', '$first_name', '$last_name', '$gender', '$email', '$contact', '$address')";

        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error; // Debugging output
        }
    }
    $conn->close();
}
?>
