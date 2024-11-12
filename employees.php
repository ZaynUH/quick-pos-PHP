<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    if (isset($_POST['additem'])) {
        // Retrieve form data
        $employeeId = $_POST['employeeId'];
        $employeeName = $_POST['employeeName'];
        $employeePassword = $_POST['employeePassword'];

         // Check if the item ID already exists in the database
         $checkQuery = "SELECT * FROM employees WHERE EmployeeID = '$employeeId'";
         $checkResult = mysqli_query($conn, $checkQuery);
 
         if (mysqli_num_rows($checkResult) > 0) 
         {
             echo "<script>alert('Employee ID already exists. Please choose a different ID.')</script>";
         } 
         else
         {
            // Prepare and bind SQL statement
            $stmt = $conn->prepare("INSERT INTO employees (EmployeeID, EmployeeName, EmployeePassword) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $employeeId, $employeeName, $employeePassword);

            // Execute the statement
            if ($stmt->execute()) {
                echo "<script>alert('Employee added successfully')</script>";
            } else {
              echo "<script>alert('Error')</script>";
            }
        }
    } elseif (isset($_POST['deleteitem'])) {
        $employeeId = $_POST['employeeId'];

        // Prepare and bind SQL statement to delete employee by ID
        $stmt = $conn->prepare("DELETE FROM employees WHERE EmployeeID = ?");
        $stmt->bind_param("s", $employeeId);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Employee deleted successfully')</script>";
        } else {
            echo "<script>alert('Error')</script>";
        }
    } elseif (isset($_POST['edititem'])) {
        // Retrieve form data
        $employeeId = $_POST['employeeId'];
        $employeeName = $_POST['employeeName'];
        $employeePassword = $_POST['employeePassword'];
        $employeeHoursWorked = $_POST['employeeHoursWorked'];

        // Prepare and bind SQL statement to update employee
        $stmt = $conn->prepare("UPDATE employees SET  EmployeeName = ?, EmployeePassword = ?, EmployeeHoursWorked = ? WHERE EmployeeID = ?");
        $stmt->bind_param("ssss", $employeeName, $employeePassword,  $employeeHoursWorked, $employeeId);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Employee updated successfully')</script>";
        } else {
            echo "<script>alert('Error')</script>";
        }
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Employee List</h1>
    <form method="post" action="" id="itemForm">
        <label for="ID">ID:</label>
        <input type="text" id="ID" name="employeeId" required>
        <label for="employeeName">Name:</label>
        <input type="text" id="employeeName" name="employeeName" required>
        <label for="employeePassword">Password:</label>
        <input type="text" id="employeePassword" name="employeePassword" required>

        <button type="submit" id="submitButton" name="additem" value="submit">Submit</button>
    </form>

    <button type="button" id="addItembtn">Add Employee</button>
    <table id="itemTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Password</th>
                <th>Hours Worked</th>
                <th>ClockedIn</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Employees will be dynamically added here -->
        </tbody>
    </table>
    <!-- Link to items.php -->
    <a href="pos.php">Go to POS Page</a>
    
    <script type="text/javascript">

        // Check if the page is being refreshed
        const navigationEntries = window.performance.getEntriesByType('navigation');
        if (navigationEntries.length && navigationEntries[0].type === 'reload')
        {
            // Redirect to pos.php
            window.location.href = 'pos.php';
        }
        // Function to fetch employees from the server and update the table
        function fetchEmployeesAndUpdateTable() {
            // Make an AJAX request to fetch employees from the server
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_all_employees.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse the JSON response
                    var employees = JSON.parse(xhr.responseText);

                    // Get the tbody element of the table
                    var tbody = document.getElementById("itemTable").getElementsByTagName("tbody")[0];

                    // Clear the existing rows in the tbody
                    tbody.innerHTML = "";

                    // Iterate over the fetched employees and insert them into the table
                    employees.forEach(function (employee) {
                        var row = tbody.insertRow();
                        row.setAttribute("data-item-id", employee.EmployeeID);
                        row.innerHTML = "<td>" + employee.EmployeeID + "</td><td>" + employee.EmployeeName + "</td><td>" + employee.EmployeePassword + "</td><td>" + employee.EmployeeHoursWorked + "</td><td>" + employee.EmployeeClockIn + "</td><td><button onclick='deleteItem(" + employee.EmployeeID + ")'>Delete</button><button onclick='editItem(" + employee.EmployeeID + ")'>Edit</button></td>";
                    });
                }
            };
            xhr.send();
        }

        // Call the function when the window has loaded
        window.onload = fetchEmployeesAndUpdateTable();

        // Function to delete an employee
        function deleteItem(employeeId) {
            if (confirm("Are you sure you want to delete this employee?")) {
                var form = document.createElement("form");
                form.setAttribute("method", "post");
                form.setAttribute("action", "");
                form.style.display = "none";

                var employeeIdField = document.createElement("input");
                employeeIdField.setAttribute("type", "hidden");
                employeeIdField.setAttribute("name", "employeeId");
                employeeIdField.setAttribute("value", employeeId);

                var deleteField = document.createElement("input");
                deleteField.setAttribute("type", "hidden");
                deleteField.setAttribute("name", "deleteitem");
                deleteField.setAttribute("value", "delete");

                form.appendChild(employeeIdField);
                form.appendChild(deleteField);

                document.body.appendChild(form);
                form.submit();
            }
        }

        // Function to edit an employee
        function editItem(employeeId) {
            // Get the table row corresponding to the employee
            var row = document.querySelector("tr[data-item-id='" + employeeId + "']");
            if (!row) {
                console.error("Employee row not found");
                return;
            }

            // Extract the employee details from the row
            var cells = row.cells;
            var employeeName = cells[1].textContent; // Second cell contains Name
            var employeePassword = cells[2].textContent; // Third cell contains Password

            // Populate the form fields with the employee details
            document.getElementById("ID").value = employeeId;
            document.getElementById("employeeName").value = employeeName;
            document.getElementById("employeePassword").value = employeePassword;

            // Change form action to edit mode
            document.getElementById("itemForm").action = "";
            document.getElementById("submitButton").name = "edititem";
            document.getElementById("submitButton").textContent = "Edit Employee";
        }

        // Function to switch form to add mode
        function switchToAddMode() {
            // Clear form fields
            document.getElementById("itemForm").reset();

            // Change form action to add mode
            document.getElementById("itemForm").action = "";
            document.getElementById("submitButton").name = "additem";
            document.getElementById("submitButton").textContent = "Submit";
        }

        // Event listener for the addItemBtn
        document.getElementById("addItembtn").addEventListener("click", function() {
            switchToAddMode();
        });
    </script>
</body>
</html>

