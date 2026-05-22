<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/language.php';

$page_title = __('Our Locations');
$db = Database::getInstance();

// Get all active locations
$locations = $db->fetchAll(
    "SELECT * FROM locations WHERE is_active = true ORDER BY is_head_office DESC, display_order ASC, branch_name_en ASC"
);

// Get head office for map
$head_office = $db->fetchOne(
    "SELECT * FROM locations WHERE is_head_office = true AND is_active = true"
);

include '../includes/header.php';
?>

<!-- Page Header -->
<section class="bg-success text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center" data-aos="fade-up">
                <h1 class="display-4 fw-bold"><?php echo __('our_branches'); ?></h1>
                <p class="lead"><?php echo __('Find LUKU Farm near you'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Main Map Section -->
<?php if ($head_office): ?>
<section class="py-4 bg-light">
    <div class="container">
        <div class="card border-0 shadow-lg" data-aos="fade-up">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-lg-4">
                        <div class="p-4">
                            <h3 class="fw-bold mb-3"><?php echo __('Head Office'); ?></h3>
                            <div class="d-flex mb-3">
                                <i class="fas fa-map-marker-alt text-success fa-fw me-2 mt-1"></i>
                                <div>
                                    <strong><?php echo __('Address'); ?>:</strong><br>
                                    <?php echo getLocalizedContent($head_office, 'address'); ?>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <i class="fas fa-phone text-success fa-fw me-2 mt-1"></i>
                                <div>
                                    <strong><?php echo __('Phone'); ?>:</strong><br>
                                    <?php echo $head_office['phone']; ?>
                                    <?php if ($head_office['phone_secondary']): ?>
                                    <br><?php echo $head_office['phone_secondary']; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <i class="fas fa-envelope text-success fa-fw me-2 mt-1"></i>
                                <div>
                                    <strong><?php echo __('Email'); ?>:</strong><br>
                                    <?php echo $head_office['email'] ?? 'info@lukufarm.com'; ?>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <i class="fas fa-clock text-success fa-fw me-2 mt-1"></i>
                                <div>
                                    <strong><?php echo __('opening_hours'); ?>:</strong><br>
                                    <?php echo getLocalizedContent($head_office, 'opening_hours'); ?>
                                </div>
                            </div>
                            <a href="https://maps.google.com/?q=<?php echo $head_office['latitude']; ?>,<?php echo $head_office['longitude']; ?>" 
                               target="_blank" class="btn btn-success mt-3">
                                <i class="fas fa-directions me-2"></i><?php echo __('Get Directions'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="ratio ratio-16x9">
                            <iframe 
                                src="https://maps.google.com/maps?q=<?php echo $head_office['latitude']; ?>,<?php echo $head_office['longitude']; ?>&hl=en&z=15&output=embed"
                                allowfullscreen
                                loading="lazy"
                                class="rounded-end">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- All Branches -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('All Branches'); ?></h2>
            <p class="lead text-muted"><?php echo __('Visit any of our ' . count($locations) . ' branches across Addis Ababa'); ?></p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($locations as $index => $location): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                <div class="card h-100 border-0 shadow-sm location-card-hover">
                    <div class="card-body p-4">
                        <?php if ($location['is_head_office']): ?>
                        <span class="badge bg-primary mb-3"><?php echo __('Head Office'); ?></span>
                        <?php endif; ?>
                        
                        <h4 class="fw-bold mb-3">
                            <?php echo getLocalizedContent($location, 'branch_name'); ?>
                        </h4>
                        
                        <div class="location-details">
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt text-success me-2"></i>
                                <?php echo getLocalizedContent($location, 'address'); ?>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-phone text-success me-2"></i>
                                <?php echo $location['phone']; ?>
                                <?php if ($location['phone_secondary']): ?>
                                <br><span class="ms-4"><?php echo $location['phone_secondary']; ?></span>
                                <?php endif; ?>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-clock text-success me-2"></i>
                                <?php echo getLocalizedContent($location, 'opening_hours'); ?>
                            </p>
                            <?php if ($location['email']): ?>
                            <p class="mb-2">
                                <i class="fas fa-envelope text-success me-2"></i>
                                <?php echo $location['email']; ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($location['description_en']): ?>
                        <hr>
                        <p class="text-muted small mb-3">
                            <?php echo getLocalizedContent($location, 'description'); ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php if ($location['latitude'] && $location['longitude']): ?>
                        <a href="https://maps.google.com/?q=<?php echo $location['latitude']; ?>,<?php echo $location['longitude']; ?>" 
                           target="_blank" class="btn btn-outline-success btn-sm w-100">
                            <i class="fas fa-location-dot me-2"></i><?php echo __('Get Directions'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Branch Map Grid -->
<?php if (count($locations) > 1): ?>
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('All Locations Map'); ?></h2>
            <p class="lead text-muted"><?php echo __('Find the branch closest to you'); ?></p>
        </div>
        
        <div class="card border-0 shadow-lg" data-aos="zoom-in">
            <div class="ratio ratio-21x9">
                <?php
                // Generate map with multiple markers
                $map_center = $head_office ? $head_office['latitude'] . ',' . $head_office['longitude'] : '9.0300,38.7400';
                ?>
                <iframe 
                    width="100%" 
                    height="450" 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0" 
                    src="https://www.openstreetmap.org/export/embed.html?bbox=38.6,8.8,38.9,9.1&amp;layer=mapnik&amp;marker=9.03,38.74"
                    style="border:0">
                </iframe>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Service Area Info -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h3 class="fw-bold mb-4"><?php echo __('Delivery Service Area'); ?></h3>
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-truck fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5><?php echo __('Feed Delivery Available'); ?></h5>
                        <p class="text-muted"><?php echo __('We deliver to all areas within Addis Ababa and up to 50km radius.'); ?></p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-clock fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5><?php echo __('Delivery Hours'); ?></h5>
                        <p class="text-muted"><?php echo __('Monday - Saturday: 8:00 AM - 6:00 PM'); ?></p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-weight-scale fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5><?php echo __('Minimum Order'); ?></h5>
                        <p class="text-muted"><?php echo __('400 KG for delivery service'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="bg-success text-white p-5 rounded-4">
                    <h4 class="fw-bold mb-4"><?php echo __('Need Help Finding Us?'); ?></h4>
                    <p class="mb-4"><?php echo __('Contact our customer service for directions or assistance.'); ?></p>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-phone-alt fa-2x me-3"></i>
                        <div>
                            <span class="small"><?php echo __('Call us'); ?></span><br>
                            <strong>+251-11-123-4567</strong>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-envelope fa-2x me-3"></i>
                        <div>
                            <span class="small"><?php echo __('Email us'); ?></span><br>
                            <strong>info@lukufarm.com</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>