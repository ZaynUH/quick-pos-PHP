
function sendVars(userId) {
  // Send clock-in time to the database
  const currentTime = formatDateTime(new Date()); // Get current time
  sendTimeToDatabase(parseInt(userId), currentTime);
}

function formatDateTime(date) {
  // Ensure date is a valid Date object
  if (!(date instanceof Date)) {
    return "Invalid Date";
  }

  // Get the components of the date
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0"); // Months are zero-based, so add 1
  const day = String(date.getDate()).padStart(2, "0");
  const hours = String(date.getHours()).padStart(2, "0");
  const minutes = String(date.getMinutes()).padStart(2, "0");
  const seconds = String(date.getSeconds()).padStart(2, "0");

  // Construct the formatted date-time string
  const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

  return formattedDateTime;
}

function sendTimeToDatabase(userId, currentTime) {
  // Make an AJAX request to your backend PHP script to store the clock-in time in the database
  $.ajax({
    url: 'store_clocked_time.php', // Replace with the actual path to your PHP script
    type: 'POST',
    data: { userId: userId, time: currentTime },
    success: function(response) {
      console.log('Clocked time stored successfully:', response);
      // Parse the response JSON

      // Find the index of the substring "clockedIn"
    const clockedInIndex = response.indexOf("clockedIn");

    // If "clockedIn" is found in the response
    if (clockedInIndex !== -1) {
      // Extract the substring starting from "clockedIn" to the end of the response
      const clockedInSubstring = response.substring(clockedInIndex);

      // Split the substring by space to get the words
      const words = clockedInSubstring.split(':');

      // Get the value of "clockedIn" which should be the next word after "clockedIn"
      const clockedInValue = words[1];

      // Output the result
      console.log("Clocked in value:", clockedInValue);
      if(clockedInValue == "false}")
      {
        alert("Clocked in");
        highlightTeamMemberButton(userId, true);
      }
      else if(clockedInValue == "true}")
      {
        alert("Clocked out");
        highlightTeamMemberButton(userId, false);
      }
      else alert("Error");
    }
    },
    error: function(xhr, status, error) {
      console.error('Error storing clocked time:', error);
    }
  });
}

function highlightTeamMemberButton(userId, isClockedIn) {
  // Remove 'active' class from all buttons
  const userButtons = document.querySelectorAll(".clock-in-container button");
  userButtons.forEach(button => button.classList.remove('active'));

  // Add or remove 'clocked-in' class based on clocked-in status
  const button = document.getElementById(`user_${userId}`);
  if (button) {
    if (isClockedIn) {
      button.classList.add('clocked-in');
    } else {
      button.classList.remove('clocked-in');
    }
  }
}

function activatationuser(e) {
  let buttonid = e.target.id;
  const userbtns = document.querySelectorAll(".clock-in-container button");

  userbtns.forEach((Eachbtn) => {
    Eachbtn.classList.remove("active");
  });

  // Store the active button ID in local storage
  window.localStorage.setItem("button-active", buttonid);

  // Add the 'active' class to the clicked button
  e.target.classList.add("active");

  // Get the submit button
  let submitbutton = document.querySelector(".login-btn input");

  // Check if the submit button exists before trying to set its value
  if (submitbutton) {
    submitbutton.value = `Login as ${e.target.textContent}`;
  } else {
    console.log("Submit button not found.");
  }

  // Update the team member display if the element exists
  const teamMemberDisplay = document.querySelector(".teammember");
  if (teamMemberDisplay) {
    teamMemberDisplay.textContent = e.target.textContent;
  } else {
    console.log("Team member display element not found.");
  }
}


var employees = [];

function fetchAllEmployees(employees) {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "fetch_all_employees.php", true);
  xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          // Parse the JSON response
          var allEmployees = JSON.parse(xhr.responseText);
          
          // Organize employees by their ID
          allEmployees.forEach(function (employee) {
              var employeeID = employee.EmployeeID; 
              employees[employeeID] = (employee);
          });
          
          // Now, each employee will have their own entry in the employees object
          // You can access individual employees using their ID like employees[employeeID]
      }
  };
  xhr.send();
}

function displayEmployees()
{
  fetchAllEmployees(employees);
  setTimeout(loaddata, 1000);
}

window.onload = displayEmployees();

function loaddata() {
  if (Object.keys(employees).length === 0) {
    console.log("No employees available.");
    document.querySelector(".clock-in-container").innerHTML = "<p>No employees found.</p>";
    return;
  } else {
    console.log("Employees available.");
    document.querySelector(".clock-in-container").innerHTML = "";
  }

  // Create a button for each employee
  Object.values(employees).forEach((employee) => {
    const storedData = employee;
    if (storedData) {
      document
        .querySelector(".clock-in-container")
        .insertAdjacentHTML(
          "beforeend",
          `<button class="name-button" id="user_${storedData.EmployeeID}">${storedData.EmployeeName}</button>`
        );
    }
  });

  const userbtns = document.querySelectorAll(".clock-in-container button");
  let activebtn = window.localStorage.getItem("button-active");

  // Highlight the previously active button if any
  if (activebtn) {
    let submitbutton = document.querySelector(".login-btn input");
    let activebtninner = document.querySelector(`#${activebtn}`);

    // Check if the submit button exists before trying to set its value
    if (submitbutton && activebtninner) {
      activebtninner.classList.add("active");
      submitbutton.value = `Login as ${activebtninner.textContent}`;
      document.querySelector(".teammember").textContent = activebtninner.textContent;
    } else {
      console.log("Submit button or active button not found.");
    }
  }

  // Add event listeners to each button
  userbtns.forEach((Eachbtn) => {
    Eachbtn.addEventListener("click", (e) => {
      activatationuser(e); // Call activatationuser when a button is clicked
      showLoginForm();
      lastClickedButton = document.querySelector(`#${e.target.id}`);
    });
  });
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

// Function to show the login form when a "Name" button is clicked
function showLoginForm() {
  document.getElementById("loginModal").classList.add("active");
}

// Function to hide the login form
function hideLoginForm() {
  document.getElementById("loginModal").classList.remove("active");
}

// Assign the showLoginForm function to all "Name" buttons
document.querySelectorAll(".card").forEach(function (button) {
  button.onClick = showLoginForm;
});

// This script assumes each button is uniquely tied to a user, which may require backend integration.

// This script will be part 1 due to the length constraint
// Initialize lastClickedButton to keep track of which button was clicked
let lastClickedButton = null;

// Logout function to clear session
function logout() {
  if (lastClickedButton) {
    // Clear the session storage for this button
    localStorage.removeItem("user_" + lastClickedButton.dataset.index);
    // Reset the button text to 'Name'
    // Hide login modal
    hideLoginForm();
  }
}

// Function to enter pin numbers into the password field
function enterPin(number) {
  let pinInput = document.getElementById("pin");
  pinInput.value += number; // Append the number to the pin input
}

function validateNumberInput(event) {
  const input = event.target.value;
  const regex = /^[0-9]*$/; // Regular expression to match only numbers

  if (!regex.test(input)) {
      // If the input does not match the regex, remove the last character
      event.target.value = input.slice(0, -1);
  }
}

// Function to handle login submission
function submitLogin()
{
  const enteredPin = document.getElementById("pin").value;

  const userId = lastClickedButton.id.split('_')[1];

  if (enteredPin != "")
  {
    employees.forEach(employee => {0
      // Check if the employee ID matches the entered user ID
      if (employee.EmployeeID === userId)
      {
        let intpin = parseInt(enteredPin);

        if(employee.EmployeePassword == intpin)
        {
          hideLoginForm();
          sendVars(userId);
        }
        else
        {
          alert("Incorrect password");
        }
      }
    });
  }
  else
  {
    alert("Please enter password.");
  }
}
