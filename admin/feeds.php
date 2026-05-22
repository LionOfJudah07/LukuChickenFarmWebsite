<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Manage Feeds';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;

// Handle Delete
if ($action === 'delete' && $id) {
    try {
        $db->delete('feeds', 'id = :id', ['id' => $id]);
        $_SESSION['success'] = 'Feed deleted successfully';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Failed to delete feed: ' . $e->getMessage();
    }
    redirect('feeds.php');
}

// Handle Toggle Status
if ($action === 'toggle' && $id) {
    $feed = $db->fetchOne("SELECT is_active FROM feeds WHERE id = ?", [$id]);
    if ($feed) {
        $db->update('feeds', 
            ['is_active' => !$feed['is_active']], 
            'id = :id', 
            ['id' => $id]
        );
        $_SESSION['success'] = 'Feed status updated';
    }
    redirect('feeds.php');
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    $data = [
        'name_en' => $_POST['name_en'],
        'name_am' => $_POST['name_am'] ?: null,
        'name_om' => $_POST['name_om'] ?: null,
        'description_en' => $_POST['description_en'],
        'description_am' => $_POST['description_am'] ?: null,
        'description_om' => $_POST['description_om'] ?: null,
        'type' => $_POST['type'],
        'stage' => $_POST['stage'] ?: null,
        'form' => $_POST['form'],
        'price' => $_POST['price'] ?: null,
        'unit' => $_POST['unit'] ?: '50kg',
        'is_medicated' => isset($_POST['is_medicated']) ? 1 : 0,
        'display_order' => (int)$_POST['display_order'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload = uploadImage($_FILES['image'], 'feeds');
        if (isset($upload['success'])) {
            $data['image_url'] = $upload['url'];
        }
    }
    
    try {
        if ($action === 'add') {
            $db->insert('feeds', $data);
            $_SESSION['success'] = 'Feed added successfully';
        } else {
            $db->update('feeds', $data, 'id = :id', ['id' => $id]);
            $_SESSION['success'] = 'Feed updated successfully';
        }
        redirect('feeds.php');
    } catch (Exception $e) {
        $error = 'Failed: ' . $e->getMessage();
    }
}

// Get feed for editing
$feed = null;
if ($action === 'edit' && $id) {
    $feed = $db->fetchOne("SELECT * FROM feeds WHERE id = ?", [$id]);
}

// Get all feeds
$feeds = $db->fetchAll("SELECT * FROM feeds ORDER BY 
    CASE WHEN type = 'Starter' THEN 1
         WHEN type = 'Grower' THEN 2
         WHEN type = 'Layer' THEN 3
         WHEN type = 'Broiler' THEN 4
         ELSE 5 END,
    display_order");

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Feeds</h1>
        <?php if ($action === 'list'): ?>
        <a href="?action=add" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Add New Feed
        </a>
        <?php endif; ?>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Stage</th>
                            <th>Form</th>
                            <th>Price</th>
                            <th>Medicated</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($feeds as $f): ?>
                        <tr>
                            <td>
                                <?php if ($f['image_url']): ?>
                                    <img src="<?= $f['image_url'] ?>" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                <?php else: ?>
                                    <div class="bg-success rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-seedling text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($f['name_en']) ?></strong>
                                <?php if ($f['name_am']): ?>
                                <br><small class="text-muted"><?= htmlspecialchars($f['name_am']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= $f['type'] ?></td>
                            <td><?= $f['stage'] ?></td>
                            <td><?= $f['form'] ?></td>
                            <td><?= formatCurrency($f['price'] ?? 0) ?></td>
                            <td>
                                <?php if ($f['is_medicated']): ?>
                                <span class="badge bg-warning text-dark">Yes</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $f['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $f['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="?action=edit&id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?action=toggle&id=<?= $f['id'] ?>" class="btn btn-sm <?= $f['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                        <i class="fas <?= $f['is_active'] ? 'fa-ban' : 'fa-check' ?>"></i>
                                    </a>
                                    <a href="?action=delete&id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this feed?')">
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
            <h5 class="mb-0"><?= $action === 'add' ? 'Add New Feed' : 'Edit Feed' ?></h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <?= csrfField() ?>
                
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#en">English</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#am">Amharic</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#om">Oromo</button></li>
                </ul>
                
                <div class="tab-content">
                    <!-- English -->
                    <div class="tab-pane fade show active" id="en">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Feed Name (English) <span class="text-danger">*</span></label>
                                <input type="text" name="name_en" class="form-control" required value="<?= $feed ? htmlspecialchars($feed['name_en']) : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (English) <span class="text-danger">*</span></label>
                                <textarea name="description_en" class="form-control" rows="3" required><?= $feed ? htmlspecialchars($feed['description_en']) : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Amharic -->
                    <div class="tab-pane fade" id="am">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Feed Name (Amharic)</label>
                                <input type="text" name="name_am" class="form-control" value="<?= $feed ? htmlspecialchars($feed['name_am'] ?? '') : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (Amharic)</label>
                                <textarea name="description_am" class="form-control" rows="3"><?= $feed ? htmlspecialchars($feed['description_am'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Oromo -->
                    <div class="tab-pane fade" id="om">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Feed Name (Oromo)</label>
                                <input type="text" name="name_om" class="form-control" value="<?= $feed ? htmlspecialchars($feed['name_om'] ?? '') : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (Oromo)</label>
                                <textarea name="description_om" class="form-control" rows="3"><?= $feed ? htmlspecialchars($feed['description_om'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Feed Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Starter" <?= $feed && $feed['type'] == 'Starter' ? 'selected' : '' ?>>Starter</option>
                            <option value="Grower" <?= $feed && $feed['type'] == 'Grower' ? 'selected' : '' ?>>Grower</option>
                            <option value="Layer" <?= $feed && $feed['type'] == 'Layer' ? 'selected' : '' ?>>Layer</option>
                            <option value="Broiler" <?= $feed && $feed['type'] == 'Broiler' ? 'selected' : '' ?>>Broiler</option>
                            <option value="All-Flock" <?= $feed && $feed['type'] == 'All-Flock' ? 'selected' : '' ?>>All-Flock</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Growth Stage</label>
                        <input type="text" name="stage" class="form-control" placeholder="e.g., 0-6 weeks" value="<?= $feed ? htmlspecialchars($feed['stage'] ?? '') : '' ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Form <span class="text-danger">*</span></label>
                        <select name="form" class="form-select" required>
                            <option value="">Select Form</option>
                            <option value="Mash" <?= $feed && $feed['form'] == 'Mash' ? 'selected' : '' ?>>Mash</option>
                            <option value="Crumbles" <?= $feed && $feed['form'] == 'Crumbles' ? 'selected' : '' ?>>Crumbles</option>
                            <option value="Pellets" <?= $feed && $feed['form'] == 'Pellets' ? 'selected' : '' ?>>Pellets</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Price</label>
                        <div class="input-group">
                            <span class="input-group-text">ETB</span>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?= $feed ? $feed['price'] : '' ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label>Unit</label>
                        <input type="text" name="unit" class="form-control" value="<?= $feed ? htmlspecialchars($feed['unit'] ?? '50kg') : '50kg' ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label>Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="<?= $feed ? $feed['display_order'] : '0' ?>">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label>Feed Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <?php if ($feed && $feed['image_url']): ?>
                        <div class="mt-2">
                            <img src="<?= $feed['image_url'] ?>" style="max-height: 60px;">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="is_medicated" class="form-check-input" id="isMedicated" 
                                <?= $feed && $feed['is_medicated'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isMedicated">Medicated Feed</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked
                                <?= !$feed || $feed['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i><?= $action === 'add' ? 'Add Feed' : 'Update Feed' ?>
                    </button>
                    <a href="feeds.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php include 'includes/admin_footer.php'; ?>