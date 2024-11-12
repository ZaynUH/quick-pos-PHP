<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "dessertshop database"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select all items from the database
$sql = "SELECT * FROM items";

// Execute the query
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Fetch all rows and store them in an array
    $items = array();
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    // Output the items as JSON
    echo json_encode($items);
} else {
    // Output an empty array if no results found
    echo json_encode(array());
}

// Close connection
$conn->close();
?>
