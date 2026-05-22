<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$db = Database::getInstance();
$page_title = 'Delivery Settings';

// Get current delivery settings
$settings = $db->fetchAll("SELECT * FROM delivery_settings");
$delivery_settings = [];
foreach ($settings as $setting) {
    $delivery_settings[$setting['setting_key']] = $setting['setting_value'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrf($csrf_token)) {
        $error = 'Invalid CSRF token';
    } else {
        try {
            $db->beginTransaction();
            
            // Update each setting
            $setting_keys = ['delivery_enabled', 'min_weight_kg', 'delivery_fee', 'delivery_fee_per_km', 'free_delivery_above'];
            
            foreach ($setting_keys as $key) {
                $value = $_POST[$key] ?? '';
                
                if ($key === 'delivery_enabled') {
                    $value = isset($_POST[$key]) ? 'true' : 'false';
                }
                
                $db->query(
                    "UPDATE delivery_settings SET setting_value = ?, updated_at = CURRENT_TIMESTAMP, updated_by = ? WHERE setting_key = ?",
                    [$value, $_SESSION['user_id'], $key]
                );
            }
            
            $db->commit();
            $_SESSION['success'] = 'Delivery settings updated successfully';
            redirect('delivery.php');
        } catch (Exception $e) {
            $db->rollBack();
            $error = 'Failed to update settings: ' . $e->getMessage();
        }
    }
}

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Delivery Settings</h1>
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
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Configure Feed Delivery Service</h5>
                    <p class="text-muted small mb-0">Set rules and pricing for feed delivery service</p>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <?php echo csrfField(); ?>
                        
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="delivery_enabled" id="deliveryEnabled" 
                                       <?php echo ($delivery_settings['delivery_enabled'] ?? 'true') === 'true' ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="deliveryEnabled">
                                    <strong>Enable Delivery Service</strong>
                                </label>
                                <div class="text-muted small">Turn delivery service on/off for the website</div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Minimum Order Weight (KG)</label>
                                <div class="input-group">
                                    <input type="number" name="min_weight_kg" class="form-control" 
                                           value="<?php echo $delivery_settings['min_weight_kg'] ?? 400; ?>" 
                                           min="0" step="1" required>
                                    <span class="input-group-text">KG</span>
                                </div>
                                <div class="text-muted small mt-1">Orders below this weight are not eligible for delivery</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Base Delivery Fee</label>
                                <div class="input-group">
                                    <span class="input-group-text">ETB</span>
                                    <input type="number" name="delivery_fee" class="form-control" 
                                           value="<?php echo $delivery_settings['delivery_fee'] ?? 1500; ?>" 
                                           min="0" step="0.01" required>
                                </div>
                                <div class="text-muted small mt-1">Standard delivery fee within Addis Ababa</div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Per Kilometer Charge</label>
                                <div class="input-group">
                                    <span class="input-group-text">ETB</span>
                                    <input type="number" name="delivery_fee_per_km" class="form-control" 
                                           value="<?php echo $delivery_settings['delivery_fee_per_km'] ?? 50; ?>" 
                                           min="0" step="0.01">
                                    <span class="input-group-text">/km</span>
                                </div>
                                <div class="text-muted small mt-1">Additional charge per kilometer from nearest branch</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Free Delivery Above (KG)</label>
                                <div class="input-group">
                                    <input type="number" name="free_delivery_above" class="form-control" 
                                           value="<?php echo $delivery_settings['free_delivery_above'] ?? 2000; ?>" 
                                           min="0" step="1">
                                    <span class="input-group-text">KG</span>
                                </div>
                                <div class="text-muted small mt-1">Orders above this weight get free delivery (set 0 to disable)</div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <h6><i class="fas fa-info-circle me-2"></i>Current Delivery Rules</h6>
                            <ul class="mb-0">
                                <li>Delivery is <?php echo ($delivery_settings['delivery_enabled'] ?? 'true') === 'true' ? 'ENABLED' : 'DISABLED'; ?></li>
                                <li>Minimum order: <?php echo $delivery_settings['min_weight_kg'] ?? 400; ?> KG</li>
                                <li>Base delivery fee: <?php echo formatCurrency($delivery_settings['delivery_fee'] ?? 1500); ?></li>
                                <li>Additional charge: <?php echo formatCurrency($delivery_settings['delivery_fee_per_km'] ?? 50); ?>/km</li>
                                <li>Free delivery for orders above <?php echo $delivery_settings['free_delivery_above'] ?? 2000; ?> KG</li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Save Delivery Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Delivery Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Service Area:</strong> Addis Ababa and surrounding areas</p>
                    <p><strong>Delivery Time:</strong> Within 24-48 hours</p>
                    <p><strong>Payment:</strong> Cash on delivery or bank transfer</p>
                    <hr>
                    <h6>Rules Displayed on Website:</h6>
                    <ul class="small">
                        <li>Delivery available for orders above 400 KG</li>
                        <li>Reasonable delivery fee applies</li>
                        <li>Free delivery for bulk orders</li>
                        <li>Contact branches for exact delivery charges</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <?php
                    // Get recent orders stats (if you have orders table)
                    // This is placeholder data
                    ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Deliveries this month:</span>
                        <strong>156</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Average order weight:</span>
                        <strong>850 KG</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Most active branch:</span>
                        <strong>Head Office</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>