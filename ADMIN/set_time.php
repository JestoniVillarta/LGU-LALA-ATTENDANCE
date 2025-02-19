<?php
include '../CONNECTION/connection.php';

// Fetch existing attendance settings
$query = "SELECT * FROM attendance_settings_tbl WHERE id = 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $start_time = date("H:i", strtotime($row['MORNING_TIME_IN']));
    $start_time_end = date("H:i", strtotime($row['TIME_IN_END']));
    $morning_time_out = date("H:i", strtotime($row['MORNING_TIME_OUT']));
    $morning_time_out_end = date("H:i", strtotime($row['TIME_OUT_END']));
    $afternoon_time_in = date("H:i", strtotime($row['AFTERNOON_TIME_IN']));
    $afternoon_time_in_end = date("H:i", strtotime($row['AFTERNOON_TIME_IN_END']));
    $afternoon_time_out = date("H:i", strtotime($row['AFTERNOON_TIME_OUT']));
    $afternoon_time_out_end = date("H:i", strtotime($row['AFTERNOON_TIME_OUT_END']));
} else {
    // Set default empty values if no records exist
    $start_time = $start_time_end = $morning_time_out = $morning_time_out_end = "";
    $afternoon_time_in = $afternoon_time_in_end = $afternoon_time_out = $afternoon_time_out_end = "";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_time = $_POST['start_time'];
    $start_time_end = $_POST['start_time_end'];
    $morning_time_out = $_POST['morning_time_out'];
    $morning_time_out_end = $_POST['morning_time_out_end'];
    $afternoon_time_in = $_POST['afternoon_time_in'];
    $afternoon_time_in_end = $_POST['afternoon_time_in_end'];
    $afternoon_time_out = $_POST['afternoon_time_out'];
    $afternoon_time_out_end = $_POST['afternoon_time_out_end'];

    // Validate the input fields
    if (empty($start_time) || empty($start_time_end) || empty($morning_time_out) || empty($morning_time_out_end) || empty($afternoon_time_in) || empty($afternoon_time_in_end) || empty($afternoon_time_out) || empty($afternoon_time_out_end)) {
        echo "All fields are required.";
        exit();
    }

    // Convert time to 12-hour format
    $start_time_12hr = date("h:i A", strtotime($start_time));
    $start_time_end_12hr = date("h:i A", strtotime($start_time_end));
    $morning_time_out_12hr = date("h:i A", strtotime($morning_time_out));
    $morning_time_out_end_12hr = date("h:i A", strtotime($morning_time_out_end));
    $afternoon_time_in_12hr = date("h:i A", strtotime($afternoon_time_in));
    $afternoon_time_in_end_12hr = date("h:i A", strtotime($afternoon_time_in_end));
    $afternoon_time_out_12hr = date("h:i A", strtotime($afternoon_time_out));
    $afternoon_time_out_end_12hr = date("h:i A", strtotime($afternoon_time_out_end));

    // Check the time sequence
    if (strtotime($start_time) >= strtotime($start_time_end) || strtotime($morning_time_out) >= strtotime($morning_time_out_end) || strtotime($afternoon_time_in) >= strtotime($afternoon_time_in_end) || strtotime($afternoon_time_out) >= strtotime($afternoon_time_out_end)) {
     
        exit();
    }

    // Update the attendance settings
    if ($result->num_rows > 0) {
        $query = "UPDATE attendance_settings_tbl SET MORNING_TIME_IN = ?, TIME_IN_END = ?, MORNING_TIME_OUT = ?, TIME_OUT_END = ?, AFTERNOON_TIME_IN = ?, AFTERNOON_TIME_IN_END = ?, AFTERNOON_TIME_OUT = ?, AFTERNOON_TIME_OUT_END = ? WHERE id = 1";
    } else {
        $query = "INSERT INTO attendance_settings_tbl (MORNING_TIME_IN, TIME_IN_END, MORNING_TIME_OUT, TIME_OUT_END, AFTERNOON_TIME_IN, AFTERNOON_TIME_IN_END, AFTERNOON_TIME_OUT, AFTERNOON_TIME_OUT_END) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssss', $start_time_12hr, $start_time_end_12hr, $morning_time_out_12hr, $morning_time_out_end_12hr, $afternoon_time_in_12hr, $afternoon_time_in_end_12hr, $afternoon_time_out_12hr, $afternoon_time_out_end_12hr);


    $stmt->execute();
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Set Attendance Time</title>
    <link rel="stylesheet" href="CSS/set_time.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>


<body>

    <div class="container">


        <div class="nav">
            <?php include 'sidenav.php'; ?>
        </div>


        <div class="content-container">

            <div class="header-container">
                <h3>Set Attendance Time</h3>
            </div>

            <div class="form_wrapper">
                <form action="" method="post" onsubmit="showModal(event)">

                    <div class="time-container">

                        <div class="time-group">
                            <label for="start_time">Morning Time In:</label>
                            <input type="time" id="start_time" name="start_time" value="<?php echo $start_time; ?>" required>
                            <label for="start_time_end" class="end-label">to</label>
                            <input type="time" id="start_time_end" name="start_time_end" value="<?php echo $start_time_end; ?>" required>
                        </div>

                        <div class="time-group">
                            <label for="morning_time_out">Morning Time Out:</label>
                            <input type="time" id="morning_time_out" name="morning_time_out" value="<?php echo $morning_time_out; ?>" required>
                            <label for="morning_time_out_end" class="end-label">to</label>
                            <input type="time" id="morning_time_out_end" name="morning_time_out_end" value="<?php echo $morning_time_out_end; ?>" required>
                        </div>

                        <br>
                        <br>
                        <br>

                        <div class="time-group">
                            <label for="afternoon_time_in">Afternoon Time In:</label>
                            <input type="time" id="afternoon_time_in" name="afternoon_time_in" value="<?php echo $afternoon_time_in; ?>" required>
                            <label for="afternoon_time_in_end" class="end-label">to</label>
                            <input type="time" id="afternoon_time_in_end" name="afternoon_time_in_end" value="<?php echo $afternoon_time_in_end; ?>" required>
                        </div>

                        <div class="time-group">
                            <label for="afternoon_time_out">Afternoon Time Out:</label>
                            <input type="time" id="afternoon_time_out" name="afternoon_time_out" value="<?php echo $afternoon_time_out; ?>" required>
                            <label for="afternoon_time_out_end" class="end-label">to</label>
                            <input type="time" id="afternoon_time_out_end" name="afternoon_time_out_end" value="<?php echo $afternoon_time_out_end; ?>" required>
                        </div>

                        <button type="submit" class="set_time">Set Time</button>

                    </div>


                    <!-- Add this HTML right before the closing </body> tag -->
<div id="successModal" class="modal">
    <div class="modal-content success">
        <div class="modal-header">
            <i class="fas fa-check-circle"></i>
            <h2>Success!</h2>
        </div>
        <div class="modal-body">
            <p>Time settings have been successfully updated.</p>
        </div>
        <div class="modal-footer">
            <button onclick="handleSuccessClose()" class="modal-btn">OK</button>
        </div>
    </div>
</div>

<div id="invalidModal" class="modal">
    <div class="modal-content invalid">
        <div class="modal-header">
            <i class="fas fa-exclamation-circle"></i>
            <h2>Invalid Time Settings</h2>
        </div>
        <div class="modal-body">
            <p>Please ensure that end times are later than start times for each period.</p>
        </div>
        <div class="modal-footer">
            <button onclick="handleInvalidClose()" class="modal-btn">OK</button>
        </div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    position: relative;
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    width: 400px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-header {
    text-align: center;
    margin-bottom: 20px;
}

.modal-header i {
    font-size: 48px;
    margin-bottom: 10px;
}

.success .modal-header i {
    color: #28a745;
}

.invalid .modal-header i {
    color: #dc3545;
}

.modal-header h2 {
    margin: 0;
    color: #333;
    font-size: 24px;
}

.modal-body {
    text-align: center;
    margin-bottom: 20px;
}

.modal-body p {
    margin: 0;
    color: #666;
    font-size: 16px;
}

.modal-footer {
    text-align: center;
}

.modal-btn {
    padding: 10px 30px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.modal-btn:hover {
    background-color: #0056b3;
}

.success .modal-btn {
    background-color: #28a745;
}

.success .modal-btn:hover {
    background-color: #218838;
}

.invalid .modal-btn {
    background-color: #dc3545;
}

.invalid .modal-btn:hover {
    background-color: #c82333;
}
</style>

<script>
function showModal(event) {
    event.preventDefault();
    
    // Get all the time inputs
    const startTime = document.getElementById('start_time').value;
    const startTimeEnd = document.getElementById('start_time_end').value;
    const morningTimeOut = document.getElementById('morning_time_out').value;
    const morningTimeOutEnd = document.getElementById('morning_time_out_end').value;
    const afternoonTimeIn = document.getElementById('afternoon_time_in').value;
    const afternoonTimeInEnd = document.getElementById('afternoon_time_in_end').value;
    const afternoonTimeOut = document.getElementById('afternoon_time_out').value;
    const afternoonTimeOutEnd = document.getElementById('afternoon_time_out_end').value;

    // Validate time sequence
    if (startTime >= startTimeEnd || 
        morningTimeOut >= morningTimeOutEnd || 
        afternoonTimeIn >= afternoonTimeInEnd || 
        afternoonTimeOut >= afternoonTimeOutEnd) {
        document.getElementById('invalidModal').style.display = 'block';
        return;
    }

    // If validation passes, submit the form
    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Show success modal after successful submission
            document.getElementById('successModal').style.display = 'block';
        } else {
            throw new Error('Network response was not ok');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function handleSuccessClose() {
    // First submit the form traditionally
    document.querySelector('form').submit();
    
    // Then close the modal and redirect
    closeModal('successModal');
    window.location.href = 'set_time.php';
}

function handleInvalidClose() {
    // Close the modal and redirect
    closeModal('invalidModal');
    window.location.href = 'set_time.php';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        window.location.href = 'set_time.php';
    }
}
</script>




</body>

</html>