<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "dessertshop database"; // Name of database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get user ID and clock-in time from the POST request
    $userId = $_POST['userId'];
    $time = $_POST['time'];

    // Check if the user has already clocked in
$stmt = $conn->prepare("SELECT EmployeeClockIn FROM employees WHERE EmployeeID = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$stmt->bind_result($clockInTime);
$stmt->fetch();

if ($clockInTime != NULL) {

    // Convert clock-in and clock-out times to DateTime objects
    $clockInDateTime = new DateTime($clockInTime);
    $clockOutDateTime = new DateTime($time);
    
    // Calculate the difference between clock-in and clock-out times in seconds
    $timeDiffSeconds = strtotime($time) - strtotime($clockInTime);

    // Convert the time difference from seconds to hours
    $hoursWorked = $timeDiffSeconds / 3600; // 3600 seconds in an hour

    $stmt->close(); // Close the previous statement

    // Store hours worked in the database
    $stmt = $conn->prepare("UPDATE employees SET EmployeeHoursWorked = EmployeeHoursWorked + ?, EmployeeClockIn = NULL WHERE EmployeeID = ?");
    $stmt->bind_param("ss", $hoursWorked, $userId);
    $stmt->execute();

    echo "Clock-out successful. Hours worked: " . $hoursWorked;
    echo json_encode(array('success' => true, 'clockedIn' => true));
} 
else
{
    $stmt->close(); // Close the previous statement
    // User has not clocked in yet
    // Prepare and execute SQL query to insert clock-in time into the database
    $sql = "UPDATE employees SET EmployeeClockIn = ? WHERE EmployeeID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $time, $userId);

    if ($stmt->execute()) {
        // Clock-in time stored successfully
        echo json_encode(array('success' => true));
        echo json_encode(array('success' => true, 'clockedIn' => false));
    } else {
        // Error occurred while storing clock-in time
        echo json_encode(array('success' => false, 'error' => 'Failed to store clock-in time'));
    }   
}

// Close the database connection
$stmt->close();
$conn->close();

} else {
    // Request method is not POST
    echo json_encode(array('success' => false, 'error' => 'Invalid request method'));
}
?>
