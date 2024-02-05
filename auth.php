<?php
// auth.php

function hashPassword($password) {
    // Hash the password using bcrypt
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hashedPassword) {
    // Verify the password against the hashed password
    return password_verify($password, $hashedPassword);
}
?>
