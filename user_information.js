// user_information.js

// Function to validate credit card number using an enhanced Luhn algorithm
function isCreditCardValid(creditCardNumber) {
  // Remove spaces and check for valid length
  const sanitizedCreditCardNumber = creditCardNumber.replace(/\s/g, '');
  if (sanitizedCreditCardNumber.length < 12 || sanitizedCreditCardNumber.length > 19) {
    return false;
  }

  const reversedDigits = sanitizedCreditCardNumber.split('').map(Number).reverse();

  let sum = 0;

  for (let i = 0; i < reversedDigits.length; i++) {
    let digit = reversedDigits[i];

    if (i % 2 === 1) {
      digit *= 2;
      if (digit > 9) {
        digit -= 9;
      }
    }

    sum += digit;
  }

  // Check if the sum is a multiple of 10
  return sum % 10 === 0;
}

// Function to validate credit card number format
function isValidCreditCardFormat(creditCardNumber) {
  // Basic regex pattern for credit card number validation
  const pattern = /^[0-9]{12,19}$/;
  return pattern.test(creditCardNumber);
}

// Function to validate CVV: 3 or 4 digits
function isValidCvv(cvv) {
return /^[0-9]{3,4}$/.test(cvv);
}

// Function to validate expiry date: format MM/YY and not in the past
function isExpiryDateValid(expiryDate) {
const currentDate = new Date();
const currentYear = currentDate.getFullYear() % 100; // Get last two digits of the year
const currentMonth = currentDate.getMonth() + 1; // Get month as a number (1-12)

const [expMonth, expYear] = expiryDate.split('/').map(Number);

if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
  return false; // Expired
}

return expMonth >= 1 && expMonth <= 12 && expYear >= currentYear && expYear <= currentYear + 20;
}

document.addEventListener('DOMContentLoaded', function() {
// Restrict input length for Credit Card Number
document.getElementById('creditCardNumber').addEventListener('input', function() {
  this.value = this.value.replace(/[^0-9]/g, '');
  if (this.value.length > 19) {
    this.value = this.value.slice(0, 19);
  }
});

// Restrict input length for CVV
document.getElementById('cvv').addEventListener('input', function() {
  this.value = this.value.replace(/[^0-9]/g, '');
  if (this.value.length > 4) {
    this.value = this.value.slice(0, 4);
  }
});

// Adjust input for Expiry Date to automatically insert a slash and limit to MM/YY format
document.getElementById('expiryDate').addEventListener('input', function() {
  let input = this.value;

  // Remove all non-digits
  input = input.replace(/\D/g, '');

  // Automatically insert slash after MM
  if (input.length > 2) {
    input = input.slice(0, 2) + '/' + input.slice(2, 4);
  }

  this.value = input;

  // Ensure the input is not longer than 5 characters (MM/YY)
  if (this.value.length > 5) {
    this.value = this.value.slice(0, 5);
  }
});
});

// Extract total price from URL parameter
const urlParams = new URLSearchParams(window.location.search);
const totalPrice = urlParams.get('total');
document.getElementById('total').textContent = "Total Price: " + totalPrice;


// Check if the page is being refreshed
const navigationEntries = window.performance.getEntriesByType('navigation');
if (navigationEntries.length && navigationEntries[0].type === 'reload') {
  // Redirect to pos.php
  window.location.href = 'pos.php';
}

// Updated function to proceed to the payment confirmation page
function proceedToPayment() {
// Perform form validation here
const creditCardNumber = document.getElementById('creditCardNumber').value.trim();
const expiryDate = document.getElementById('expiryDate').value.trim(); // Assuming you have an input with id="expiryDate"
const cvv = document.getElementById('cvv').value.trim(); // Assuming you have an input with id="cvv"

if (creditCardNumber === '' || expiryDate === '' || cvv === '') {
  alert('Please fill in all fields.');
  return;
}

if (!isValidCreditCardFormat(creditCardNumber)) {
  alert('Invalid credit card number format. Please enter a valid number.');
  return;
}

if (!isCreditCardValid(creditCardNumber)) {
  alert('Invalid credit card number. Please check and try again.');
  return;
}

if (!isValidCvv(cvv)) {
  alert('Invalid CVV. Please check and try again.');
  return;
}

if (!isExpiryDateValid(expiryDate)) {
  alert('Invalid expiry date. Please check and try again.');
  return;
}

// Store the last 4 digits of the credit card number in local storage
const last4Digits = creditCardNumber.slice(-4);
localStorage.setItem('last4Digits', last4Digits);

// Redirect to payment confirmation page with total price in URL parameter
window.location.href = 'payment_confirmation.html?total=' + totalPrice;
}
