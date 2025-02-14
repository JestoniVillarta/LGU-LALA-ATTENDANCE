<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="CSS/sidenav.css">
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><button id="dashboard" data-url="dashboard.php">Dashboard</button></li>
            <li><button id="attendance" data-url="attendance.php">Attendance</button></li>
            <li><button id="student" data-url="student.php">Student</button></li>
            <li><button id="set_time" data-url="set_time.php">Setup Time</button></li>
            <li><button id="logout" data-url="../logout.php">Logout</button></li>
        </ul>
    </div>


</body>


<script src="JS/sidenav.js"></script>

</html>



