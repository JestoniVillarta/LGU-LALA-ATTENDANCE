<?php
include '../CONNECTION/connection.php';

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    $sql = "DELETE FROM student_tbl WHERE STUDENT_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);

    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>
