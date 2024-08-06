<?php
try {
    require_once('session.php');
    require_once('vendor/autoload.php');
    require_once('dbUtil.php');
    require_once('validateInputs.php');
    require_once ('register.php');

    $mailer = new PHPMailer\PHPMailer\PHPMailer();

    // Check if the MFA token is set in the query parameters
    if (isset($_GET['token'])) {
        $mfaToken = $_GET['token'];
        $token = $_SESSION['mfa_token'];
        // Validate the MFA token 
        if (isset($_SESSION['mfa_token']) && $_SESSION['mfa_token'] === $mfaToken) {
            var_dump($_SESSION);

            // Retrieve user data from session 
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
                // Registration successful
                setSession('user_id', $userId);
                setSession('username', $username);

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
        throw new Exception('Error: MFA token not found.');
    }

} catch (Exception $e) {
    echo $e->getMessage();
    exit();
}
?>
