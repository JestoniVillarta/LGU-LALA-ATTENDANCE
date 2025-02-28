<?php
include '../CONNECTION/connection.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_date = isset($_GET['search_date']) ? $conn->real_escape_string($_GET['search_date']) : date("Y-m-d");

// Base SQL query
$sql = "SELECT STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS FROM student_tbl";
$conditions = [];

// Add conditions based on the search input
if (!empty($search)) {
    if (is_numeric($search)) {
        $conditions[] = "STUDENT_ID = '$search'";
    } else {
        $conditions[] = "(FIRST_NAME LIKE '%$search%' OR LAST_NAME LIKE '%$search%')";
    }
}

// Append conditions to the SQL query if any are present
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

// Handle the POST request for adding a new student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);

    // Check if the student ID already exists
    $check_sql = "SELECT STUDENT_ID FROM student_tbl WHERE STUDENT_ID = '$student_id'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo "error: Student ID already exists!";
    } else {
        // Insert new student record
        $sql = "INSERT INTO student_tbl (STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS) 
                VALUES ('$student_id', '$first_name', '$last_name', '$gender', '$email', '$contact', '$address')";

        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    $conn->close();
    exit;
}

// Execute the SQL query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/student.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->

</head>

<body>

    <div class="container">
        <div class="nav">
            <?php include 'sidenav.php'; ?>
        </div>

        <div class="content-container">
            <div class="table-button-wrapper">
                <h3>Student List</h3>
                <div class="button-search-group">
                    <div class="search-container">
                        <form method="GET">
                            <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit">🔍</button>
                        </form>
                    </div>
                    <a href="#" class="add-btn" onclick="openModal()"><i class="fa-solid fa-user-plus"></i> Add Student</a>
                </div>
            </div>

            <div class="table-wrapper">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th class="student">Student ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr id='row_" . htmlspecialchars($row['STUDENT_ID'], ENT_QUOTES, 'UTF-8') . "'>
                                    <td  class='student-td'>" . htmlspecialchars($row['STUDENT_ID'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td>" . htmlspecialchars($row['FIRST_NAME'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td>" . htmlspecialchars($row['LAST_NAME'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td>" . htmlspecialchars($row['GENDER'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td>" . htmlspecialchars($row['EMAIL'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td>" . htmlspecialchars($row['CONTACT'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td>" . htmlspecialchars($row['ADDRESS'], ENT_QUOTES, 'UTF-8') . "</td>
                                    <td class='action-cell'>
                                        <div class='actions'>
                                            <a href='edit_students.php?id=" . htmlspecialchars($row['STUDENT_ID'], ENT_QUOTES, 'UTF-8') . "' class='edit'>
                                                <span class='tooltip'>
                                                    <i class='fa-sharp fa-solid fa-pen icon-background'></i>
                                                    <span class='tooltiptext'>Edit Student</span>
                                                </span>
                                            </a>
                                            <a href='#' onclick='confirmDelete(" . htmlspecialchars($row['STUDENT_ID'], ENT_QUOTES, 'UTF-8') . ")' class='delete'>
                                                <span class='tooltip'>
                                                    <i class='fa-solid fa-trash'></i>
                                                    <span class='tooltiptext'>Delete Student</span>
                                                </span>
                                            </a>
                                            <a href='student_records.php?id=" . htmlspecialchars($row['STUDENT_ID'], ENT_QUOTES, 'UTF-8') . "' class='view'>
                                                <span class='tooltip'>
                                                    <i class='fa-regular fa-rectangle-list icon-background'></i>
                                                    <span class='tooltiptext'>View Attendance</span>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No students found</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content">

            <span class="close" onclick="closeModal()">&times;</span>

            <h2>Add Student</h2>


            <form id="addStudentForm" method="POST" action="">


                <div class="name-container">
                    <div class="input-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    <div class="input-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                </div>

                <div class="id-gender-container">
                    <div class="input-group">
                        <label for="STUDENT_ID">Student ID:</label>
                        <input type="text" id="STUDENT_ID" name="student_id" placeholder="Student ID" required>
                    </div>
                    <div class="input-group">
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="contact-container">
                    <div class="input-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="contact">Contact:</label>
                        <input type="text" id="contact" name="contact" required>
                    </div>
                </div>


                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>

                <button type="submit" class="add-button">Add Student</button>

            </form>
        </div>
    </div>


    <script>
    function openModal() {
        document.getElementById('addStudentModal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('addStudentModal').style.display = 'none';
    }
    $(document).ready(function() {
        $('#addStudentForm').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: 'student.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.trim() === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Student Added!',
                            text: 'The student has been successfully added.',
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response,
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    });
                }
            });
        });
    });
    </script>

   
</body>

</html>