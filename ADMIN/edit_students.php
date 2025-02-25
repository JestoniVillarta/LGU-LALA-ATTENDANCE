<?php
include '../CONNECTION/connection.php';
session_start(); // Start session for alerts

if (isset($_GET['id'])) {
    $student_id = $conn->real_escape_string($_GET['id']);

    $sql = "SELECT * FROM student_tbl WHERE STUDENT_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        $_SESSION['alert'] = [
            "title" => "Error!",
            "text" => "Student not found.",
            "icon" => "error",
            "button" => "OK",
            "redirect" => "student.php"
        ];
        header("Location: student.php");
        exit();
    }
    $stmt->close();
} else {
    $_SESSION['alert'] = [
        "title" => "Invalid!",
        "text" => "Invalid student ID.",
        "icon" => "error",
        "button" => "OK",
        "redirect" => "student.php"
    ];
    header("Location: student.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_student_id = $conn->real_escape_string($_POST['student_id']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);

    if ($new_student_id !== $student_id) {
        $check_id_sql = "SELECT * FROM student_tbl WHERE STUDENT_ID = ?";
        $check_stmt = $conn->prepare($check_id_sql);
        $check_stmt->bind_param("s", $new_student_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $_SESSION['alert'] = [
                "title" => "Oops!",
                "text" => "The new Student ID already exists. Please choose another.",
                "icon" => "error",
                "button" => "Try Again"
            ];
        } else {
            $update_sql = "UPDATE student_tbl SET STUDENT_ID = ?, FIRST_NAME = ?, LAST_NAME = ?, GENDER = ?, EMAIL = ?, CONTACT = ?, ADDRESS = ? WHERE STUDENT_ID = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssssss", $new_student_id, $first_name, $last_name, $gender, $email, $contact, $address, $student_id);

            if ($update_stmt->execute()) {
                $_SESSION['alert'] = [
                    "title" => "Updated!",
                    "text" => "Student information updated successfully!",
                    "icon" => "success",
                    "button" => "OK",
                    "redirect" => "student.php"
                ];
            } else {
                $_SESSION['alert'] = [
                    "title" => "Error!",
                    "text" => "Failed to update student: " . $conn->error,
                    "icon" => "error",
                    "button" => "Close"
                ];
            }
            $update_stmt->close();
        }
        $check_stmt->close();
    } else {
        $update_sql = "UPDATE student_tbl SET FIRST_NAME = ?, LAST_NAME = ?, GENDER = ?, EMAIL = ?, CONTACT = ?, ADDRESS = ? WHERE STUDENT_ID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssss", $first_name, $last_name, $gender, $email, $contact, $address, $student_id);

        if ($update_stmt->execute()) {
            $_SESSION['alert'] = [
                "title" => "Updated!",
                "text" => "Student information updated successfully!",
                "icon" => "success",
                "button" => "OK",
                "redirect" => "student.php"
            ];
        } else {
            $_SESSION['alert'] = [
                "title" => "Error!",
                "text" => "Failed to update student: " . $conn->error,
                "icon" => "error",
                "button" => "Close"
            ];
        }
        $update_stmt->close();
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

    <div class="container">

        <div class="nav">
            <?php include 'sidenav.php'; ?>
        </div>

        <div class="content-container">

            <div class="header_text">
                <h3>Edit Student Information</h3>
                <a href="student.php" class="back-btn"><i class="fa-solid fa-backward"></i> Back</a>
            </div>
            <div class="form-wrapper">
                <form method="POST">
                    <label>Student ID:</label>
                    <input type="text" name="student_id" value="<?php echo htmlspecialchars($student['STUDENT_ID']); ?>" required>

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
                </form>
            </div>

        </div>
    </div>


    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    <?php
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        echo "swal({
            title: '{$alert['title']}',
            text: '{$alert['text']}',
            icon: '{$alert['icon']}',
            button: '{$alert['button']}'
        })";

        if (isset($alert['redirect'])) {
            echo ".then(() => { window.location.href = '{$alert['redirect']}'; });";
        } else {
            echo ";";
        }

        unset($_SESSION['alert']);
    }
    ?>
</script>
    
</body>

</html>