<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Manage Locations';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrf($csrf_token)) {
        $error = 'Invalid CSRF token';
    } else {
        if ($action === 'add' || $action === 'edit') {
            $data = [
                'branch_name_en' => $_POST['branch_name_en'],
                'branch_name_am' => $_POST['branch_name_am'] ?? null,
                'branch_name_om' => $_POST['branch_name_om'] ?? null,
                'address_en' => $_POST['address_en'],
                'address_am' => $_POST['address_am'] ?? null,
                'address_om' => $_POST['address_om'] ?? null,
                'phone' => $_POST['phone'],
                'phone_secondary' => $_POST['phone_secondary'] ?? null,
                'email' => $_POST['email'] ?? null,
                'latitude' => $_POST['latitude'] ?? null,
                'longitude' => $_POST['longitude'] ?? null,
                'opening_hours_en' => $_POST['opening_hours_en'],
                'opening_hours_am' => $_POST['opening_hours_am'] ?? null,
                'opening_hours_om' => $_POST['opening_hours_om'] ?? null,
                'description_en' => $_POST['description_en'],
                'description_am' => $_POST['description_am'] ?? null,
                'description_om' => $_POST['description_om'] ?? null,
                'is_head_office' => isset($_POST['is_head_office']) ? true : false,
                'is_active' => isset($_POST['is_active']) ? true : false,
                'display_order' => $_POST['display_order'] ?? 0
            ];
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_result = uploadImage($_FILES['image'], 'locations');
                if (isset($upload_result['success'])) {
                    $data['image_url'] = $upload_result['url'];
                }
            }
            
            try {
                if ($action === 'add') {
                    $db->insert('locations', $data);
                    $_SESSION['success'] = 'Location added successfully';
                } else {
                    $db->update('locations', $data, 'id = :id', ['id' => $id]);
                    $_SESSION['success'] = 'Location updated successfully';
                }
                redirect('locations.php');
            } catch (Exception $e) {
                $error = 'Failed to save location: ' . $e->getMessage();
            }
        } elseif ($action === 'delete') {
            try {
                $db->delete('locations', 'id = :id', ['id' => $id]);
                $_SESSION['success'] = 'Location deleted successfully';
            } catch (Exception $e) {
                $_SESSION['error'] = 'Failed to delete location: ' . $e->getMessage();
            }
            redirect('locations.php');
        } elseif ($action === 'toggle') {
            $location = $db->fetchOne("SELECT is_active FROM locations WHERE id = ?", [$id]);
            if ($location) {
                $db->update('locations', 
                    ['is_active' => !$location['is_active']], 
                    'id = :id', 
                    ['id' => $id]
                );
                $_SESSION['success'] = 'Location status updated';
            }
            redirect('locations.php');
        }
    }
}

// Get location data for editing
$location = null;
if ($action === 'edit' && $id) {
    $location = $db->fetchOne("SELECT * FROM locations WHERE id = ?", [$id]);
    if (!$location) {
        redirect('locations.php');
    }
}

// Get all locations for listing
$locations = $db->fetchAll(
    "SELECT * FROM locations ORDER BY is_head_office DESC, display_order ASC, branch_name_en ASC"
);

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Locations</h1>
        <?php if ($action === 'list'): ?>
        <a href="?action=add" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i>Add New Location
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
    
    <?php if ($action === 'list'): ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 30px;"></th>
                            <th>Branch Name</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Hours</th>
                            <th style="width: 150px;">Status</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($locations)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">No locations found</p>
                                <a href="?action=add" class="btn btn-sm btn-success mt-3">
                                    <i class="fas fa-plus-circle me-2"></i>Add Your First Location
                                </a>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($locations as $loc): ?>
                        <tr>
                            <td>
                                <?php if ($loc['is_head_office']): ?>
                                <span class="badge bg-primary" title="Head Office">
                                    <i class="fas fa-star"></i>
                                </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($loc['branch_name_en']); ?></strong>
                                <?php if ($loc['branch_name_am']): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($loc['branch_name_am']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($loc['address_en']); ?>
                            </td>
                            <td>
                                <i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($loc['phone']); ?>
                                <?php if ($loc['phone_secondary']): ?>
                                <br><small><?php echo htmlspecialchars($loc['phone_secondary']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?php echo htmlspecialchars($loc['opening_hours_en']); ?></small>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge <?php echo $loc['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $loc['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                    <a href="?action=toggle&id=<?php echo $loc['id']; ?>" 
                                       class="btn btn-sm <?php echo $loc['is_active'] ? 'btn-warning' : 'btn-success'; ?>"
                                       onclick="return confirm('Toggle status for this location?')">
                                        <?php echo $loc['is_active'] ? 'Deactivate' : 'Activate'; ?>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="?action=edit&id=<?php echo $loc['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?action=delete&id=<?php echo $loc['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure you want to delete this location? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><?php echo $action === 'add' ? 'Add New Location' : 'Edit Location'; ?></h5>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <?php echo csrfField(); ?>
                
                <ul class="nav nav-tabs mb-4" id="locationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab">English</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="amharic-tab" data-bs-toggle="tab" data-bs-target="#amharic" type="button" role="tab">Amharic</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="oromo-tab" data-bs-toggle="tab" data-bs-target="#oromo" type="button" role="tab">Afaan Oromo</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="locationTabContent">
                    <!-- English -->
                    <div class="tab-pane fade show active" id="english" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Branch Name (English) <span class="text-danger">*</span></label>
                                <input type="text" name="branch_name_en" class="form-control" required
                                       value="<?php echo $location ? htmlspecialchars($location['branch_name_en']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address (English) <span class="text-danger">*</span></label>
                                <input type="text" name="address_en" class="form-control" required
                                       value="<?php echo $location ? htmlspecialchars($location['address_en']) : ''; ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description (English) <span class="text-danger">*</span></label>
                                <textarea name="description_en" class="form-control" rows="3" required><?php echo $location ? htmlspecialchars($location['description_en']) : ''; ?></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Opening Hours (English) <span class="text-danger">*</span></label>
                                <input type="text" name="opening_hours_en" class="form-control" required
                                       value="<?php echo $location ? htmlspecialchars($location['opening_hours_en']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Amharic -->
                    <div class="tab-pane fade" id="amharic" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Branch Name (Amharic)</label>
                                <input type="text" name="branch_name_am" class="form-control"
                                       value="<?php echo $location ? htmlspecialchars($location['branch_name_am']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address (Amharic)</label>
                                <input type="text" name="address_am" class="form-control"
                                       value="<?php echo $location ? htmlspecialchars($location['address_am']) : ''; ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description (Amharic)</label>
                                <textarea name="description_am" class="form-control" rows="3"><?php echo $location ? htmlspecialchars($location['description_am']) : ''; ?></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Opening Hours (Amharic)</label>
                                <input type="text" name="opening_hours_am" class="form-control"
                                       value="<?php echo $location ? htmlspecialchars($location['opening_hours_am']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Afaan Oromo -->
                    <div class="tab-pane fade" id="oromo" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Branch Name (Oromo)</label>
                                <input type="text" name="branch_name_om" class="form-control"
                                       value="<?php echo $location ? htmlspecialchars($location['branch_name_om']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address (Oromo)</label>
                                <input type="text" name="address_om" class="form-control"
                                       value="<?php echo $location ? htmlspecialchars($location['address_om']) : ''; ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description (Oromo)</label>
                                <textarea name="description_om" class="form-control" rows="3"><?php echo $location ? htmlspecialchars($location['description_om']) : ''; ?></textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Opening Hours (Oromo)</label>
                                <input type="text" name="opening_hours_om" class="form-control"
                                       value="<?php echo $location ? htmlspecialchars($location['opening_hours_om']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" required
                               value="<?php echo $location ? htmlspecialchars($location['phone']) : ''; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Secondary Phone</label>
                        <input type="tel" name="phone_secondary" class="form-control"
                               value="<?php echo $location ? htmlspecialchars($location['phone_secondary']) : ''; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="<?php echo $location ? htmlspecialchars($location['email']) : ''; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" name="display_order" class="form-control" min="0"
                               value="<?php echo $location ? $location['display_order'] : '0'; ?>">
                        <small class="text-muted">Lower numbers appear first</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="any" name="latitude" class="form-control"
                               value="<?php echo $location ? $location['latitude'] : ''; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="number" step="any" name="longitude" class="form-control"
                               value="<?php echo $location ? $location['longitude'] : ''; ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branch Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <?php if ($location && $location['image_url']): ?>
                        <div class="mt-2">
                            <img src="<?php echo $location['image_url']; ?>" alt="Branch" style="max-height: 100px;">
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_head_office" class="form-check-input" id="isHeadOffice"
                                   <?php echo ($location && $location['is_head_office']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="isHeadOffice">This is Head Office</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" checked
                                   <?php echo ($location && !$location['is_active']) ? '' : 'checked'; ?>>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i><?php echo $action === 'add' ? 'Add Location' : 'Update Location'; ?>
                    </button>
                    <a href="locations.php" class="btn btn-outline-secondary ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php endif; ?>
</div>

<?php include 'includes/admin_footer.php'; ?>