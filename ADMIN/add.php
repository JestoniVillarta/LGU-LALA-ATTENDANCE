<?php
include '../CONNECTION/connection.php';
session_start(); // Start session to store messages

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
        $_SESSION['alert'] = [
            "text" => "Student ID already exists!",
            "icon" => "error",
            "button" => "Try Again"
        ];
    } else {
        // Use Prepared Statements to prevent SQL injection
        $sql = "INSERT INTO student_tbl (STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $student_id, $first_name, $last_name, $gender, $email, $contact, $address);

        if ($stmt->execute()) {
            $_SESSION['alert'] = [
               
                "text" => "Student added successfully!",
                "icon" => "success",
                "button" => "OK",
                "redirect" => "student.php"
            ];
        } else {
            $_SESSION['alert'] = [
                "text" => "Error adding student: " . $stmt->error,
                "icon" => "error",
                "button" => "Close"
            ];
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body>

    <div class="container">

        <div class="nav">
            <?php include 'sidenav.php'; ?>
        </div>

        <div class="content-container">



            <div class="header-text">
                <h3>Add Student</h3>

                <a href="student.php" class="back-btn"><i class="fa-solid fa-backward"></i> Back</a>

            </div>




            <div class="form-wrapper">

                <form method="POST" action="">

                    <input type="text" id="STUDENT_ID" name="student_id" placeholder="Student ID" required><br><br>

                    <div class="name-container">

                        <input type="text" id="FIRST_NAME" name="first_name" placeholder="First Name" required>
                        <input type="text" id="LAST_NAME" name="last_name" placeholder="Last Name" required>

                    </div><br><br>


                    <select id="GENDER" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select><br><br>

                    <input type="email" id="EMAIL" name="email" placeholder="Email" required><br><br>

                    <input type="text" id="PHONE" name="contact" placeholder="Contact" required><br><br>

                    <input type="text" id="ADDRESS" name="address" placeholder="Address" required><br><br>


                    <button type="submit" class="add-btn">Add Student</button>
                </form>
            </div>

        </div>
    </div>


    <!-- Include SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        <?php
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            echo "swal({
            text: '{$alert['text']}',
            icon: '{$alert['icon']}',
            button: '{$alert['button']}'
        })";

            // If a redirect is set, reload the page after clicking OK
            if (isset($alert['redirect'])) {
                echo ".then(() => { window.location.href = '{$alert['redirect']}'; });";
            } else {
                echo ";";
            }

            unset($_SESSION['alert']); // Clear session alert after displaying
        }
        ?>
    </script>

</body>



</html>