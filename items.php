<?php

session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if form is submitted
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
        $itemId = $_POST['itemId'];
        $itemName = $_POST['itemName'];
        $itemPrice = $_POST['itemPrice'];
        $itemTab = $_POST['itemTab'];

         // Check if the item ID already exists in the database
        $checkQuery = "SELECT * FROM items WHERE ItemID = '$itemId'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) 
        {
            echo "<script>alert('Item ID already exists. Please choose a different ID.')</script>";
        } 
        else
        {
            // Prepare and bind SQL statement
            $query = mysqli_query($conn, "INSERT INTO items (ItemID, ItemName, ItemPrice, ItemEmission, ItemTab) VALUES ('$itemId', '$itemName', '$itemPrice', '$itemEmission', '$itemTab')");

            // Execute the statement
            if ($query) {
                echo "<script>alert('Item added successfully')</script>";
            } else 
            {
            echo "<script>alert('Error')</script>";
            }
        }
    } elseif (isset($_POST['deleteitem'])) {
        $itemId = $_POST['itemId'];

        // Prepare and bind SQL statement to delete item by ID
        $query = mysqli_query($conn, "DELETE FROM items WHERE ItemID = '$itemId'");

        // Execute the statement
        if ($query) {
            echo "<script>alert('Item deleted successfully')</script>";
        } else {
            echo "<script>alert('Error')</script>";
        }
    } elseif (isset($_POST['edititem'])) {
        // Retrieve form data
        $itemId = $_POST['itemId'];
        $itemName = $_POST['itemName'];
        $itemPrice = $_POST['itemPrice'];
        $itemEmission = $_POST['itemEmission'];
        $itemTab = $_POST['itemTab'];

        // Prepare and bind SQL statement to update item
        $query = mysqli_query($conn, "UPDATE items SET ItemName = '$itemName', ItemPrice = '$itemPrice', ItemEmission = '$itemEmission', ItemTab = '$itemTab' WHERE ItemID = '$itemId'");

        // Execute the statement
        if ($query) {
            echo "<script>alert('Item updated successfully')</script>";
        } else {
            echo "<script>alert('Error')</script>";
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Item List</h1>
    <form method="post" action="" id="itemForm">
        <label for="ID">ID:</label>
        <input type="number" id="ID" name="itemId" required>
        <label for="itemName">Item Name:</label>
        <input type="text" id="itemName" name="itemName" required>
        <label for="itemPrice">Price:</label>
        <input type="float" id="itemPrice" name="itemPrice" required>
        <label for="itemEmission">Emissions (kg):</label>
        <input type="float" id="itemEmission" name="itemEmission" required>
        <label for="itemTab">Tab:</label>
        <select id="itemTab" name="itemTab" required>
            <option value="pancakes">Pancakes</option>
            <option value="milkshakes">Milkshakes</option>
            <option value="crepes">Crepes</option>
            <option value="cakes">Cakes</option>
            <option value="drinks">Drinks</option>
        </select>

        <button type="submit" id="submitButton" name="additem" value="submit">Submit</button>
    </form>

    <button type="button" id="addItembtn">Add Item</button>
    <table id="itemTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Emissions (KG)</th>
                <th>Tab</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Items will be dynamically added here -->
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
        // Function to fetch items from the server and update the table
        function fetchItemsAndUpdateTable() {
            // Make an AJAX request to fetch items from the server
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_all_items.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse the JSON response
                    var items = JSON.parse(xhr.responseText);

                    // Get the tbody element of the table
                    var tbody = document.getElementById("itemTable").getElementsByTagName("tbody")[0];

                    // Clear the existing rows in the tbody
                    tbody.innerHTML = "";

                    // Iterate over the fetched items and insert them into the table
                    items.forEach(function (item) {
                        var row = tbody.insertRow();
                        row.setAttribute("data-item-id", item.ItemID);
                        row.innerHTML = "<td>" + item.ItemID + "</td><td>" + item.ItemName + "</td><td>" + item.ItemPrice + "</td><td>" + item.ItemEmission + "</td><td>" +item.ItemTab + "</td><td><button onclick='deleteItem(" + item.ItemID + ")'>Delete</button><button onclick='editItem(" + item.ItemID + ")'>Edit</button></td>";
                    });
                }
            };
            xhr.send();
        }

        // Call the function when the window has loaded
        window.onload = fetchItemsAndUpdateTable();

        // Function to delete an item
        function deleteItem(itemId) {
            if (confirm("Are you sure you want to delete this item?")) {
                var form = document.createElement("form");
                form.setAttribute("method", "post");
                form.setAttribute("action", "");
                form.style.display = "none";

                var itemIdField = document.createElement("input");
                itemIdField.setAttribute("type", "hidden");
                itemIdField.setAttribute("name", "itemId");
                itemIdField.setAttribute("value", itemId);

                var deleteField = document.createElement("input");
                deleteField.setAttribute("type", "hidden");
                deleteField.setAttribute("name", "deleteitem");
                deleteField.setAttribute("value", "delete");

                form.appendChild(itemIdField);
                form.appendChild(deleteField);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function editItem(itemId)
        {
            // Get the table row corresponding to the item
            var row = document.querySelector("tr[data-item-id='" + itemId + "']");
            if (!row) {
               console.error("Item row not found");
               return;
            }

            // Extract the item details from the row
            var cells = row.cells;
            var itemName = cells[1].textContent; // Second cell contains Item Name
            var itemPrice = cells[2].textContent; // Third cell contains Price
            var itemEmission = cells[3].textContent;
            var itemTab = cells[4].textContent; // Fourth cell contains Tab

            // Populate the form fields with the item details
            document.getElementById("ID").value = itemId;
            document.getElementById("itemName").value = itemName;
            document.getElementById("itemPrice").value = itemPrice;
            document.getElementById("itemEmission").value = itemEmission;
            document.getElementById("itemTab").value = itemTab;

            // Change form action to edit mode
            document.getElementById("itemForm").action = "";
            document.getElementById("submitButton").name = "edititem";
            document.getElementById("submitButton").textContent = "Edit Item";
        }

        // Function to switch form to add mode
        function switchToAddMode()
        {
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
