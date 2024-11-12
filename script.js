// Object to store items by tab
var itemsByTab = {};
// Array to store items in the basket
var basket = [];
// Total price of items in the basket
var totalPrice = 0;

// Function to fetch all items from the server
function fetchAllItems() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_all_items.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Parse the JSON response and sort items by tab
            var allItems = JSON.parse(xhr.responseText);

            // Organize items by tab
            allItems.forEach(function (item) {
                var tab = item.ItemTab;
                if (!itemsByTab[tab]) {
                    itemsByTab[tab] = [];
                }
                itemsByTab[tab].push(item);
            });
        }
    };
    xhr.send();
}

// Function to display items for a specific tab
function displayItems(tab) {
    // Get the ul element for the tab
    var tabItems = document.getElementById("tabItems");

    // Clear existing items
    tabItems.innerHTML = "";

    // Get items for the selected tab
    var items = itemsByTab[tab] || [];

    // Populate the list with items
    items.forEach(function (item) {
        var li = document.createElement("li");
        li.textContent = item.ItemName + " - $" + item.ItemPrice;
        li.onclick = function () {
            addItemToBasket(item);
        };
        tabItems.appendChild(li);
    });
}

// Function to add an item to the basket
function addItemToBasket(item) {
    console.log("Adding item to basket:", item);
    basket.push(item);
    totalPrice += parseFloat(item.ItemPrice); // Ensure that item.ItemPrice is treated as a number
    updateBasketDisplay();
}

// Function to remove an item from the basket
function removeItemFromBasket(item) {
    var index = basket.indexOf(item);
    if (index !== -1) {
        basket.splice(index, 1);
        totalPrice -= parseFloat(item.ItemPrice);
        updateBasketDisplay();
    }
}

// Function to update the basket display
function updateBasketDisplay() {
    var basketElement = document.getElementById("basket");
    var totalPriceElement = document.getElementById("totalPrice");

    // Clear existing basket display
    basketElement.innerHTML = "";
    totalPriceElement.textContent = totalPrice.toFixed(2); 

    // Populate the basket display with items
    basket.forEach(function (item) {
        var li = document.createElement("li");
        li.textContent = item.ItemName + " - $" + parseFloat(item.ItemPrice).toFixed(2); 
        li.onclick = function () {
            removeItemFromBasket(item);
        };
        basketElement.appendChild(li);
    });
}
function checkout() {
    // Get the total price from the totalPrice variable
    var total = totalPrice.toFixed(2); // Format total price

    // Construct the URL for the next webpage with the total price as a query parameter
    var nextUrl = "user_information.html?total=" + total;

    // Redirect the user to the next webpage
    window.location.href = nextUrl;
}

// Call fetchAllItems() when the page loads
window.onload = fetchAllItems;
