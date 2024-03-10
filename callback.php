<?php

// Include the Google API client library
require_once('session.php');
require_once 'vendor/autoload.php';
require_once('dbUtil.php');
require_once ('validateInputs.php');

// Initialize the Google client
$client = new Google_Client();
$client->setAuthConfig(__DIR__ .'/client_secret_601545463122-q61e84etavrj2stgh6erhfq2mvl2gf48.apps.googleusercontent.com.json'); // Path to your client secret JSON file
$client->setRedirectUri('http://localhost/auth_system/callback.php'); // Your callback URL
$client->addScope('email');
$client->addScope('profile');

// Handle the OAuth callback
if (isset($_GET['code'])) {
    try {
        // Exchange authorization code for access token
        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        // Set access token to the client
        $client->setAccessToken($accessToken);

        // Get user info
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        if($userInfo){
            // Extract user information into separate variables
           $email = $userInfo->email;
           $google_id = $userInfo->id;
           $givenName = $userInfo->givenName;
           $familyName = $userInfo->familyName;
           $verifiedEmail = $userInfo->verifiedEmail;

           $user = getUserByGoogleId($google_id);

            if($user){
                setSession('user_id', $user['id']);
                setSession('username', $user['username']);

                setSession('login_attempts', 0);
                setSession('last_login_attempt', 0);

                if (!isset($_SESSION['user_id'])) {
                    header('Location: login.php');
                } else {
                    header('Location: dashboard.php');
                }
                exit();
            } else{
                $username = generateUsername($givenName);
                $saved = saveGoogleUserToDatabase($username, $email, $google_id);

                if($saved){
                    setSession('user_id', $saved);
                    setSession('username', $username);

                    setSession('login_attempts', 0);
                    setSession('last_login_attempt', 0);

                    header('Location: dashboard.php');
                    exit();
                } else{
                    echo "User could not be created.";
                }
            }
        }else{
            echo "Failed to retrieve user information.";
            exit();
        }
        // You can redirect the user or perform further actions here
    } catch (Google_Service_Exception $e) {
        error_log('Google Service Error: ' . $e->getMessage());
        echo 'An error occurred while processing your request. Please try again later.';
    } catch (Google_Exception $e) {
        error_log('Google Error: ' . $e->getMessage());
        echo 'An error occurred while processing your request. Please try again later.';
    } catch (Exception $e) {
         error_log('Error: ' . $e->getMessage());
//        echo 'An error occurred while processing your request. Please try again later.';
            echo $e;
    }
} else {
    echo "Authorization code not found.";
    exit();
}

//if (isset($_GET['code'])) {
//    try {
//        // Exchange authorization code for access token
//        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
//
//        // Set access token to the client
//        $client->setAccessToken($accessToken);
//
//        // Get user info
//        $oauth2 = new Google_Service_Oauth2($client);
//        $userInfo = $oauth2->userinfo->get();
//
//        // Display user information
//        echo "<pre>";
//        print_r($userInfo);
//        echo "</pre>";
//        // You can redirect the user or perform further actions here
//    } catch (Google_Service_Exception $e) {
//        echo 'Google Service Error: ' . $e->getMessage();
//    } catch (Google_Exception $e) {
//        echo 'Google Error: ' . $e->getMessage();
//    } catch (Exception $e) {
//        echo 'Error: ' . $e->getMessage();
//    }
//} else {
//    echo 'Authorization code not found.';
//}
?>


