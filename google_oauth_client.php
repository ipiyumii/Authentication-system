<?php
require_once 'vendor/autoload.php'; // Adjust the path as needed
require_once('session.php');
require_once('dbUtil.php');
require_once('vendor/auth0/auth0-php/src/Auth0.php');

// Set up Google OAuth client
// Handle callback URL (dashboard.php)
if (isset($_GET['code'])) {
    // Exchange authorization code for access token
    $accessToken = exchangeCodeForAccessToken($_GET['code']);

    // Retrieve user information from Auth0 using the access token
    $userInfo = getUserInfoFromAuth0($accessToken);
    // Create session for authenticated user
    if ($userInfo) {
        $user_id = $userInfo['user_id'];
        $username = $userInfo['username'];
        $email = $userInfo['email'];

        $user = getUserByGoogleId($email);

        if($user){
            setSession('user_id', $user['user_id']);
            setSession('username', $user['username']);

            setSession('login_attempts', 0);
            setSession('last_login_attempt', 0);

            header('Location: dashboard.php');
            exit();
        } else{
           // $username = generateUsername();
            $saved = saveGoogleUserToDatabase($username, $email, $user_id);

            if ($saved){
                setSession('user_id', $user_id);
                setSession('username', $username);

                setSession('login_attempts', 0);
                setSession('last_login_attempt', 0);

                header('Location: dashboard.php');
                exit();
            } else {
                echo "User could not be created.";
            } exit();
        }

    }else {
        // Handle case where user information could not be retrieved from Auth0
        echo "Failed to retrieve user information.";
        exit();
    }
}else {
    // Handle case where authorization code is not present in URL parameters
    echo "Authorization code not found.";
    exit();
}


//// Handle OAuth callback
//if (isset($_GET['code'])) {
//    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
//    if (!isset($token['error'])) {
//        $client->setAccessToken($token);
//        $oauth2 = new Google_Service_Oauth2($client);
//        $userInfo = $oauth2->userinfo->get();
//
//        // Store or use the user information as needed
//        $googleId = $userInfo->getId();
//        $email = $userInfo->getEmail();
//        $name = $userInfo->getName();
//
//        // Example: Authenticate user based on their Google ID
//        // You can integrate this with your existing user management system
//        $user = getUserByGoogleId($googleId);
//        if ($user) {
//            setSession('user_id', $user['id']);
//            setSession('username', $user['username']);
//
//            setSession('login_attempts', 0);
//            setSession('last_login_attempt', 0);
//
//            header('Location: dashboard.php');
//            exit();
//        } else {
//            header('Location: google_register.php');
//        }
//    } else {
//        // Handle error
//        echo "Error: " . $token['error'];
//    }
//} else {
//    // Initiate OAuth authorization request
//    $authUrl = $client->createAuthUrl();
//    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
//}
//
//
//
////$client = new Google_Client();
////
////$client->setClientId('501921060648-tthuolqjfrl8il0v1g9gg4qbbnci210f.apps.googleusercontent.com');
////$client->setClientSecret('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');
////$client->setRedirectUri('http://localhost/auth_system/dashboard.php');
////$client->addScope(Google_Service_Oauth2::USERINFO_EMAIL); // Request access to user's email
////$client->addScope('profile');
////$client->addScope('email');
////
////
//if (isset($_GET['code'])) {
//    $token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);
//
//    if (!isset($token["error"])) {
//        $client->setAccessToken($token['access_token']);
//        $_SESSION['access_token'] = $token['access_token'];
//
//        //get user profile
//        $google_service = new Google_Service_Oauth2($client);
//        $data = $google_service->userinfo->get();
//
//        //$google_account_info = $google_service->userinfo->get();
//        $email = $data->email;
//        $name = $data->name;
//        $id = $data->id;
//
//        $user = getUserByGoogleId($email);
//
//        if ($user) {
//            setSession('user_id', $user['id']);
//            setSession('username', $user['username']); // Assuming username is stored in DB
//
//            // Clear unnecessary session variables
//            clearSession('login_attempts');
//            clearSession('last_login_attempt');
//
//            header('Location: dashboard.php');
//            exit();
//        } else {
//            $username = generateUsername($name);
//            $saved = saveGoogleUserToDatabase($username, $email, $id);
//
//            if ($saved) {
//                setSession('user_id', $id);
//                setSession('username', $username);
//
//                // Clear unnecessary session variables
//                clearSession('login_attempts');
//                clearSession('last_login_attempt');
//
//                header('Location: dashboard.php');
//            } else {
//                echo "User could not be created.";
//            }
//            exit();
//        }
//    }
//}


?>