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
    <link rel="stylesheet" href="CSS/sidenav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
</head>
<body>

    <div class="nav">
        <?php include 'sidenav.php'; ?>
    </div>

    <div class="table-button-wrapper">
        <h3>Student List</h3>
        <div class="button-search-group">
            <div class="search-container">
                <form method="GET">
                    <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">🔍</button>
                </form>
            </div>
            <a href="add.php" class="add-btn">Add Student</a>
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
                            echo "<tr>
                                <td>" . htmlspecialchars($row["STUDENT_ID"]) . "</td>
                                <td>" . htmlspecialchars($row["FIRST_NAME"]) . "</td>
                                <td>" . htmlspecialchars($row["LAST_NAME"]) . "</td>
                                <td>" . htmlspecialchars($row["GENDER"]) . "</td>
                                <td>" . htmlspecialchars($row["EMAIL"]) . "</td>
                                <td>" . htmlspecialchars($row["CONTACT"]) . "</td>
                                <td>" . htmlspecialchars($row["ADDRESS"]) . "</td>
                                <td>
                <a href='edit_students.php?id=" . $row["STUDENT_ID"] . "' class='edit-btn'>Edit</a>
                <a href='delete_students.php?id=" . $row["STUDENT_ID"] . "' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a>
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

</body>
</html>
