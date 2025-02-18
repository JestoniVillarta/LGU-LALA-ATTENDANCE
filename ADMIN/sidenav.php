<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="CSS/sidenav.css">
    <script src="https://kit.fontawesome.com/YOUR_KIT_CODE.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <button id="toggleSidebar"><i class="fas fa-bars"></i></button>
        
        <!-- Admin Panel Title with Icon -->
        <h2 class="sidebar-header">
            <i class="fas fa-user-cog admin-icon"></i> 
            <p class="sidebar-text">Admin Panel</p>
        </h2>

        <ul>
            <li><button data-url="dashboard.php"><i class="fas fa-home"></i> <p class="sidebar-text">Dashboard</p></button></li>
            <li><button data-url="attendance.php"><i class="fas fa-user-check"></i> <p class="sidebar-text">Attendance</p></button></li>
            <li><button data-url="student.php"><i class="fas fa-user-graduate"></i> <p class="sidebar-text">Student</p></button></li>
            <li><button data-url="set_time.php"><i class="fas fa-clock"></i> <p class="sidebar-text">Setup Time</p></button></li>
            <li><button id="logout"><i class="fas fa-sign-out-alt"></i> <p class="sidebar-text">Logout</p></button></li>
        </ul>
    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="logout_modal">
        <div class="logout_modal-content">
            <p>Are you sure you want to logout?</p>
            <button id="confirmLogout">Yes</button>
            <button id="cancelLogout">Cancel</button>
        </div>
    </div>

    <script src="JS/sidenav.js"></script>

</body>
</html>
