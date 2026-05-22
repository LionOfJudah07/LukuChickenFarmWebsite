<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Manage Services';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;

// Handle Delete
if ($action === 'delete' && $id) {
    $db->delete('services', 'id = :id', ['id' => $id]);
    $_SESSION['success'] = 'Service deleted successfully';
    redirect('services.php');
}

// Handle Toggle Status
if ($action === 'toggle' && $id) {
    $service = $db->fetchOne("SELECT is_active FROM services WHERE id = ?", [$id]);
    if ($service) {
        $db->update('services', ['is_active' => !$service['is_active']], 'id = :id', ['id' => $id]);
        $_SESSION['success'] = 'Service status updated';
    }
    redirect('services.php');
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    $data = [
        'title_en' => $_POST['title_en'],
        'title_am' => $_POST['title_am'] ?: null,
        'title_om' => $_POST['title_om'] ?: null,
        'description_en' => $_POST['description_en'],
        'description_am' => $_POST['description_am'] ?: null,
        'description_om' => $_POST['description_om'] ?: null,
        'icon' => $_POST['icon'],
        'display_order' => (int)$_POST['display_order'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];
    
    try {
        if ($action === 'add') {
            $db->insert('services', $data);
            $_SESSION['success'] = 'Service added successfully';
        } else {
            $db->update('services', $data, 'id = :id', ['id' => $id]);
            $_SESSION['success'] = 'Service updated successfully';
        }
        redirect('services.php');
    } catch (Exception $e) {
        $error = 'Failed: ' . $e->getMessage();
    }
}

$service = null;
if ($action === 'edit' && $id) {
    $service = $db->fetchOne("SELECT * FROM services WHERE id = ?", [$id]);
}

$services = $db->fetchAll("SELECT * FROM services ORDER BY display_order");

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Services</h1>
        <?php if ($action === 'list'): ?>
        <a href="?action=add" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Add New Service
        </a>
        <?php endif; ?>
    </div>
    
    <?php if ($action === 'list'): ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Icon</th>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $s): ?>
                        <tr>
                            <td>
                                <div class="bg-success bg-opacity-10 p-2 rounded-circle d-inline-block">
                                    <i class="fas fa-<?= $s['icon'] ?> text-success"></i>
                                </div>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($s['title_en']) ?></strong>
                                <?php if ($s['title_am']): ?>
                                <br><small class="text-muted"><?= htmlspecialchars($s['title_am']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= substr(htmlspecialchars($s['description_en']), 0, 50) ?>...</td>
                            <td><?= $s['display_order'] ?></td>
                            <td>
                                <span class="badge <?= $s['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $s['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="?action=edit&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?action=toggle&id=<?= $s['id'] ?>" class="btn btn-sm <?= $s['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                        <i class="fas <?= $s['is_active'] ? 'fa-ban' : 'fa-check' ?>"></i>
                                    </a>
                                    <a href="?action=delete&id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this service?')">
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
            <h5 class="mb-0"><?= $action === 'add' ? 'Add New Service' : 'Edit Service' ?></h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <?= csrfField() ?>
                
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#en">English</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#am">Amharic</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#om">Oromo</button></li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="en">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Service Title (English) <span class="text-danger">*</span></label>
                                <input type="text" name="title_en" class="form-control" required value="<?= $service ? htmlspecialchars($service['title_en']) : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (English) <span class="text-danger">*</span></label>
                                <textarea name="description_en" class="form-control" rows="4" required><?= $service ? htmlspecialchars($service['description_en']) : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="am">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Service Title (Amharic)</label>
                                <input type="text" name="title_am" class="form-control" value="<?= $service ? htmlspecialchars($service['title_am'] ?? '') : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (Amharic)</label>
                                <textarea name="description_am" class="form-control" rows="4"><?= $service ? htmlspecialchars($service['description_am'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="om">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Service Title (Oromo)</label>
                                <input type="text" name="title_om" class="form-control" value="<?= $service ? htmlspecialchars($service['title_om'] ?? '') : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (Oromo)</label>
                                <textarea name="description_om" class="form-control" rows="4"><?= $service ? htmlspecialchars($service['description_om'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Icon <span class="text-danger">*</span></label>
                        <select name="icon" class="form-select" required>
                            <option value="">Select Icon</option>
                            <option value="building" <?= $service && $service['icon'] == 'building' ? 'selected' : '' ?>>Building (Coop)</option>
                            <option value="chat" <?= $service && $service['icon'] == 'chat' ? 'selected' : '' ?>>Chat (Consultation)</option>
                            <option value="gear" <?= $service && $service['icon'] == 'gear' ? 'selected' : '' ?>>Gear (Setup)</option>
                            <option value="scissors" <?= $service && $service['icon'] == 'scissors' ? 'selected' : '' ?>>Scissors (Beak)</option>
                            <option value="syringe" <?= $service && $service['icon'] == 'syringe' ? 'selected' : '' ?>>Syringe (Vaccination)</option>
                            <option value="shield" <?= $service && $service['icon'] == 'shield' ? 'selected' : '' ?>>Shield (Prevention)</option>
                            <option value="basket" <?= $service && $service['icon'] == 'basket' ? 'selected' : '' ?>>Basket (Feed)</option>
                            <option value="pill" <?= $service && $service['icon'] == 'pill' ? 'selected' : '' ?>>Pill (Medicine)</option>
                            <option value="chicken" <?= $service && $service['icon'] == 'chicken' ? 'selected' : '' ?>>Chicken (Meat)</option>
                            <option value="truck" <?= $service && $service['icon'] == 'truck' ? 'selected' : '' ?>>Truck (Delivery)</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="<?= $service ? $service['display_order'] : '0' ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked
                                <?= !$service || $service['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i><?= $action === 'add' ? 'Add Service' : 'Update Service' ?>
                    </button>
                    <a href="services.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php include 'includes/admin_footer.php'; ?>