<?php
session_start();
require_once __DIR__ . '/auth0.php';

$userInfo = $auth0->getUser();

if ($userInfo) {
    $_SESSION['user'] = $userInfo;
    header('Location: dashboard.php');
    exit;
} else {
    echo 'Authentication failed';
}
