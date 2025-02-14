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
                <i class="fas fa-users"></i> 
                <br>450<br>Total Employees
            </div>
            <div class="card green">
                <i class="fas fa-check-circle"></i> 
                <br>300<br>On Time
            </div>
            <div class="card red">
                <i class="fas fa-clock"></i> 
                <br>100<br>Late
            </div>
            <div class="card orange">
                <i class="fas fa-user-times"></i> 
                <br>50<br>Absent
            </div>
        </div>
    </div>
</div>

</body>
</html>
