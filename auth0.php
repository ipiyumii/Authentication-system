<?php
// auth0.php

// Load Auth0 PHP SDK
require __DIR__ . '/vendor/autoload.php'; // Adjust the path as needed

use Auth0\SDK\Auth0;

// Initialize Auth0 SDK with your Auth0 credentials
$auth0 = new Auth0([
    'domain' => 'dev-n204rpwbcj2fvi0r.us.auth0.com',
    'client_id' => 'bEndYokb2KoUTxpRT6SsRUyF7dYsWBiM',
    'client_secret' => 'Q8ycO80xnybABNTugahSZ4pfUQnpDTVQH7BMEnvD1S-EXrxO5E-1l94d8CQ9nXaI',
    'redirect_uri' => 'http://localhost/auth_system/dashboard.php',
    // 'audience' => 'https://YOUR_AUTH0_DOMAIN/api/v2/',
    // 'scope' => 'openid profile email'


    // Update your auth0.php file with your Auth0 credentials. 
    // You will need to replace 'YOUR_AUTH0_DOMAIN', 'YOUR_CLIENT_ID',
    //  'YOUR_CLIENT_SECRET', and 'YOUR_REDIRECT_URI' with your actual Auth0 domain, client ID, client secret, and 
]);

// Handle Auth0 login process
if (isset($_GET['code'])) {
    try {
        // Process the authentication callback
        $auth0->login();
        
        // After successful login, you can redirect the user to the dashboard or any other page
        header('Location: dashboard.php');
        exit();
    } catch (Exception $e) {
        // Handle any errors
        echo 'An error occurred: ' . $e->getMessage();
    }
}
?>
