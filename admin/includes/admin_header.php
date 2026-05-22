<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin'; ?> - LUKU Farm Admin</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Admin Custom CSS -->
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark">
            <div class="sidebar-header p-3">
                <h4 class="text-white mb-0">LUKU Farm</h4>
                <small class="text-white-50">Admin Panel</small>
            </div>
            
            <div class="user-info bg-success p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-circle fa-2x text-white"></i>
                    </div>
                    <div class="flex-grow-1 ms-3 text-white">
                        <div class="fw-bold"><?php echo $_SESSION['full_name'] ?? $_SESSION['username']; ?></div>
                        <small><?php echo $_SESSION['user_role']; ?></small>
                    </div>
                </div>
            </div>
            
            <ul class="list-unstyled p-3">
                <li class="mb-2">
                    <a href="dashboard.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo $current_page == 'dashboard.php' ? 'bg-success' : ''; ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="mb-2">
                    <a href="services.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['services.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-cogs me-2"></i> Services
                    </a>
                </li>
                <li class="mb-2">
                    <a href="feeds.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['feeds.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-seedling me-2"></i> Feeds
                    </a>
                </li>
                <li class="mb-2">
                    <a href="vaccines.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['vaccines.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-syringe me-2"></i> Vaccines
                    </a>
                </li>
                <li class="mb-2">
                    <a href="medicines.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['medicines.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-pills me-2"></i> Medicines
                    </a>
                </li>
                <li class="mb-2">
                    <a href="locations.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['locations.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-map-marker-alt me-2"></i> Locations
                    </a>
                </li>
                <li class="mb-2">
                    <a href="delivery.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['delivery.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-truck me-2"></i> Delivery
                    </a>
                </li>
                <li class="mb-2">
                    <a href="pages.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['pages.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-file me-2"></i> Pages
                    </a>
                </li>
                <li class="mb-2">
                    <a href="translations.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['translations.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-language me-2"></i> Translations
                    </a>
                </li>
                <li class="mb-2">
                    <a href="messages.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['messages.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-envelope me-2"></i> Messages
                    </a>
                </li>
                <li class="mb-2">
                    <a href="users.php" class="text-white text-decoration-none d-block p-2 rounded <?php echo in_array($current_page, ['users.php']) ? 'bg-success' : ''; ?>">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                </li>
                <li class="mb-2 mt-4 pt-3 border-top border-secondary">
                    <a href="<?php echo BASE_URL; ?>/public/" class="text-white-50 text-decoration-none d-block p-2 rounded" target="_blank">
                        <i class="fas fa-globe me-2"></i> View Website
                    </a>
                </li>
                <li class="mb-2">
                    <a href="<?php echo BASE_URL; ?>/public/logout.php" class="text-white-50 text-decoration-none d-block p-2 rounded">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>
        
        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button type="button" id="sidebarToggle" class="btn btn-outline-secondary">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="ms-auto">
                        <span class="text-muted">
                            <i class="fas fa-clock me-1"></i> <?php echo date('M d, Y - H:i'); ?>
                        </span>
                    </div>
                </div>
            </nav>
            
            <div class="container-fluid py-4">