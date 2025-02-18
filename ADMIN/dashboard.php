<?php

include '../CONNECTION/connection.php';

// Kuhaa ang total number sa estudyante
$sql = "SELECT COUNT(*) as total_students FROM student_tbl";
$result = $conn->query($sql);

// Ikuha ang value
$row = $result->fetch_assoc();
$total_students = $row['total_students'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Dashboard</title>
    <link rel="stylesheet" href="CSS/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body>

    <div class="nav">
        <?php include 'sidenav.php'; ?>
    </div>



    <div class="dashboard-wrapper">
        <h3>Dashboard</h3>
    </div>

    <div class="table-wrapper">
        <div class="main-content">
            <div class="cards">
                <div class="card blue">
                    <br>

                    <span class="text-blue total-count"><?php echo $total_students; ?></span>

                    <br>
                    <br>

                    <span class="text-blue label">Total Students</span>

                    <i class="fa-solid fa-users"></i> <!-- Icon is now separate -->
                </div>


                <div class="card green">
                    <i class="fas fa-check-circle"></i>
                    <br><span class="text-green">300<br>On Time</span>
                </div>
                <div class="card red">
                    <i class="fas fa-clock"></i>
                    <br><span class="text-red">100<br>Late</span>
                </div>
                <div class="card orange">
                    <i class="fas fa-user-times"></i>
                    <br><span class="text-orange">50<br>Absent</span>
                </div>
            </div>
        </div>
    </div>

</body>

</html>