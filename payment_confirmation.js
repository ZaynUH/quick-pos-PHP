// payment_confirmation.js

// Function to continue shopping
function continueShopping() {
    window.location.href = 'pos.php'; // Replace with your home page URL
}

// Check if the page is being refreshed
const navigationEntries = window.performance.getEntriesByType('navigation');
if (navigationEntries.length && navigationEntries[0].type === 'reload') {
  // Redirect to pos.php
  window.location.href = 'pos.php';
}

// Get payment amount from the URL query parameter (if any)
const urlParams = new URLSearchParams(window.location.search);
const totalAmount = urlParams.get('total');

$(document).ready(function() {
  // Existing code to retrieve and display payment amount
  const paymentAmount = totalAmount;
  $('#paymentAmount').text(`$${paymentAmount}`);
  $('#totalAmountDisplay').text(`$${paymentAmount}`);

  // Retrieve the last 4 digits from local storage
  // Retrieve the last 4 digits from local storage and display them
  const last4Digits = localStorage.getItem('last4Digits');
  $('#last4Digits').text(last4Digits);
;

  // Optionally, clear the last 4 digits from local storage if it's no longer needed
  localStorage.removeItem('last4Digits');

  // Optionally, clear the total amount from local storage if it's no longer needed
  localStorage.removeItem('totalAmount');
});