<?php
require_once __DIR__ . '/db.php';

function loginUser($username, $password) {
    $db = Database::getInstance();
    
    try {
        $user = $db->fetchOne(
            "SELECT * FROM users WHERE username = ? AND is_active = true",
            [$username]
        );
        
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['login_time'] = time();
            
            // Update last login
            $db->query(
                "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?",
                [$user['id']]
            );
            
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Invalid username or password'];
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Login failed. Please try again.'];
    }
}

function logoutUser() {
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

function checkSessionTimeout() {
    if (isset($_SESSION['login_time'])) {
        $timeout = SESSION_TIMEOUT ?? 1800; // 30 minutes default
        if (time() - $_SESSION['login_time'] > $timeout) {
            logoutUser();
            return false;
        }
        $_SESSION['login_time'] = time(); // Reset timer
    }
    return true;
}
?>