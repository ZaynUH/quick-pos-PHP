function checkAccess(url) {
    const user = prompt("Enter admin username:");
    const pass = prompt("Enter admin password:");

    alert(user + " " + pass)
    // Send an AJAX request to validate the credentials
    $.ajax({
        url: 'check_admin.php',
        type: 'POST',
        dataType: 'json',
        data: {username: user, password: pass},
        success: function(response) {
            if (response.success) {
                // If credentials are correct, redirect to the specified URL
                window.location.href = url;
            } else {
                // If credentials are incorrect, show an alert with the error message
                alert(response.message);
            }
        },
        error: function(xhr, status, error) {
            // If an error occurs, show an alert with the error message
            console.error("Error: " + error);
        }
    });
}
