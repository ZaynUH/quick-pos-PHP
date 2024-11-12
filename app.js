var products;
// Array to store the items in the shopping cart
const cartItems = [];

// Function to fetch items from the database
function fetchItems() {
  $.ajax({
      url: 'fetch_all_items.php', // Replace 'fetch_items.php' with the actual path to your PHP file that fetches items from the database
      type: 'GET',
      success: function(response) {
          // Parse the JSON response
          products = JSON.parse(response);
          renderProducts(products, "");
      },
      error: function(xhr, status, error) {
          console.error('Error fetching items:', error);
      }
  });
}

// Function to render products in the product list
function renderProducts(items, category) {
  
  const productList = $('#product-list');
  productList.empty(); // Clear previous products
  if(category == "")
  {
    category = 'milkshakes';
  }
  items.forEach(item => {
    if(item.ItemTab == category)
    {
      const productItem = $('<div class="product-item">');
      productItem.append(`<img src="productpics/${item.ItemIcon}" alt="${item.ItemName}">`);
      productItem.append(`<p>${item.ItemName}</p>`);
      productItem.append(`<p>Price: $${parseFloat(item.ItemPrice).toFixed(2)}</p>`);
      productItem.append(`<p>CO2 Emission: ${item.ItemEmission} kg</p>`);
      productItem.append(`<button onclick="addToCart(${item.ItemID})">Add to Cart</button>`);
      productList.append(productItem);
    }
      
  });
}

// Function to filter products based on category
function filterProducts(category) {
  renderProducts(products, category);
}

// Function to render items in the shopping cart
function renderCart() {
  const cartList = $('#cart-list');
  const totalSpan = $('#total');
  const totalCO2Span = $('#total-co2');
  cartList.empty(); // Clear previous items
  
  let total = 0;
  let totalCO2 = 0;
  
  cartItems.forEach(item => {
    const cartItem = $('<li class="cart-item">');
    cartItem.append(`<span>${item.name}</span>`);
    cartItem.append(`<span>$${item.price.toFixed(2)}</span>`);
    cartItem.append(`<span>${item.co2Emission} kg</span>`); // Display CO2 emission for the item
    
    // Add a click event listener to remove the item when clicked
    cartItem.click(function() {
      removeCartItem(item.ItemID);
    });
    
    cartList.append(cartItem);
    
    total += parseFloat(item.price);
    totalCO2 += item.co2Emission;
  });
  
  totalSpan.text(total.toFixed(2));
  totalCO2Span.text(totalCO2.toFixed(2));
}

// Function to add a product to the shopping cart
function addToCart(productId) {
  const product = products.find(item => item.ItemID == productId);
  if (product) {
    cartItems.push({
      id: product.ItemID,
      name: product.ItemName,
      price: parseFloat(product.ItemPrice),
      co2Emission: parseFloat(product.ItemEmission),
    });
    renderCart(); // Update the shopping cart UI
  }
  else alert("Item not found");
}

// Function to remove an item from the shopping cart
function removeCartItem(itemId) {
  const index = cartItems.findIndex(item => item.ItemID === itemId);
  if (index !== -1) {
    cartItems.splice(index, 1);
    renderCart(); // Update the shopping cart UI
  }
}

function handlePayment() {
  const totalAmount = $('#total').text();
  const totalCO2 = $('#total-co2').text();
  if(totalAmount != 0)
  {
    // Store the total amount and total CO2 emission in local storage
    localStorage.setItem('totalAmount', totalAmount);
    localStorage.setItem('totalCO2', totalCO2);

    // Redirect to the payment page without the total amount and total CO2 emission in the URL
    const paymentUrl = "user_information.html?total=" + totalAmount;
    window.location.href = paymentUrl;
  }
  
}

// This is where you would add any additional JavaScript, for instance to update the current time dynamically.
document.addEventListener("DOMContentLoaded", (event) => {
  // Update the current time display on the page

  const currentTimeDisplay = document.getElementById("current-time");
  function updateTime() {
    const now = new Date();
    currentTimeDisplay.textContent =
      now.toTimeString().substring(0, 5) + " - " + now.toLocaleDateString();
  }
  setInterval(updateTime, 1000); // Update the time every second
  updateTime(); // Also update the time immediately
});

// Document ready
$(document).ready(function() {
  fetchItems();

  // Attach click event to category buttons
  $('.category-button').click(function() {
    const category = $(this).attr('data-category');
    filterProducts(category);
  });

  // Attach click event to Pay Now button
  $('#pay-button').click(function() {
    handlePayment();
  });
});
