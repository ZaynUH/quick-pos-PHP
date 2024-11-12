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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="./design.css" />
</head>
<body>
    <div class="header">
      <div class="left-area">
        <img src="./logo.png" class="logo" alt="" />

        <p class="teammember">Team Member</p>
      </div>
      <div id="current-time">XX:XX - XX/XX/XXXX</div>
      <div class="right-area">
        <a href="#">
          <i class="fa-brands fa-facebook"></i>
        </a>
        <a href="clock_in.php">
          <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="items.php">
          <i class="fa-brands fa-twitter"></i>
        </a>
        <a href="logout.php">
          <i class="fa-brands fa-linkedin"></i>
        </a>
      </div>
    </div>
    <div class="main-body-area">
        <div class="tab-buttons">
            <button id="pancakesBtn" onclick="displayItems('pancakes')">Pancakes</button>
            <button id="milkshakesBtn" onclick="displayItems('milkshakes')">Milkshakes</button>
            <button id="crepesBtn" onclick="displayItems('crepes')">Crepes</button>
            <button id="cakesBtn" onclick="displayItems('cakes')">Cakes</button>
            <button id="drinksBtn" onclick="displayItems('drinks')">Drinks</button>
            <div class="items-container">
            <ul id="tabItems"></ul>
        </div>
        </div>
        
        <div class="basket-container">
            <h2>Basket</h2>
            <ul id="basket"></ul>
            <p>Total: $<span id="totalPrice">0.00</span></p>
            <button id="checkout" onclick=checkout()>Checkout</button>
        </div>
    </div>
    
    <script src="script.js"></script>
</body>
</html>
