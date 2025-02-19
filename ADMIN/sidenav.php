<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

    <!-- STYLESHEET -->
    <link rel="stylesheet" href="CSS/sidenav.css" />

    <title>Sidebar</title>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <!-- Menu Button -->
            <div class="menu-btn">
                <i class="ph-bold ph-caret-left"></i>
            </div>

            <!-- User Section -->
            <div class="head">
                <div class="user-img">
                    <img src="../img/tonix.jpg" alt="User Image" />
                </div>
                <div class="user-details">
                    <h6 class="title">Web Developer</h6>
                    <h6 class="name">Jestoni</h6>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="nav">
                <div class="menu">
                    <ul>
                        <li>
                            <a href="dashboard.php">
                                <i class="icon ph-bold ph-house-simple"></i>
                                <span class="text">Dashboard</span>
                            </a>
                        </li>

                        <li>
                            <a href="attendance.php">
                            <i class="icon fa-solid fa-user-check"></i>
                                <span class="text">Attendance</span>
                            </a>
                        </li>

                        <!-- Students Section with Submenu -->
                        <li class="has-submenu">
                            <a href="student.php" class="menu-link">
                                <i class="icon fa-regular fa-address-book"></i>
                                <span class="text">Students</span>
                                <i class="arrow ph-bold ph-caret-down"></i> <!-- Arrow moved outside -->
                            </a>

                            <ul class="submenu">
                                <li>
                                    <a href="add.php">
                                        <i class="icon ph-bold ph-user-plus"></i>
                                        <span class="text">Add Student</span>
                                    </a>
                                </li>
                            </ul>
                        </li>



                        <li>
                            <a href="set_time.php">
                                <i class="icon ph-bold ph-calendar-blank"></i>
                                <span class="text">Set Time</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Settings Section -->
                <div class="menu">
                    <h6 class="title">Settings</h6>
                    <ul>
                        <li>
                            <a href="#">
                                <i class="icon ph-bold ph-gear"></i>
                                <span class="text">Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Account Section -->
            <div class="menu">
                <h6 class="title">Account</h6>
                <ul>
                    <li>
                        <a href="../logout.php">
                            <i class="icon ph-bold ph-sign-out"></i>
                            <span class="text">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.js"
        integrity="sha512-8Z5++K1rB3U+USaLKG6oO8uWWBhdYsM3hmdirnOEWp8h2B1aOikj5zBzlXs8QOrvY9OxEnD2QDkbSKKpfqcIWw=="
        crossorigin="anonymous"></script>


    <script src="JS/sidenav.js"></script>
</body>

</html>