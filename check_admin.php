<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Retrieve the session username and password
    $sessionUsername = $_SESSION['username'];
    $sessionPassword = $_SESSION['password'];

    // Get the entered username and password from the AJAX request
    $enteredUsername = $_POST['username'];
    $enteredPassword = $_POST['password'];

    // Check if the entered credentials match the session credentials
    if ($enteredUsername === $sessionUsername && $enteredPassword === $sessionPassword) {
        // If credentials are correct, send a success response
        echo json_encode(["success" => true]);
    } else {
        // If credentials are incorrect, send an error response
        echo json_encode(["success" => false, "message" => "Invalid username or password"]);
    }
} else {
    // If the user is not logged in, send an error response
    echo json_encode(["success" => false, "message" => "User not logged in"]);
}

// Set Content-Type header
header('Content-Type: application/json');
?>
