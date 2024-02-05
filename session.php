<?php
// session.php

// Start session
session_start();

function setSession($key, $value) {
    // Set session variable
    $_SESSION[$key] = $value;
}

function getSession($key) {
    // Get session variable
    return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
}

function endSession() {
    // Destroy the session
    session_destroy();
}

// Check for session timeout (adjust the timeout duration as needed)
$timeout = 60 * 30; // 30 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    endSession();
}
$_SESSION['last_activity'] = time();
?>
