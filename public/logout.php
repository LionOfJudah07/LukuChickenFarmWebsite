<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Destroy session
$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

session_destroy();

// Redirect to home page
redirect(url('/public/'));
?>