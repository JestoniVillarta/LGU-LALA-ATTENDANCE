<?php
include '../CONNECTION/connection.php';

// Get search input and prevent SQL injection
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_date = isset($_GET['search_date']) ? $conn->real_escape_string($_GET['search_date']) : date("Y-m-d");

// Base SQL query
$sql = "SELECT STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS FROM student_tbl";

$conditions = [];

// Filter by search term (Student ID, First Name, or Last Name)
if (!empty($search)) {
    if (is_numeric($search)) {
        $conditions[] = "STUDENT_ID = '$search'";
    } else {
        $conditions[] = "(FIRST_NAME LIKE '%$search%' OR LAST_NAME LIKE '%$search%')";
    }
}


$result = $conn->query($sql);

// Debugging: Uncomment this to check the generated SQL query
// echo "Generated Query: " . $sql . "<br>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="CSS/student.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

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
                                <th>Student ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr id='row_" . htmlspecialchars($row['STUDENT_ID'], ENT_QUOTES, 'UTF-8') . "'>
                                    <td>" . htmlspecialchars($row['STUDENT_ID'], ENT_QUOTES, 'UTF-8') . "</td>
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


            <form id="addStudentForm" method="POST" action="add.php">


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

                <button type="submit">Add Student</button>

            </form>
        </div>
    </div>


    <!-- JavaScript for Modal -->
    <script>
        function openModal() {
            document.getElementById('addStudentModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('addStudentModal').style.display = 'none';
        }

        window.onclick = function(event) {
            var modal = document.getElementById('addStudentModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        $(document).ready(function() {
            $('#addStudentForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    url: 'add.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert('Student added successfully!');
                        closeModal();
                        location.reload();
                    },
                    error: function() {
                        alert('Error adding student.');
                    }
                });
            });
        });
    </script>

    <!-- CSS for Modal -->
    <style>
        .modal {
            display: none;
            /* Initially hidden */
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 40%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            position: relative;
        }

        .modal-content h2 {
            margin-bottom: 15px;
            color: #333;
        }

        .name-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            /* Adds spacing between first and last name fields */
        }

        .contact-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            /* Space between fields */
        }

        .input-group {
            display: flex;
            flex-direction: column;
            /* Stack label on top of input */
            width: 48%;
            /* Adjust width to fit side by side */
        }

        .input-group label {
            font-weight: bold;
            margin-bottom: 5px;
            /* Adds space between label and input */
        }

        .id-gender-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            /* Space between fields */
        }

        .modal .modal-content label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
            color: #555;
        }

        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-btn {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .modal-btn:hover {
            background-color: #0056b3;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            cursor: pointer;
            color: #555;
        }

        .close:hover {
            color: #000;
        }
    </style>

    <script>
        function confirmDelete(studentId) {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this student record!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    className: "swal-delete" // Add a custom class

                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: 'delete_students.php',
                            type: 'GET',
                            data: {
                                id: studentId
                            },
                            success: function(response) {
                                swal("Student has been deleted!", {
                                    icon: "success",
                                }).then(() => {
                                    $("#row_" + studentId).fadeOut(500, function() {
                                        $(this).remove();
                                    });
                                });
                            },
                            error: function() {
                                swal("Error!", "Failed to delete student.", "error");
                            }
                        });
                    }
                });
        }
    </script>

</body>

</html>