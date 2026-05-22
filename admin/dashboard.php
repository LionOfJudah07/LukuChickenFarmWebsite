<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/admin_auth.php';

requireAdmin();

$page_title = 'Admin Dashboard';

// Get statistics
$db = Database::getInstance();

$stats = [
    'total_services' => $db->fetchOne("SELECT COUNT(*) as count FROM services")['count'],
    'total_feeds' => $db->fetchOne("SELECT COUNT(*) as count FROM feeds")['count'],
    'total_vaccines' => $db->fetchOne("SELECT COUNT(*) as count FROM vaccines")['count'],
    'total_medicines' => $db->fetchOne("SELECT COUNT(*) as count FROM medicines")['count'],
    'total_locations' => $db->fetchOne("SELECT COUNT(*) as count FROM locations WHERE is_active = true")['count'],
    'total_messages' => $db->fetchOne("SELECT COUNT(*) as count FROM messages WHERE is_read = false")['count'],
    'total_users' => $db->fetchOne("SELECT COUNT(*) as count FROM users WHERE is_active = true")['count'],
    'total_products' => $db->fetchOne("SELECT COUNT(*) as count FROM products")['count'],
];

// Get recent messages
$recent_messages = $db->fetchAll(
    "SELECT * FROM messages ORDER BY created_at DESC LIMIT 5"
);

// Get recent locations
$recent_locations = $db->fetchAll(
    "SELECT * FROM locations ORDER BY created_at DESC LIMIT 5"
);

include 'includes/admin_header.php';
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Dashboard</h1>
            <p class="text-muted">Welcome back, <?php echo $_SESSION['full_name'] ?? $_SESSION['username']; ?>!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-cogs fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Services</h6>
                            <h3 class="mb-0"><?php echo $stats['total_services']; ?></h3>
                        </div>
                    </div>
                    <a href="services.php" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-seedling fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Feeds</h6>
                            <h3 class="mb-0"><?php echo $stats['total_feeds']; ?></h3>
                        </div>
                    </div>
                    <a href="feeds.php" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-syringe fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Vaccines</h6>
                            <h3 class="mb-0"><?php echo $stats['total_vaccines']; ?></h3>
                        </div>
                    </div>
                    <a href="vaccines.php" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-pills fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Medicines</h6>
                            <h3 class="mb-0"><?php echo $stats['total_medicines']; ?></h3>
                        </div>
                    </div>
                    <a href="medicines.php" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-map-marker-alt fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Locations</h6>
                            <h3 class="mb-0"><?php echo $stats['total_locations']; ?></h3>
                        </div>
                    </div>
                    <a href="locations.php" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-envelope fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Unread Messages</h6>
                            <h3 class="mb-0"><?php echo $stats['total_messages']; ?></h3>
                        </div>
                    </div>
                    <a href="messages.php" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Users</h6>
                            <h3 class="mb-0"><?php echo $stats['total_users']; ?></h3>
                        </div>
                    </div>
                    <a href="users.php" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger bg-opacity-10 p-3 rounded-3">
                            <i class="fas fa-truck fa-2x text-danger"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Delivery Settings</h6>
                            <h3 class="mb-0">Active</h3>
                        </div>
                    </div>
                    <a href="delivery.php" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Messages -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Messages</h5>
                        <a href="messages.php" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (empty($recent_messages)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No messages yet</p>
                        </div>
                        <?php else: ?>
                        <?php foreach ($recent_messages as $message): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($message['name']); ?></h6>
                                    <p class="mb-1 text-muted small"><?php echo htmlspecialchars($message['email']); ?></p>
                                    <p class="mb-0 small"><?php echo substr(htmlspecialchars($message['message']), 0, 100); ?>...</p>
                                </div>
                                <div class="text-end">
                                    <span class="badge <?php echo $message['is_read'] ? 'bg-secondary' : 'bg-success'; ?> mb-2">
                                        <?php echo $message['is_read'] ? 'Read' : 'Unread'; ?>
                                    </span>
                                    <small class="d-block text-muted"><?php echo date('M d, H:i', strtotime($message['created_at'])); ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Locations -->
        <div class="col-xl-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Locations</h5>
                        <a href="locations.php" class="btn btn-sm btn-outline-primary">Manage Locations</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (empty($recent_locations)): ?>
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No locations added yet</p>
                        </div>
                        <?php else: ?>
                        <?php foreach ($recent_locations as $location): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($location['branch_name_en']); ?></h6>
                                    <p class="mb-1 text-muted small"><?php echo htmlspecialchars($location['address_en']); ?></p>
                                    <p class="mb-0 small"><i class="fas fa-phone me-1"></i> <?php echo htmlspecialchars($location['phone']); ?></p>
                                </div>
                                <div>
                                    <?php if ($location['is_head_office']): ?>
                                    <span class="badge bg-primary">Head Office</span>
                                    <?php endif; ?>
                                    <span class="badge <?php echo $location['is_active'] ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $location['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="services.php?action=add" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-plus-circle me-2"></i> Add Service
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="feeds.php?action=add" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-plus-circle me-2"></i> Add Feed
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="locations.php?action=add" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-plus-circle me-2"></i> Add Location
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="translations.php?action=add" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-plus-circle me-2"></i> Add Translation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>