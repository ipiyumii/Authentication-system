<!-- registration.php -->
<?php
// Include database utility functions
require_once 'dbUtil.php';
require_once 'session.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input from the registration form
    $googleId = $_POST['google_id']; // Google ID from the hidden input field
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if the username is available
    if (validateUserName($username)) {
        // Hash the password before saving to the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Save the new user to the database
        $userId = saveUserToDatabase($username, $hashedPassword, '', '', ''); // No address and telephone for now
        
        // Check if user creation was successful
        if ($userId) {
            // Set session variables or redirect to dashboard
            // Set session variables
            setSession('user_id', $userId);
            setSession('username', $username);
            
            // Reset login attempts on successful login
            setSession('login_attempts', 0);
            setSession('last_login_attempt', 0);
            
            // Redirect to the dashboard page
            header('Location: dashboard.php');
            exit();
        } else {
            // Handle error (e.g., user creation failed)
            echo "Error: Failed to create user account.";
        }
    } else {
        // Username is not available, display an error message
        echo "Error: Username is already taken. Please choose a different username.";
    }

} else {
    // Redirect to the registration form if accessed directly without form submission
    header('Location: registration.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>User Registration</h2>
    <form action="register.php" method="POST">
        <input type="hidden" name="google_id" value="<?php echo htmlspecialchars($_GET['google_id']); ?>">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <!-- Add more fields as needed -->
        <button type="submit">Register</button>
    </form>
</body>
</html>
