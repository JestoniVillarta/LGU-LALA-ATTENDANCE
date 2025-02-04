<?php

include 'CONNECTION/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect the form data
  $username = $_POST['username'];
  $password = $_POST['password'];

  // SQL query to check if the user exists
  $sql = "SELECT ID, ADMIN_USERNAME, ADMIN_PASSWORD FROM admin_tbl WHERE ADMIN_USERNAME = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  // Check if a user was found
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Compare the entered password with the stored password (use password_verify() for hashed passwords)
    if ($user['ADMIN_PASSWORD'] === $password) {  // In a real app, use password_verify($password, $user['ADMIN_PASSWORD'])
      // Start a session and store user information if needed
      session_start();
      $_SESSION['user_id'] = $user['ID'];
      $_SESSION['username'] = $user['ADMIN_USERNAME'];

      // Redirect to the admin dashboard
      header("Location: ADMIN/dashboard.php");
      exit();  // Make sure to exit after the redirect to stop further script execution
    } else {
      echo "Invalid password!";
    }
  } else {
    echo "No user found with that username!";
  }

  $stmt->close();
  $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="CSS/login.css">
</head>
<body>
  
  <form action="login.php" method="POST">
    <label for="username">Username:</label>
    
    <input type="text" id="ADMIN_USERNAME" name="username" required>
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="ADMIN_PASSWORD" name="password" required>
    <br><br>
    <button type="submit">Login</button>
  </form>
</body>
</html>
