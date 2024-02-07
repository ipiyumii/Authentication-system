<?php
// Load Google API client library
require_once 'vendor/autoload.php'; // Adjust the path as needed
require_once('session.php');
require_once('dbUtil.php');
require_once('auth.php');

// Set up Google OAuth client
$client = new Google_Client();
$client->setAuthConfig('/Applications/MAMP/htdocs/auth_system/client_secret_27789779214-qsdde5m62ivka4f2nikqt5kb2s4lt7ee.apps.googleusercontent.com.json');
$client->setRedirectUri('http://localhost/auth_system/dashboard.php');
$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL); // Request access to user's email

// Handle OAuth callback
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token);
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        // Store or use the user information as needed
        $googleId = $userInfo->getId();
        $email = $userInfo->getEmail();
        $name = $userInfo->getName();
         
        // Example: Authenticate user based on their Google ID
        // You can integrate this with your existing user management system
        $user = getUserByGoogleId($googleId);
        if ($user) {  
            setSession('user_id', $user['id']);
            setSession('username', $user['username']);
        
            setSession('login_attempts', 0);
            setSession('last_login_attempt', 0);
        
            header('Location: dashboard.php');
            exit();
        } else {
            header('Location: google_register.php');
        }
    } else {
        // Handle error
        echo "Error: " . $token['error'];
    }
} else {
    // Initiate OAuth authorization request
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
}
?>

 