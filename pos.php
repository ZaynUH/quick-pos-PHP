<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root"; // Default username for XAMPP MySQL
$password = ""; // Default password for XAMPP MySQL
$dbname = "dessertshop database"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tabs from the database (Replace this with your actual database query)
$sql = "SELECT DISTINCT ItemTab FROM items"; // Assuming your table is named 'items' and has a column 'category'
$result = mysqli_query($conn, $sql);
$tabs = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tabs[] = $row['ItemTab'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles2.css">

  <title>Point of Sale System</title>
</head>
<body>
  <header>
    <div class="header">
      <div class="left-area">
        <img src="./logo.png" class="logo" alt="" />


      </div>
      <div id="current-time">XX:XX - XX/XX/XXXX</div>
      <div class="right-area">
        <a href="clock_in.php">C
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

  <div id="main-body-area">
    <div id="pos-container">
      <section id="category-section">
        <?php foreach ($tabs as $tab): ?>
            <button class="category-button" data-category="<?php echo $tab; ?>"><?php echo ucfirst($tab); ?></button>
        <?php endforeach; ?>
      </section>
  
      <section id="products-section">
        <h2>Products</h2>
        <div id="product-list">
            
        </div>
      </section>
    </div>
  
    <div id="cart-container">
      <section id="cart-section">
        <h2>Shopping Cart</h2>
        <ul id="cart-list"></ul>
        <p>Total: $<span id="total">0.00</span></p>
        <p>Total CO2 Emission: <span id="total-co2">0.00</span> kg</p>
        <button id="pay-button" onclick="handlePayment()">Pay Now</button>
      </section>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="app.js"></script>
</body>
</html>
