<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/language.php';

$current_page = basename($_SERVER['PHP_SELF']);
$is_logged_in = isLoggedIn();
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?php echo url('/public/'); ?>">
            <span class="fw-bold text-success">LUKU</span> Farm
            <small class="d-block fs-6 text-muted"><?php echo __('Expertise begets quality'); ?></small>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" 
                       href="<?php echo url('/public/'); ?>">
                        <?php echo __('nav_home'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'about.php' ? 'active' : ''; ?>" 
                       href="<?php echo url('/public/about.php'); ?>">
                        <?php echo __('nav_about'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'services.php' ? 'active' : ''; ?>" 
                       href="<?php echo url('/public/services.php'); ?>">
                        <?php echo __('nav_services'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'locations.php' ? 'active' : ''; ?>" 
                       href="<?php echo url('/public/locations.php'); ?>">
                        <?php echo __('nav_locations'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'partner.php' ? 'active' : ''; ?>" 
                       href="<?php echo url('/public/partner.php'); ?>">
                        <?php echo __('nav_partner'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>" 
                       href="<?php echo url('/public/contact.php'); ?>">
                        <?php echo __('nav_contact'); ?>
                    </a>
                </li>
                
                <?php if ($is_logged_in): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['username'] ?? 'Admin'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <?php if (isAdmin()): ?>
                        <li><a class="dropdown-item" href="<?php echo url('/admin/dashboard.php'); ?>">
                            <i class="fas fa-dashboard"></i> Dashboard
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="<?php echo url('/public/logout.php'); ?>">
                            <i class="fas fa-sign-out-alt"></i> <?php echo __('nav_logout'); ?>
                        </a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'login.php' ? 'active' : ''; ?>" 
                       href="<?php echo url('/public/login.php'); ?>">
                        <i class="fas fa-sign-in-alt"></i> <?php echo __('nav_login'); ?>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item ms-2">
                    <button id="themeToggle" class="btn btn-outline-secondary btn-sm" title="Toggle dark/light mode">
                        <i class="fas fa-moon"></i>
                    </button>
                </li>
                
                <li class="nav-item ms-2">
                    <?php echo languageSwitcher('d-inline'); ?>
                </li>
            </ul>
        </div>
    </div>
</nav>