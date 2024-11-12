<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Employee Clock-In System</title>

    

    <link rel="stylesheet" href="./clock_in_design.css" />
  </head>
  <body>
    <header>
    <div class="header">
      <div class="left-area">
        <img src="./logo.png" class="logo" alt="" />

      </div>
      <div id="current-time">XX:XX - XX/XX/XXXX</div>
      <div class="right-area">
        <a href="pos.php">P
          <i class="fa-brands fa-facebook"></i>
        </a>
        <a href="employees.php">E
          <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="items.php"> I
          <i class="fa-brands fa-twitter"></i>
        </a>
        <a href="logout.php"> L
          <i class="fa-brands fa-linkedin"></i>
        </a>
      </div>
    </div>
  </header>
    <div class="nav">
      <div class="tab active">Team Members</div>
      <div class="tab">Rota List</div>
    </div>

    <div class="clock-in-container"></div>
    
    <div id="loginModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="hideLoginForm()">&times;</span>
        <h2>Log In</h2>
        <div>
          <label id="username">Name</label>
        </div>
        <div class="form-input">
          <label for="pin">Password</label>
          <input type="text" id="pin" name="pin" oninput="validateNumberInput(event)" />
        </div>
        <div class="keypad">
          <button class="pin-button" onclick="enterPin('1')">1</button>
          <button class="pin-button" onclick="enterPin('2')">2</button>
          <button class="pin-button" onclick="enterPin('3')">3</button>
          <button class="pin-button" onclick="enterPin('4')">4</button>
          <button class="pin-button" onclick="enterPin('5')">5</button>
          <button class="pin-button" onclick="enterPin('6')">6</button>
          <button class="pin-button" onclick="enterPin('7')">7</button>
          <button class="pin-button" onclick="enterPin('8')">8</button>
          <button class="pin-button" onclick="enterPin('9')">9</button>
          <button class="pin-button" onclick="enterPin('0')">0</button>
        </div>
        <button id="enterButton" onclick="submitLogin()">Enter</button>
      </div>
      <div class="form-input">
          <p class="output-msg"></p>
      </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="clock_in_script.js"></script>
  </body>
</html>
