<?php
include '../CONNECTION/connection.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$sql = "SELECT STUDENT_ID, FIRST_NAME, LAST_NAME, GENDER, EMAIL, CONTACT, ADDRESS FROM student_tbl";

if (!empty($search)) {
    $sql .= " WHERE STUDENT_ID LIKE '%$search%' 
              OR FIRST_NAME LIKE '%$search%' 
              OR LAST_NAME LIKE '%$search%'";
}

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
                <a href="add.php" class="add-btn"><i class="fa-solid fa-user-plus"></i> Add Student</a>
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
                data: { id: studentId },
                success: function(response) {
                    swal("Student has been deleted!", {
                        icon: "success",
                    }).then(() => {
                        $("#row_" + studentId).fadeOut(500, function() { $(this).remove(); });
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
