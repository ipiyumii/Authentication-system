<?php
try {
    // Include necessary libraries and files
    require_once('session.php');
    require_once('vendor/autoload.php');
    require_once('dbUtil.php');
    require_once('validateInputs.php');
    require_once ('register.php');

    // Initialize necessary components
    $mailer = new PHPMailer\PHPMailer\PHPMailer();

    // Check if the MFA token is set in the query parameters
    if (isset($_GET['token'])) {
        // Retrieve the MFA token from the query parameters
        $mfaToken = $_GET['token'];
        $token = $_SESSION['mfa_token'];
        // Validate the MFA token against the one stored in the session
        if (isset($_SESSION['mfa_token']) && $_SESSION['mfa_token'] === $mfaToken) {
            // MFA token is valid, proceed with user registration
            var_dump($_SESSION);
            // Retrieve user data from session (if needed)
            if (isset($_SESSION['registration_data'])) {
                $userData = $_SESSION['registration_data'];
                $username = $userData['username'];
                $email = $userData['email'];
                $telephone = $userData['telephone'];
                $address = $userData['address'];
                $password = $userData['password'];
            } else {
                throw new Exception('Error: User data not found in the session.');
            }

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
                throw new Exception('Error: User registration failed. Please try again.');
            }
        } else {
            // Invalid or expired MFA token
            throw new Exception('Error: Invalid or expired verification token.');
        }
    } else {
        // MFA token not found in the query parameters
        throw new Exception('Error: MFA token not found.');
    }

} catch (Exception $e) {
    // Handle exceptions
    echo $e->getMessage();
    exit();
}
?>
