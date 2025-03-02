<?php
include '../CONNECTION/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $original_student_id = $_POST['original_student_id']; // The ID before editing
    $student_id = $_POST['student_id']; // The new or unchanged Student ID
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Check if the original student exists
    $checkQuery = "SELECT * FROM student_tbl WHERE STUDENT_ID = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $original_student_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Check if the new student_id already exists and is not the same as the original
        if ($student_id !== $original_student_id) {
            $checkDuplicateQuery = "SELECT * FROM student_tbl WHERE STUDENT_ID = ?";
            $checkDuplicateStmt = $conn->prepare($checkDuplicateQuery);
            $checkDuplicateStmt->bind_param("s", $student_id);
            $checkDuplicateStmt->execute();
            $duplicateResult = $checkDuplicateStmt->get_result();

            if ($duplicateResult->num_rows > 0) {
                echo "<script>alert('Error: Student ID $student_id is already in use. Please choose a different ID.'); window.location.href='student.php';</script>";
                exit;
            }

            $checkDuplicateStmt->close();
        }

        // Proceed with update
        $query = "UPDATE student_tbl SET STUDENT_ID=?, FIRST_NAME=?, LAST_NAME=?, GENDER=?, EMAIL=?, CONTACT=?, ADDRESS=? WHERE STUDENT_ID=?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssssssss", $student_id, $first_name, $last_name, $gender, $email, $contact, $address, $original_student_id);

            if ($stmt->execute()) {
                echo "<script>alert('Student updated successfully!'); window.location.href='student.php';</script>";
                exit;
            } else {
                echo "<script>alert('Error updating student: " . $stmt->error . "'); window.location.href='student.php';</script>";
                exit;
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error preparing statement: " . $conn->error . "'); window.location.href='student.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Error: Student with ID $original_student_id not found!'); window.location.href='student.php';</script>";
        exit;
    }

    $checkStmt->close();
    $conn->close();
}
?>
