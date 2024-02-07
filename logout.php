<?php
require_once "session.php";
endSession(); // Call the endSession() function from session.php
header("Location: login.php");
exit;
?>
