<?php
/**
 * EMERGENCY ADMIN CREATOR
 * ========================
 * Run this file ONLY when you need to create a new admin user
 * ACCESS VIA: http://yourdomain.com/luku-farm/create-admin.php
 * DELETE THIS FILE AFTER USE!
 */

require_once 'includes/config.php';
require_once 'includes/db.php';

// Set time limit and error reporting
set_time_limit(60);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define admin credentials here - CHANGE THESE!
$NEW_ADMIN_USERNAME = 'admin';
$NEW_ADMIN_PASSWORD = 'Admin@123';
$NEW_ADMIN_EMAIL = 'admin@lukufarm.com';
$NEW_ADMIN_FULLNAME = 'System Administrator';

// HTML header
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Admin Creator - LUKU Farm</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-top: 0;
            border-bottom: 3px solid #28a745;
            padding-bottom: 15px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #28a745;
            margin: 20px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #dc3545;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 20px;
            border-radius: 8px;
            border-left: 5px solid #ffc107;
            margin: 20px 0;
        }
        .info-box {
            background: #e7f3fe;
            border-left: 5px solid #2196F3;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 14px;
        }
        .credentials {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px dashed #28a745;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 20px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #218838;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 LUKU Farm - Emergency Admin Creator</h1>';

// Test database connection
try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo '<div class="success">✅ Database connected successfully!</div>';
} catch (Exception $e) {
    echo '<div class="error">❌ Database connection failed: ' . htmlspecialchars($e->getMessage()) . '</div>';
    echo '<p>Please check your database configuration in <code>includes/config.php</code></p>';
    echo '</div></body></html>';
    exit;
}

// Check if users table exists
try {
    $table_check = $db->fetchOne("
        SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE table_schema = 'public' 
            AND table_name = 'users'
        ) as exists
    ");
    
    if (!$table_check['exists']) {
        echo '<div class="error">❌ Users table does not exist! Please import schema.sql first.</div>';
        echo '</div></body></html>';
        exit;
    }
} catch (Exception $e) {
    echo '<div class="error">❌ Error checking users table: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create_admin') {
        $username = trim($_POST['username'] ?? $NEW_ADMIN_USERNAME);
        $password = $_POST['password'] ?? $NEW_ADMIN_PASSWORD;
        $email = trim($_POST['email'] ?? $NEW_ADMIN_EMAIL);
        $full_name = trim($_POST['full_name'] ?? $NEW_ADMIN_FULLNAME);
        
        // Validate inputs
        $errors = [];
        if (empty($username)) $errors[] = 'Username is required';
        if (empty($password)) $errors[] = 'Password is required';
        if (empty($email)) $errors[] = 'Email is required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
        
        if (empty($errors)) {
            try {
                // Check if username exists
                $existing = $db->fetchOne(
                    "SELECT id FROM users WHERE username = ?",
                    [$username]
                );
                
                if ($existing) {
                    // Update existing user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $db->update('users', [
                        'password' => $hashed_password,
                        'email' => $email,
                        'full_name' => $full_name,
                        'role' => 'admin',
                        'is_active' => true
                    ], 'username = :username', ['username' => $username]);
                    
                    echo '<div class="success">✅ Admin user UPDATED successfully!</div>';
                } else {
                    // Create new user
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $db->insert('users', [
                        'username' => $username,
                        'password' => $hashed_password,
                        'email' => $email,
                        'full_name' => $full_name,
                        'role' => 'admin',
                        'is_active' => true,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    echo '<div class="success">✅ Admin user CREATED successfully!</div>';
                }
                
                echo '<div class="credentials">';
                echo '<h3 style="margin-top:0; color:#28a745;">📋 Admin Credentials</h3>';
                echo '<table>';
                echo '<tr><th>Username:</th><td><code>' . htmlspecialchars($username) . '</code></td></tr>';
                echo '<tr><th>Password:</th><td><code>' . htmlspecialchars($password) . '</code></td></tr>';
                echo '<tr><th>Email:</th><td><code>' . htmlspecialchars($email) . '</code></td></tr>';
                echo '<tr><th>Full Name:</th><td>' . htmlspecialchars($full_name) . '</td></tr>';
                echo '<tr><th>Role:</th><td><span style="background:#28a745; color:white; padding:4px 12px; border-radius:20px;">Administrator</span></td></tr>';
                echo '</table>';
                echo '</div>';
                
            } catch (Exception $e) {
                echo '<div class="error">❌ Failed to create/update admin: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        } else {
            echo '<div class="error">❌ Validation errors:<br>' . implode('<br>', array_map('htmlspecialchars', $errors)) . '</div>';
        }
    } elseif ($action === 'delete_self') {
        // SECURITY: This deletes this file
        if (unlink(__FILE__)) {
            echo '<div class="success">✅ Emergency admin creator file has been DELETED successfully!</div>';
            echo '<p>You can now close this window.</p>';
            echo '</div></body></html>';
            exit;
        } else {
            echo '<div class="error">❌ Could not delete this file. Please delete it manually!</div>';
        }
    } elseif ($action === 'list_users') {
        try {
            $users = $db->fetchAll("SELECT id, username, email, full_name, role, is_active, created_at, last_login FROM users ORDER BY id");
            
            echo '<h3>📊 All Users</h3>';
            echo '<table>';
            echo '<tr><th>ID</th><th>Username</th><th>Email</th><th>Full Name</th><th>Role</th><th>Status</th><th>Created</th></tr>';
            
            foreach ($users as $user) {
                echo '<tr>';
                echo '<td>' . $user['id'] . '</td>';
                echo '<td><strong>' . htmlspecialchars($user['username']) . '</strong></td>';
                echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                echo '<td>' . htmlspecialchars($user['full_name'] ?? '-') . '</td>';
                echo '<td><span style="background:' . ($user['role'] === 'admin' ? '#28a745' : '#6c757d') . '; color:white; padding:2px 8px; border-radius:12px;">' . $user['role'] . '</span></td>';
                echo '<td><span style="color:' . ($user['is_active'] ? '#28a745' : '#dc3545') . ';">' . ($user['is_active'] ? 'Active' : 'Inactive') . '</span></td>';
                echo '<td>' . date('Y-m-d', strtotime($user['created_at'])) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } catch (Exception $e) {
            echo '<div class="error">❌ Failed to list users: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// Show default form
?>

<div class="info-box">
    <strong>⚠️ SECURITY WARNING!</strong>
    <p style="margin-bottom:0;">This script creates admin users. <strong style="color:#dc3545;">DELETE THIS FILE IMMEDIATELY AFTER USE!</strong></p>
</div>

<form method="POST" action="" style="margin-top: 30px;">
    <input type="hidden" name="action" value="create_admin">
    
    <h3>Create/Update Admin User</h3>
    
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($NEW_ADMIN_USERNAME); ?>" 
               style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 16px;" required>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Password:</label>
        <input type="text" name="password" value="<?php echo htmlspecialchars($NEW_ADMIN_PASSWORD); ?>" 
               style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 16px;" required>
        <small style="color: #6c757d;">Minimum 6 characters</small>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($NEW_ADMIN_EMAIL); ?>" 
               style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 16px;" required>
    </div>
    
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($NEW_ADMIN_FULLNAME); ?>" 
               style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 16px;">
    </div>
    
    <button type="submit" style="background: #28a745; color: white; padding: 12px 30px; border: none; border-radius: 5px; font-size: 16px; font-weight: bold; cursor: pointer;">
        🔑 Create/Update Admin User
    </button>
</form>

<hr style="margin: 40px 0; border: 0; border-top: 2px solid #eee;">

<div style="display: flex; gap: 15px; flex-wrap: wrap;">
    <form method="POST" action="" style="display: inline;">
        <input type="hidden" name="action" value="list_users">
        <button type="submit" style="background: #17a2b8; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer;">
            📋 List All Users
        </button>
    </form>
    
    <form method="POST" action="" style="display: inline;" onsubmit="return confirm('⚠️ This will DELETE this emergency script file! Continue?');">
        <input type="hidden" name="action" value="delete_self">
        <button type="submit" style="background: #dc3545; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer;">
            🗑️ Delete This File (SECURITY)
        </button>
    </form>
    
    <a href="public/login.php" style="background: #6c757d; color: white; padding: 10px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
        🔐 Go to Login Page
    </a>
</div>

<div class="warning" style="margin-top: 40px;">
    <h4 style="margin-top: 0;">📌 Quick Setup Instructions:</h4>
    <ol style="margin-bottom: 0;">
        <li>Click the green button above to create your admin user</li>
        <li>Copy the displayed credentials</li>
        <li>Click "Delete This File" to remove this script</li>
        <li>Go to <a href="public/login.php" style="color: #856404;">Login Page</a> and sign in</li>
    </ol>
</div>

</div>
</body>
</html>
<?php
// No closing PHP tag to prevent whitespace issues
?>