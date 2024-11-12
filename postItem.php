<?php
// Check if form is submitted
if (isset($_POST['submit'])) { 
    // Database connection parameters
    $servername = "localhost";
    $username = "root"; // Default username for XAMPP MySQL
    $password = ""; // Default password for XAMPP MySQL
    $dbname = "dessertshop database"; // Name of database

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $itemId = $_POST['itemId'];
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $itemEmission = $_POST['itemEmission'];
    $itemTab = $_POST['itemTab'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO items (itemId, itemName, itemPrice, itemEmission, itemTab) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssss", $itemId, $itemName, $itemPrice, $itemEmission, $itemTab);

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        echo "<script>alert('Item added successfully');</script>"; 
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>"; 
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
