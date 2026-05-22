<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Manage Medicines';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;

// Handle Delete
if ($action === 'delete' && $id) {
    $db->delete('medicines', 'id = :id', ['id' => $id]);
    $_SESSION['success'] = 'Medicine deleted successfully';
    redirect('medicines.php');
}

// Handle Toggle Status
if ($action === 'toggle' && $id) {
    $medicine = $db->fetchOne("SELECT is_active FROM medicines WHERE id = ?", [$id]);
    if ($medicine) {
        $db->update('medicines', ['is_active' => !$medicine['is_active']], 'id = :id', ['id' => $id]);
        $_SESSION['success'] = 'Medicine status updated';
    }
    redirect('medicines.php');
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
        'category' => $_POST['category'],
        'usage_en' => $_POST['usage_en'],
        'usage_am' => $_POST['usage_am'] ?: null,
        'usage_om' => $_POST['usage_om'] ?: null,
        'dosage' => $_POST['dosage'],
        'price' => $_POST['price'] ?: null,
        'display_order' => (int)$_POST['display_order'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];
    
    try {
        if ($action === 'add') {
            $db->insert('medicines', $data);
            $_SESSION['success'] = 'Medicine added successfully';
        } else {
            $db->update('medicines', $data, 'id = :id', ['id' => $id]);
            $_SESSION['success'] = 'Medicine updated successfully';
        }
        redirect('medicines.php');
    } catch (Exception $e) {
        $error = 'Failed: ' . $e->getMessage();
    }
}

$medicine = null;
if ($action === 'edit' && $id) {
    $medicine = $db->fetchOne("SELECT * FROM medicines WHERE id = ?", [$id]);
}

$medicines = $db->fetchAll("SELECT * FROM medicines ORDER BY 
    CASE category 
        WHEN 'Antibiotic' THEN 1
        WHEN 'Antiparasitic' THEN 2
        WHEN 'Support' THEN 3
        ELSE 4 
    END,
    display_order");

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Medicines</h1>
        <?php if ($action === 'list'): ?>
        <a href="?action=add" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Add New Medicine
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
                            <th>Medicine Name</th>
                            <th>Category</th>
                            <th>Dosage</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medicines as $m): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($m['name_en']) ?></strong>
                                <?php if ($m['name_am']): ?>
                                <br><small class="text-muted"><?= htmlspecialchars($m['name_am']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-info"><?= $m['category'] ?></span>
                            </td>
                            <td><?= htmlspecialchars($m['dosage']) ?></td>
                            <td><?= $m['price'] ? formatCurrency($m['price']) : '-' ?></td>
                            <td>
                                <span class="badge <?= $m['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $m['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="?action=edit&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?action=toggle&id=<?= $m['id'] ?>" class="btn btn-sm <?= $m['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                        <i class="fas <?= $m['is_active'] ? 'fa-ban' : 'fa-check' ?>"></i>
                                    </a>
                                    <a href="?action=delete&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this medicine?')">
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
            <h5 class="mb-0"><?= $action === 'add' ? 'Add New Medicine' : 'Edit Medicine' ?></h5>
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
                                <label>Medicine Name (English) <span class="text-danger">*</span></label>
                                <input type="text" name="name_en" class="form-control" required value="<?= $medicine ? htmlspecialchars($medicine['name_en']) : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (English) <span class="text-danger">*</span></label>
                                <textarea name="description_en" class="form-control" rows="3" required><?= $medicine ? htmlspecialchars($medicine['description_en']) : '' ?></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Usage Instructions (English)</label>
                                <textarea name="usage_en" class="form-control" rows="2"><?= $medicine ? htmlspecialchars($medicine['usage_en'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="am">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Medicine Name (Amharic)</label>
                                <input type="text" name="name_am" class="form-control" value="<?= $medicine ? htmlspecialchars($medicine['name_am'] ?? '') : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (Amharic)</label>
                                <textarea name="description_am" class="form-control" rows="3"><?= $medicine ? htmlspecialchars($medicine['description_am'] ?? '') : '' ?></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Usage Instructions (Amharic)</label>
                                <textarea name="usage_am" class="form-control" rows="2"><?= $medicine ? htmlspecialchars($medicine['usage_am'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="om">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Medicine Name (Oromo)</label>
                                <input type="text" name="name_om" class="form-control" value="<?= $medicine ? htmlspecialchars($medicine['name_om'] ?? '') : '' ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description (Oromo)</label>
                                <textarea name="description_om" class="form-control" rows="3"><?= $medicine ? htmlspecialchars($medicine['description_om'] ?? '') : '' ?></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Usage Instructions (Oromo)</label>
                                <textarea name="usage_om" class="form-control" rows="2"><?= $medicine ? htmlspecialchars($medicine['usage_om'] ?? '') : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="Antibiotic" <?= $medicine && $medicine['category'] == 'Antibiotic' ? 'selected' : '' ?>>Antibiotic</option>
                            <option value="Antiparasitic" <?= $medicine && $medicine['category'] == 'Antiparasitic' ? 'selected' : '' ?>>Antiparasitic</option>
                            <option value="Support" <?= $medicine && $medicine['category'] == 'Support' ? 'selected' : '' ?>>Support & Supplements</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label>Dosage</label>
                        <input type="text" name="dosage" class="form-control" placeholder="e.g., 10mg/kg" value="<?= $medicine ? htmlspecialchars($medicine['dosage']) : '' ?>">
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label>Price</label>
                        <div class="input-group">
                            <span class="input-group-text">ETB</span>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?= $medicine ? $medicine['price'] : '' ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label>Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="<?= $medicine ? $medicine['display_order'] : '0' ?>">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked
                                <?= !$medicine || $medicine['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i><?= $action === 'add' ? 'Add Medicine' : 'Update Medicine' ?>
                    </button>
                    <a href="medicines.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php include 'includes/admin_footer.php'; ?>