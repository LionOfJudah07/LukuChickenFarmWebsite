<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Manage Vaccines';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;

// Handle Delete
if ($action === 'delete' && $id) {
    $db->delete('vaccines', 'id = :id', ['id' => $id]);
    $_SESSION['success'] = 'Vaccine deleted successfully';
    redirect('vaccines.php');
}

// Handle Toggle Status
if ($action === 'toggle' && $id) {
    $vaccine = $db->fetchOne("SELECT is_active FROM vaccines WHERE id = ?", [$id]);
    if ($vaccine) {
        $db->update('vaccines', ['is_active' => !$vaccine['is_active']], 'id = :id', ['id' => $id]);
        $_SESSION['success'] = 'Vaccine status updated';
    }
    redirect('vaccines.php');
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
        'application_method' => $_POST['application_method'],
        'age_recommendation' => $_POST['age_recommendation'],
        'price' => $_POST['price'] ?: null,
        'display_order' => (int)$_POST['display_order'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];
    
    try {
        if ($action === 'add') {
            $db->insert('vaccines', $data);
            $_SESSION['success'] = 'Vaccine added successfully';
        } else {
            $db->update('vaccines', $data, 'id = :id', ['id' => $id]);
            $_SESSION['success'] = 'Vaccine updated successfully';
        }
        redirect('vaccines.php');
    } catch (Exception $e) {
        $error = 'Failed: ' . $e->getMessage();
    }
}

$vaccine = null;
if ($action === 'edit' && $id) {
    $vaccine = $db->fetchOne("SELECT * FROM vaccines WHERE id = ?", [$id]);
}

$vaccines = $db->fetchAll("SELECT * FROM vaccines ORDER BY display_order, name_en");

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Vaccines</h1>
        <?php if ($action === 'list'): ?>
        <a href="?action=add" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Add New Vaccine
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
                            <th>Vaccine Name</th>
                            <th>Application Method</th>
                            <th>Age Recommendation</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vaccines as $v): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($v['name_en']) ?></strong>
                                <?php if ($v['name_am']): ?>
                                <br><small class="text-muted"><?= htmlspecialchars($v['name_am']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($v['application_method']) ?></td>
                            <td><?= htmlspecialchars($v['age_recommendation']) ?></td>
                            <td><?= $v['price'] ? formatCurrency($v['price']) : '-' ?></td>
                            <td>
                                <span class="badge <?= $v['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $v['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="?action=edit&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?action=toggle&id=<?= $v['id'] ?>" class="btn btn-sm <?= $v['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                        <i class="fas <?= $v['is_active'] ? 'fa-ban' : 'fa-check' ?>"></i>
                                    </a>
                                    <a href="?action=delete&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this vaccine?')">
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
            <h5 class="mb-0"><?= $action === 'add' ? 'Add New Vaccine' : 'Edit Vaccine' ?></h5>
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
                                <label>Vaccine Name (English) <span class="text-danger">*</span></label>
                                <input type="text" name="name_en" class="form-control" required value="<?= $vaccine ? htmlspecialchars($vaccine['name_en']) : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (English) <span class="text-danger">*</span></label>
                                <textarea name="description_en" class="form-control" rows="3" required><?= $vaccine ? htmlspecialchars($vaccine['description_en']) : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="am">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Vaccine Name (Amharic)</label>
                                <input type="text" name="name_am" class="form-control" value="<?= $vaccine ? htmlspecialchars($vaccine['name_am'] ?? '') : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (Amharic)</label>
                                <textarea name="description_am" class="form-control" rows="3"><?= $vaccine ? htmlspecialchars($vaccine['description_am'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="om">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Vaccine Name (Oromo)</label>
                                <input type="text" name="name_om" class="form-control" value="<?= $vaccine ? htmlspecialchars($vaccine['name_om'] ?? '') : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (Oromo)</label>
                                <textarea name="description_om" class="form-control" rows="3"><?= $vaccine ? htmlspecialchars($vaccine['description_om'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Application Method <span class="text-danger">*</span></label>
                        <select name="application_method" class="form-select" required>
                            <option value="">Select Method</option>
                            <option value="Eye drop" <?= $vaccine && $vaccine['application_method'] == 'Eye drop' ? 'selected' : '' ?>>Eye drop</option>
                            <option value="Intranasal" <?= $vaccine && $vaccine['application_method'] == 'Intranasal' ? 'selected' : '' ?>>Intranasal</option>
                            <option value="Drinking water" <?= $vaccine && $vaccine['application_method'] == 'Drinking water' ? 'selected' : '' ?>>Drinking water</option>
                            <option value="Spray" <?= $vaccine && $vaccine['application_method'] == 'Spray' ? 'selected' : '' ?>>Spray</option>
                            <option value="Injection" <?= $vaccine && $vaccine['application_method'] == 'Injection' ? 'selected' : '' ?>>Injection</option>
                            <option value="Wing web" <?= $vaccine && $vaccine['application_method'] == 'Wing web' ? 'selected' : '' ?>>Wing web</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Age Recommendation</label>
                        <input type="text" name="age_recommendation" class="form-control" placeholder="e.g., Day 1-7" value="<?= $vaccine ? htmlspecialchars($vaccine['age_recommendation']) : '' ?>">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label>Price</label>
                        <div class="input-group">
                            <span class="input-group-text">ETB</span>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?= $vaccine ? $vaccine['price'] : '' ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label>Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="<?= $vaccine ? $vaccine['display_order'] : '0' ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked
                                <?= !$vaccine || $vaccine['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i><?= $action === 'add' ? 'Add Vaccine' : 'Update Vaccine' ?>
                    </button>
                    <a href="vaccines.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php include 'includes/admin_footer.php'; ?>