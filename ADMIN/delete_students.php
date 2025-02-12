<?php
include '../CONNECTION/connection.php';

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // I-prepare ang SQL query para sa pag-delete
    $sql = "DELETE FROM student_tbl WHERE STUDENT_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);

    if ($stmt->execute()) {
        echo "<script>
            alert('Student deleted successfully!');
            window.location.href = 'students_list.php'; // Ibalik sa main list
        </script>";
    } else {
        echo "<script>
            alert('Error deleting student.');
            window.location.href = 'ADMIN/student.php';
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>
