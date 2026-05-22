<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Africa/Addis_Ababa');

// Base URLs
define('BASE_URL', 'http://localhost/luku-farm');
define('BASE_PATH', dirname(__DIR__));

// Database configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'luku_farm');
define('DB_USER', 'postgres');
define('DB_PASS', 'admin');

// Upload directories
define('UPLOAD_DIR', BASE_PATH . '/uploads/');
define('UPLOAD_URL', BASE_URL . '/uploads/');

// Pagination
define('ITEMS_PER_PAGE', 12);

// Session timeout (30 minutes)
define('SESSION_TIMEOUT', 1800);

// CSRF protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Language settings
$supported_languages = ['en', 'am', 'om'];
$default_language = 'en';

// Get current language from session or set default
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = $default_language;
}

// Set current language
$current_language = $_SESSION['lang'];

// Load translation function
require_once __DIR__ . '/functions.php';
?>