<?php
// Include necessary libraries and files
require_once('vendor/autoload.php');
require_once('session.php');
require_once('dbUtil.php');
require_once('validateInputs.php');

// Initialize necessary components
$mailer = new PHPMailer\PHPMailer\PHPMailer();

// Check if the MFA token is set in the query parameters
if (isset($_GET['token'])) {
    // Retrieve the MFA token from the query parameters
    $mfaToken = $_GET['token'];

    // Validate the MFA token against the one stored in the session
    if (isset($_SESSION['mfa_token']) && $_SESSION['mfa_token'] === $mfaToken) {
        // MFA token is valid, proceed with user registration

        // Retrieve user data from session (if needed)
        $username = $_SESSION['registration_data']['username'];
        $email = $_SESSION['registration_data']['email'];
        $telephone = $_SESSION['registration_data']['telephone'];
        $address = $_SESSION['registration_data']['address'];
        $password = $_SESSION['registration_data']['password'];

        // Insert user into the database
        $hashedPassword = hashPassword($password);
        $userId = saveUserToDatabase($username, $hashedPassword, $email, $address, $telephone);

        if ($userId) {
            // Registration successful, set session variables
            setSession('user_id', $userId);
            setSession('username', $username);

            // Redirect to the dashboard page or success page
            header('Location: dashboard.php');
            exit();
        } else {
            // Registration failed, handle accordingly
            echo 'Error: User registration failed. Please try again.';
        }
    } else {
        // Invalid or expired MFA token
        echo 'Error: Invalid or expired verification token.';
    }
} else {
    // MFA token not found in the query parameters
    echo 'Error: MFA token not found.';
}
?>
