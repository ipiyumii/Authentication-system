<?php
// session.php

// Start session
session_start();

function setSession($key, $value) {
    // Set session variable
    $_SESSION[$key] = $value;
}

function getSession($key) {
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

function endSession() {
    $userId = $_SESSION['user_id'];

    // $rest= resetLoginAttempts($userId);
    // Destroy the session
    $_SESSION = [];
    session_destroy();
}

// Check for session timeout 
$timeout = 60 * 30; 
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    endSession();
}
$_SESSION['last_activity'] = time();
?>
