<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Manage Users';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrf($csrf_token)) {
        $error = 'Invalid CSRF token';
    } else {
        if ($action === 'add' || $action === 'edit') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $full_name = $_POST['full_name'];
            $role = $_POST['role'];
            $is_active = isset($_POST['is_active']) ? true : false;
            
            // Check if username exists
            $existing = $db->fetchOne(
                "SELECT id FROM users WHERE username = ? AND id != ?",
                [$username, $id]
            );
            
            if ($existing) {
                $error = 'Username already exists';
            } else {
                // Check if email exists
                $existing_email = $db->fetchOne(
                    "SELECT id FROM users WHERE email = ? AND id != ?",
                    [$email, $id]
                );
                
                if ($existing_email) {
                    $error = 'Email already exists';
                } else {
                    $data = [
                        'username' => $username,
                        'email' => $email,
                        'full_name' => $full_name,
                        'role' => $role,
                        'is_active' => $is_active
                    ];
                    
                    // Add password for new user
                    if ($action === 'add' && !empty($_POST['password'])) {
                        $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    }
                    
                    // Update password if provided
                    if ($action === 'edit' && !empty($_POST['password'])) {
                        $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    }
                    
                    try {
                        if ($action === 'add') {
                            $db->insert('users', $data);
                            $_SESSION['success'] = 'User added successfully';
                        } else {
                            $db->update('users', $data, 'id = :id', ['id' => $id]);
                            $_SESSION['success'] = 'User updated successfully';
                        }
                        redirect('users.php');
                    } catch (Exception $e) {
                        $error = 'Failed to save user: ' . $e->getMessage();
                    }
                }
            }
        } elseif ($action === 'delete') {
            // Don't allow deleting yourself
            if ($id == $_SESSION['user_id']) {
                $_SESSION['error'] = 'You cannot delete your own account';
            } else {
                try {
                    $db->delete('users', 'id = :id', ['id' => $id]);
                    $_SESSION['success'] = 'User deleted successfully';
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Failed to delete user: ' . $e->getMessage();
                }
            }
            redirect('users.php');
        } elseif ($action === 'toggle') {
            $user = $db->fetchOne("SELECT is_active FROM users WHERE id = ?", [$id]);
            if ($user) {
                // Don't allow deactivating yourself
                if ($id == $_SESSION['user_id']) {
                    $_SESSION['error'] = 'You cannot deactivate your own account';
                } else {
                    $db->update('users', 
                        ['is_active' => !$user['is_active']], 
                        'id = :id', 
                        ['id' => $id]
                    );
                    $_SESSION['success'] = 'User status updated';
                }
            }
            redirect('users.php');
        }
    }
}

// Get user data for editing
$user = null;
if ($action === 'edit' && $id) {
    $user = $db->fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
    if (!$user) {
        redirect('users.php');
    }
}

// Get all users
$users = $db->fetchAll(
    "SELECT * FROM users ORDER BY 
        CASE WHEN id = ? THEN 0 ELSE 1 END,
        created_at DESC",
    [$_SESSION['user_id']]
);

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Users</h1>
        <?php if ($action === 'list'): ?>
        <a href="?action=add" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Add New User
        </a>
        <?php endif; ?>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php 
        echo $_SESSION['success'];
        unset($_SESSION['success']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php 
        echo $_SESSION['error'];
        unset($_SESSION['error']);
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Last Login</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr class="<?php echo $u['id'] == $_SESSION['user_id'] ? 'table-primary' : ''; ?>">
                            <td>
                                <strong><?php echo htmlspecialchars($u['username']); ?></strong>
                                <?php if ($u['id'] == $_SESSION['user_id']): ?>
                                <span class="badge bg-info ms-2">You</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($u['full_name'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td>
                                <span class="badge <?php echo $u['role'] === 'admin' ? 'bg-danger' : 'bg-secondary'; ?>">
                                    <?php echo ucfirst($u['role']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $u['last_login'] ? date('M d, Y H:i', strtotime($u['last_login'])) : 'Never'; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $u['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $u['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="?action=edit&id=<?php echo $u['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                    <a href="?action=toggle&id=<?php echo $u['id']; ?>" 
                                       class="btn btn-sm <?php echo $u['is_active'] ? 'btn-outline-warning' : 'btn-outline-success'; ?>"
                                       onclick="return confirm('Toggle status for this user?')">
                                        <i class="fas <?php echo $u['is_active'] ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                    </a>
                                    <a href="?action=delete&id=<?php echo $u['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><?php echo $action === 'add' ? 'Add New User' : 'Edit User'; ?></h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <?php echo csrfField(); ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" required
                               value="<?php echo $user ? htmlspecialchars($user['username']) : ''; ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required
                               value="<?php echo $user ? htmlspecialchars($user['email']) : ''; ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control"
                               value="<?php echo $user ? htmlspecialchars($user['full_name']) : ''; ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="admin" <?php echo ($user && $user['role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                            <option value="editor" <?php echo ($user && $user['role'] == 'editor') ? 'selected' : ''; ?>>Editor</option>
                            <option value="viewer" <?php echo ($user && $user['role'] == 'viewer') ? 'selected' : ''; ?>>Viewer</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <?php echo $action === 'add' ? 'Password' : 'New Password (leave blank to keep current)'; ?>
                            <?php if ($action === 'add'): ?><span class="text-danger">*</span><?php endif; ?>
                        </label>
                        <input type="password" name="password" class="form-control" 
                               <?php echo $action === 'add' ? 'required' : ''; ?>>
                        <?php if ($action === 'edit'): ?>
                        <small class="text-muted">Leave empty to keep current password</small>
                        <?php endif; ?>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" 
                                   <?php echo (!$user || $user['is_active']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i><?php echo $action === 'add' ? 'Add User' : 'Update User'; ?>
                    </button>
                    <a href="users.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php include 'includes/admin_footer.php'; ?>