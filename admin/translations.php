<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Manage Translations';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;
$category_filter = $_GET['category'] ?? '';

// Handle Delete
if ($action === 'delete' && $id) {
    $db->delete('translations', 'id = :id', ['id' => $id]);
    $_SESSION['success'] = 'Translation deleted successfully';
    redirect('translations.php' . ($category_filter ? '?category=' . $category_filter : ''));
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    $data = [
        'translation_key' => $_POST['translation_key'],
        'en' => $_POST['en'],
        'am' => $_POST['am'] ?: null,
        'om' => $_POST['om'] ?: null,
        'category' => $_POST['category'] ?: 'general'
    ];
    
    try {
        if ($action === 'add') {
            $db->insert('translations', $data);
            $_SESSION['success'] = 'Translation added successfully';
        } else {
            $db->update('translations', $data, 'id = :id', ['id' => $id]);
            $_SESSION['success'] = 'Translation updated successfully';
        }
        redirect('translations.php' . ($category_filter ? '?category=' . $category_filter : ''));
    } catch (Exception $e) {
        $error = 'Failed: ' . $e->getMessage();
    }
}

// Get translation for editing
$translation = null;
if ($action === 'edit' && $id) {
    $translation = $db->fetchOne("SELECT * FROM translations WHERE id = ?", [$id]);
}

// Build query for translations
$query = "SELECT * FROM translations";
$params = [];
if ($category_filter) {
    $query .= " WHERE category = ?";
    $params[] = $category_filter;
}
$query .= " ORDER BY category, translation_key";
$translations = $db->fetchAll($query, $params);

// Get unique categories
$categories = $db->fetchAll("SELECT DISTINCT category FROM translations ORDER BY category");

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Translations</h1>
        <?php if ($action === 'list'): ?>
        <a href="?action=add" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Add New Translation
        </a>
        <?php endif; ?>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
    
    <!-- Category Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <span class="me-3 fw-bold">Filter by Category:</span>
                <div class="btn-group">
                    <a href="translations.php" class="btn <?= !$category_filter ? 'btn-success' : 'btn-outline-secondary' ?>">All</a>
                    <?php foreach ($categories as $cat): ?>
                    <a href="?category=<?= urlencode($cat['category']) ?>" class="btn <?= $category_filter == $cat['category'] ? 'btn-success' : 'btn-outline-secondary' ?>">
                        <?= ucfirst($cat['category']) ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Key</th>
                            <th>English</th>
                            <th>Amharic</th>
                            <th>Oromo</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($translations as $t): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($t['translation_key']) ?></code></td>
                            <td><?= htmlspecialchars($t['en']) ?></td>
                            <td><?= htmlspecialchars($t['am'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($t['om'] ?? '-') ?></td>
                            <td><span class="badge bg-info"><?= $t['category'] ?></span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="?action=edit&id=<?= $t['id'] ?>&category=<?= urlencode($category_filter) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?action=delete&id=<?= $t['id'] ?>&category=<?= urlencode($category_filter) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this translation?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
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
            <h5 class="mb-0"><?= $action === 'add' ? 'Add New Translation' : 'Edit Translation' ?></h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <?= csrfField() ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Translation Key <span class="text-danger">*</span></label>
                        <input type="text" name="translation_key" class="form-control" required 
                               placeholder="e.g., nav_home, welcome_message"
                               value="<?= $translation ? htmlspecialchars($translation['translation_key']) : '' ?>"
                               <?= $action === 'edit' ? 'readonly' : '' ?>>
                        <small class="text-muted">Use lowercase with underscores</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Category</label>
                        <input type="text" name="category" class="form-control" 
                               placeholder="e.g., navigation, common, services"
                               value="<?= $translation ? htmlspecialchars($translation['category']) : 'general' ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>English <span class="text-danger">*</span></label>
                        <textarea name="en" class="form-control" rows="2" required><?= $translation ? htmlspecialchars($translation['en']) : '' ?></textarea>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Amharic (አማርኛ)</label>
                        <textarea name="am" class="form-control" rows="2"><?= $translation ? htmlspecialchars($translation['am'] ?? '') : '' ?></textarea>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Oromo (Afaan Oromoo)</label>
                        <textarea name="om" class="form-control" rows="2"><?= $translation ? htmlspecialchars($translation['om'] ?? '') : '' ?></textarea>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i><?= $action === 'add' ? 'Add Translation' : 'Update Translation' ?>
                    </button>
                    <a href="translations.php<?= $category_filter ? '?category=' . urlencode($category_filter) : '' ?>" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php include 'includes/admin_footer.php'; ?>