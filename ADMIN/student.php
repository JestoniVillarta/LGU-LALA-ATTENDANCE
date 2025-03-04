<?php
include '../CONNECTION/connection.php';
// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_date = isset($_GET['search_date']) ? $conn->real_escape_string($_GET['search_date']) : date("Y-m-d");

// Base SQL query for displaying students
$sql = "SELECT STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS FROM student_tbl";
$conditions = [];

// Add conditions based on the search input
if (!empty($search)) {
    if (is_numeric($search)) {
        $conditions[] = "STUDENT_ID LIKE '%$search%'";
    } else {
        $conditions[] = "(FIRST_NAME LIKE '%$search%' OR LAST_NAME LIKE '%$search%')";
    }
}

// Append conditions to the SQL query if any are present
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

// Get search and gender filter values from URL parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$gender_filter = isset($_GET['gender']) ? $conn->real_escape_string($_GET['gender']) : '';

// Base SQL query
$sql = "SELECT STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS FROM student_tbl WHERE 1";

// Add search condition
if (!empty($search)) {
    if (is_numeric($search)) {
        $sql .= " AND STUDENT_ID LIKE '%$search%'";
    } else {
        $sql .= " AND (FIRST_NAME LIKE '%$search%' OR LAST_NAME LIKE '%$search%')";
    }
}

// Add gender filter condition
if (!empty($gender_filter)) {
    $sql .= " AND GENDER = '$gender_filter'";
}

// Execute the query
$result = $conn->query($sql);


// Handle DELETE request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    // Sanitize the student ID
    $student_id = $conn->real_escape_string($_POST['student_id']);

    // Prepare the delete statement
    $delete_query = "DELETE FROM student_tbl WHERE STUDENT_ID = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("s", $student_id);

    $response = ["status" => "error", "message" => "Something went wrong!"];

    // Execute the delete query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = ["status" => "success", "message" => "Student deleted successfully!"];
        } else {
            $response = ["status" => "error", "message" => "No student found with the given ID."];
        }
    } else {
        $response = ["status" => "error", "message" => "Error deleting student: " . $stmt->error];
    }

    // Close statement
    $stmt->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle POST requests (Add or Edit)
if ($_SERVER["REQUEST_METHOD"] == "POST" && (!isset($_POST['action']) || $_POST['action'] != 'delete')) {
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $first_name = $conn->real_escape_string($_POST['first_name']);
    $last_name = $conn->real_escape_string($_POST['last_name']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $email = $conn->real_escape_string($_POST['email']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);

    // Check if this is an edit operation
    if (isset($_POST['original_student_id'])) {
        // EDIT operation
        $original_student_id = $conn->real_escape_string($_POST['original_student_id']);
        $response = ["status" => "error", "message" => "Something went wrong!"];

        // Check if new ID is already in use (but only if ID is being changed)
        if ($student_id !== $original_student_id) {
            $checkDuplicateQuery = "SELECT STUDENT_ID FROM student_tbl WHERE STUDENT_ID = ?";
            $checkDuplicateStmt = $conn->prepare($checkDuplicateQuery);
            $checkDuplicateStmt->bind_param("s", $student_id);
            $checkDuplicateStmt->execute();
            $duplicateResult = $checkDuplicateStmt->get_result();

            if ($duplicateResult->num_rows > 0) {
                echo json_encode(["status" => "error", "message" => "Student ID $student_id is already in use."]);
                $checkDuplicateStmt->close();
                $conn->close();
                exit;
            }
            $checkDuplicateStmt->close();
        }

        // Update Query
        $query = "UPDATE student_tbl SET STUDENT_ID=?, FIRST_NAME=?, LAST_NAME=?, GENDER=?, EMAIL=?, CONTACT=?, ADDRESS=? WHERE STUDENT_ID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssss", $student_id, $first_name, $last_name, $gender, $email, $contact, $address, $original_student_id);

        if ($stmt->execute()) {
            $response = ["status" => "success", "message" => "Student updated successfully!"];
        } else {
            $response = ["status" => "error", "message" => "Error updating student: " . $stmt->error];
        }

        $stmt->close();
        $conn->close();
        echo json_encode($response);
        exit;
    } else {
        // ADD operation
        // Check if the student ID already exists
        $check_sql = "SELECT STUDENT_ID FROM student_tbl WHERE STUDENT_ID = '$student_id'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            echo "error: Student ID already exists!";
        } else {
            // Insert new student record
            $insert_sql = "INSERT INTO student_tbl (STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS) 
                    VALUES ('$student_id', '$first_name', '$last_name', '$gender', '$email', '$contact', '$address')";

            if ($conn->query($insert_sql) === TRUE) {
                echo "success";
            } else {
                echo "Error: " . $insert_sql . "<br>" . $conn->error;
            }
        }
        $conn->close();
        exit;
    }
}

// Execute the SQL query for displaying students
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

                    <div class="search-gender">
                        <form method="GET">
                            <select name="gender" onchange="this.form.submit()"> <!-- No need for JS -->
                                <option value="">All Genders</option>
                                <option value="Male" <?= ($gender_filter == "Male") ? "selected" : "" ?>>Male</option>
                                <option value="Female" <?= ($gender_filter == "Female") ? "selected" : "" ?>>Female</option>
                            </select>
                        </form>
                    </div>

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
 
                                          <a href='#' onclick='openEditModal(\"" . htmlspecialchars($row['STUDENT_ID'], ENT_QUOTES, 'UTF-8') . "\", \"" . htmlspecialchars($row['FIRST_NAME'], ENT_QUOTES, 'UTF-8') . "\", \"" . htmlspecialchars($row['LAST_NAME'], ENT_QUOTES, 'UTF-8') . "\", \"" . htmlspecialchars($row['GENDER'], ENT_QUOTES, 'UTF-8') . "\", \"" . htmlspecialchars($row['EMAIL'], ENT_QUOTES, 'UTF-8') . "\", \"" . htmlspecialchars($row['CONTACT'], ENT_QUOTES, 'UTF-8') . "\", \"" . htmlspecialchars($row['ADDRESS'], ENT_QUOTES, 'UTF-8') . "\")' class='edit'>
                                                 <span class='tooltip'>
                                                 <i class='fa-sharp fa-solid fa-pen icon-background'></i>
                                               <span class='tooltiptext'>Edit Student</span>
                                                </span>
                                                 </a>

                                               <a href='#' onclick='confirmDelete(\"" . htmlspecialchars($row['STUDENT_ID'], ENT_QUOTES, 'UTF-8') . "\")' class='delete'>
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




    <div id="editModal" class="edit-modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Student</h2>

            <form id="editStudentForm" method="POST" action="">
                <!-- Hidden field to store the original Student ID -->
                <input type="hidden" id="originalStudentId" name="original_student_id">

                <div class="id-gender-container">
                    <div class="input-group">
                        <label for="editStudentId">Student ID:</label>
                        <input type="text" id="editStudentId" name="student_id" required>
                    </div>
                    <div class="input-group">
                        <label for="editGender">Gender:</label>
                        <select id="editGender" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="name-container">
                    <div class="input-group">
                        <label for="editFirstName">First Name:</label>
                        <input type="text" id="editFirstName" name="first_name" required>
                    </div>
                    <div class="input-group">
                        <label for="editLastName">Last Name:</label>
                        <input type="text" id="editLastName" name="last_name" required>
                    </div>
                </div>

                <div class="contact-container">
                    <div class="input-group">
                        <label for="editEmail">Email:</label>
                        <input type="email" id="editEmail" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="editContact">Contact:</label>
                        <input type="text" id="editContact" name="contact" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="editAddress">Address:</label>
                    <input type="text" id="editAddress" name="address" required>
                </div>

                <button type="submit" class="edit-button">Save</button>
            </form>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            // Open Add Student Modal
            function openModal() {
                document.getElementById('addStudentModal').style.display = 'flex';
            }

            // Close Add Student Modal
            function closeModal() {
                document.getElementById('addStudentModal').style.display = 'none';
            }

            // Confirm Delete Function
            function confirmDelete(studentId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this deletion!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Perform delete operation
                        deleteStudent(studentId);
                    }
                });
            }

            // Delete Student Function
            function deleteStudent(studentId) {
                $.ajax({
                    url: '', // Current page
                    method: 'POST',
                    data: {
                        action: 'delete',
                        student_id: studentId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            // Show success sweet alert
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // Remove the row from the table
                            $('#row_' + studentId).fadeOut(500, function() {
                                $(this).remove();
                            });
                        } else {
                            // Show error sweet alert
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function() {
                        // Handle network or other errors
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong with the deletion.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }

            // Helper functions for SweetAlert
            function showSuccessAlert(title, text, rowId = null) {
                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: text,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    if (rowId) {
                        // Fade out the row and then remove it
                        $(rowId).fadeOut(500, function() {
                            $(this).remove();
                            location.reload(); // Optional: reload the page
                        });
                    } else {
                        location.reload(); // Reload the page after success
                    }
                });
            }

            function showErrorAlert(title, text) {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: text,
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            // Add and Edit Student Form Handler
            function submitStudentForm(form, formType) {
                let formData = new FormData(form[0]);
                let rowId = null;

                // For edit, capture the row ID to fade out
                if (formType === 'edit') {
                    rowId = '#row_' + formData.get('original_student_id');
                }

                // jQuery ajax settings
                let ajaxSettings = {
                    url: 'student.php',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: formData,
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                };

                // Handle different response formats based on form type
                if (formType === 'add') {
                    ajaxSettings.success = function(response) {
                        if (response.trim() === 'success') {
                            showSuccessAlert('Student Added!', 'The student has been successfully added.');
                        } else {
                            showErrorAlert('Error!', response);
                        }
                    };
                } else { // edit form
                    ajaxSettings.dataType = 'json';
                    ajaxSettings.success = function(data) {
                        if (data.status === 'success') {
                            showSuccessAlert('Updated!', data.message, rowId);
                        } else {
                            showErrorAlert('Oops...', data.message);
                        }
                    };
                }

                // Execute the AJAX request
                $.ajax(ajaxSettings);
            }

            // Form submission event handlers
            $('#addStudentForm').submit(function(event) {
                event.preventDefault();
                submitStudentForm($(this), 'add');
            });

            $('#editStudentForm').submit(function(event) {
                event.preventDefault();
                submitStudentForm($(this), 'edit');
            });

            // Expose functions globally if needed
            window.openModal = openModal;
            window.closeModal = closeModal;
            window.confirmDelete = confirmDelete;
        });
    </script>


    <script src="JS/students.js"></script>






</body>

</html>