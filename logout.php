<?php
require_once "session.php";
require_once('dbUtil.php');

endSession(); 

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if ($username) {
    resetLoginAttempts($username);
}
header("Location: login.php");
exit;
?>
