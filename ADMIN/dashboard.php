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
                <i class="fa-solid fa-users"></i>
                <br><span class="text-blue">450<br>Total Employees</span>
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
