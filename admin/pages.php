<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Manage Pages';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrf($csrf_token)) {
        $error = 'Invalid CSRF token';
    } else {
        if ($action === 'edit') {
            $data = [
                'title_en' => $_POST['title_en'],
                'title_am' => $_POST['title_am'] ?? null,
                'title_om' => $_POST['title_om'] ?? null,
                'content_en' => $_POST['content_en'],
                'content_am' => $_POST['content_am'] ?? null,
                'content_om' => $_POST['content_om'] ?? null,
                'meta_description_en' => $_POST['meta_description_en'] ?? null,
                'meta_description_am' => $_POST['meta_description_am'] ?? null,
                'meta_description_om' => $_POST['meta_description_om'] ?? null,
                'is_published' => isset($_POST['is_published']) ? true : false,
                'updated_by' => $_SESSION['user_id']
            ];
            
            try {
                $db->update('pages', $data, 'id = :id', ['id' => $id]);
                $_SESSION['success'] = 'Page updated successfully';
                redirect('pages.php');
            } catch (Exception $e) {
                $error = 'Failed to update page: ' . $e->getMessage();
            }
        }
    }
}

// Get all pages
$pages = $db->fetchAll("SELECT * FROM pages ORDER BY page_name");

// Get page for editing
$page = null;
if ($action === 'edit' && $id) {
    $page = $db->fetchOne("SELECT * FROM pages WHERE id = ?", [$id]);
}

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Pages</h1>
    </div>
    
    <?php if ($action === 'list'): ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Page Name</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $p): ?>
                        <tr>
                            <td><strong><?php echo ucfirst($p['page_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($p['title_en']); ?></td>
                            <td>
                                <span class="badge <?php echo $p['is_published'] ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $p['is_published'] ? 'Published' : 'Draft'; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y H:i', strtotime($p['updated_at'])); ?></td>
                            <td>
                                <a href="?action=edit&id=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php elseif ($action === 'edit' && $page): ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Edit Page: <?php echo ucfirst($page['page_name']); ?></h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <?php echo csrfField(); ?>
                
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#en">English</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#am">Amharic</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#om">Afaan Oromo</button>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <!-- English -->
                    <div class="tab-pane fade show active" id="en">
                        <div class="mb-3">
                            <label class="form-label">Page Title (English)</label>
                            <input type="text" name="title_en" class="form-control" value="<?php echo htmlspecialchars($page['title_en']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meta Description (English)</label>
                            <textarea name="meta_description_en" class="form-control" rows="2"><?php echo htmlspecialchars($page['meta_description_en']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content (English)</label>
                            <textarea name="content_en" class="form-control" rows="15"><?php echo htmlspecialchars($page['content_en']); ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Amharic -->
                    <div class="tab-pane fade" id="am">
                        <div class="mb-3">
                            <label class="form-label">Page Title (Amharic)</label>
                            <input type="text" name="title_am" class="form-control" value="<?php echo htmlspecialchars($page['title_am']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content (Amharic)</label>
                            <textarea name="content_am" class="form-control" rows="15"><?php echo htmlspecialchars($page['content_am']); ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Oromo -->
                    <div class="tab-pane fade" id="om">
                        <div class="mb-3">
                            <label class="form-label">Page Title (Oromo)</label>
                            <input type="text" name="title_om" class="form-control" value="<?php echo htmlspecialchars($page['title_om']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content (Oromo)</label>
                            <textarea name="content_om" class="form-control" rows="15"><?php echo htmlspecialchars($page['content_om']); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_published" class="form-check-input" id="isPublished" <?php echo $page['is_published'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="isPublished">Published</label>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Update Page
                    </button>
                    <a href="pages.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php include 'includes/admin_footer.php'; ?>