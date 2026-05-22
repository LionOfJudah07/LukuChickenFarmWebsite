<?php
require_once __DIR__ . '/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    redirect(url('/public/login.php'));
}

// Check session timeout
if (!checkSessionTimeout()) {
    redirect(url('/public/login.php'));
}

// Check if user has admin role
if (!isAdmin()) {
    die('Access denied. You do not have permission to access this area.');
}
?>