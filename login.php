<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: clock_in.php"); // Redirect to pos.php if already logged in
    exit();
}

// Handle login form submission
if (isset($_POST['login']))
{
    // Validate user credentials (e.g., against a database)

    $servername = "localhost";
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dbname = "dessertshop database"; // Name of your database
  
    try {
      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);
    
      // Check connection
      if ($conn->connect_error) {
          // Display an alert if connection fails
          echo '<script>alert("Failed to connect to server");</script>';
      }
      else
      {
          $_SESSION['username'] = $username; // Store username in session
          $_SESSION['password'] = $password; // Store username in session
          header("Location: clock_in.php"); // Redirect to pos.php after successful login
          exit();
      }
  } catch (mysqli_sql_exception $e) {
      // Display a generic error message
      echo '<script>alert("Incorret Username or Password");</script>';
  }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./login.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body>
<header>
  <div class="header">
      <div class="left-area">
        <img src="./logo.png" class="logo" alt="" />
      </div>
      <div id="current-time">XX:XX - XX/XX/XXXX</div>
      <div class="right-area">
        <a href="#">
          <i class="fa-brands fa-facebook"></i>
        </a>
        <a href="#">
          <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="#">
          <i class="fa-brands fa-twitter"></i>
        </a>
        <a href="#">
          <i class="fa-brands fa-linkedin"></i>
        </a>
      </div>
    </div>
</header>
<div class="main-body-area">
  <div class="login-area">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form class="login-form" action="login.php" method="post">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required><br><br>
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required><br><br>
      <button type="submit" name="login">Login</button>
    </form>
  </div>

  <div class="right-content">
    <h1 class="main-heading">Quick POS System</h1>
    <img src="https://cdn.pixabay.com/photo/2017/05/04/16/37/meeting-2284501_1280.jpg" alt="" />
  </div>
</div>
<script>

document.addEventListener("DOMContentLoaded", (event) => {
  // Update the current time display on the page

  const currentTimeDisplay = document.getElementById("current-time");
  function updateTime() {
    const now = new Date();
    currentTimeDisplay.textContent =
      now.toTimeString().substring(0, 5) + " - " + now.toLocaleDateString();
  }
  setInterval(updateTime, 1000); // Update the time every second
  updateTime(); // Also update the time immediately
});

</script>
    
</body>
</html>
