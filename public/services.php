<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/language.php';

$page_title = __('Our Services');
$db = Database::getInstance();

// Get all services
$services = $db->fetchAll(
    "SELECT * FROM services WHERE is_active = true ORDER BY display_order"
);

// Get delivery settings
$delivery_settings = getDeliverySettings();

include '../includes/header.php';
?>

<!-- Page Header -->
<section class="bg-success text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center" data-aos="fade-up">
                <h1 class="display-4 fw-bold"><?php echo __('our_services'); ?></h1>
                <p class="lead"><?php echo __('Comprehensive poultry solutions for every need'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $service['display_order'] * 50; ?>">
                <div class="card h-100 border-0 shadow-sm service-card hover-scale">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto mb-4">
                            <i class="fas fa-<?php echo $service['icon'] ?? 'cog'; ?> fa-3x"></i>
                        </div>
                        <h4 class="card-title fw-bold mb-3">
                            <?php echo getLocalizedContent($service, 'title'); ?>
                        </h4>
                        <p class="card-text text-muted">
                            <?php echo getLocalizedContent($service, 'description'); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Feed Delivery Service - SPECIAL HIGHLIGHT -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="bg-white p-5 rounded-4 shadow-lg">
                    <div class="d-inline-block bg-success bg-opacity-10 p-3 rounded-circle mb-4">
                        <i class="fas fa-truck fa-3x text-success"></i>
                    </div>
                    <h2 class="display-5 fw-bold mb-3"><?php echo __('feed_delivery'); ?></h2>
                    <p class="lead text-success"><?php echo __('Convenient, reliable, and affordable'); ?></p>
                    <p class="mb-4"><?php echo __('We deliver high-quality feeds directly to your farm. Our fleet of modern trucks ensures timely delivery throughout Addis Ababa and surrounding areas.'); ?></p>
                    
                    <div class="bg-light p-4 rounded-3 mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-success"></i><?php echo __('delivery_conditions'); ?></h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong><?php echo __('min_order_weight'); ?>:</strong> 
                                <?php echo $delivery_settings['min_weight_kg'] ?? 400; ?> KG
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong><?php echo __('delivery_fee'); ?>:</strong> 
                                <?php echo formatCurrency($delivery_settings['delivery_fee'] ?? 1500); ?> 
                                (<?php echo __('base fee'); ?>)
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong><?php echo __('Free delivery'); ?>:</strong> 
                                <?php echo __('Orders above'); ?> <?php echo $delivery_settings['free_delivery_above'] ?? 2000; ?> KG
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong><?php echo __('Delivery area'); ?>:</strong> 
                                <?php echo __('Addis Ababa and 50km radius'); ?>
                            </li>
                        </ul>
                    </div>
                    
                    <a href="<?php echo url('/public/contact.php'); ?>" class="btn btn-success btn-lg">
                        <i class="fas fa-phone-alt me-2"></i><?php echo __('Request Delivery'); ?>
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-weight-hanging fa-3x text-success mb-3"></i>
                                <h5><?php echo __('Bulk Orders'); ?></h5>
                                <p class="small"><?php echo __('Best prices for bulk purchases'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-clock fa-3x text-success mb-3"></i>
                                <h5><?php echo __('Fast Delivery'); ?></h5>
                                <p class="small"><?php echo __('Within 24-48 hours'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                                <h5><?php echo __('Quality Guarantee'); ?></h5>
                                <p class="small"><?php echo __('Fresh, properly stored feeds'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="fas fa-credit-card fa-3x text-success mb-3"></i>
                                <h5><?php echo __('Flexible Payment'); ?></h5>
                                <p class="small"><?php echo __('Cash, bank transfer, mobile money'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <img src="<?php echo url('/public/assets/images/Truck.png'); ?>" class="img-fluid rounded-3 shadow" alt="Delivery Service">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feeds Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('our_feeds'); ?></h2>
            <p class="lead text-muted"><?php echo __('Premium nutrition for optimal poultry health'); ?></p>
        </div>
        
        <?php
        $feeds = $db->fetchAll(
            "SELECT * FROM feeds WHERE is_active = true ORDER BY 
                CASE 
                    WHEN type = 'Starter' THEN 1
                    WHEN type = 'Grower' THEN 2
                    WHEN type = 'Layer' THEN 3
                    WHEN type = 'Broiler' THEN 4
                    ELSE 5
                END,
                display_order"
        );
        ?>
        
        <div class="row g-4">
            <?php foreach ($feeds as $feed): ?>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        <?php if ($feed['is_medicated']): ?>
                        <span class="position-absolute top-0 end-0 badge bg-warning text-dark m-2">
                            <?php echo __('Medicated'); ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($feed['image_url']): ?>
                        <img src="<?php echo $feed['image_url']; ?>" class="card-img-top" alt="<?php echo getLocalizedContent($feed, 'name'); ?>">
                        <?php else: ?>
                        <div class="bg-success bg-gradient d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-seedling fa-5x text-white"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold"><?php echo getLocalizedContent($feed, 'name'); ?></h5>
                        <p class="text-muted small mb-2"><?php echo $feed['stage']; ?> | <?php echo $feed['form']; ?></p>
                        <p class="card-text small"><?php echo getLocalizedContent($feed, 'description'); ?></p>
                        <div class="mt-3">
                            <span class="h5 text-success fw-bold"><?php echo formatCurrency($feed['price'] ?? 0); ?></span>
                            <span class="text-muted">/<?php echo $feed['unit'] ?? '50kg'; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Vaccines Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('our_vaccines'); ?></h2>
            <p class="lead text-muted"><?php echo __('Protect your flock with quality vaccines'); ?></p>
        </div>
        
        <?php
        $vaccines = $db->fetchAll(
            "SELECT * FROM vaccines WHERE is_active = true ORDER BY display_order"
        );
        ?>
        
        <div class="row g-4">
            <?php foreach ($vaccines as $vaccine): ?>
            <div class="col-lg-3 col-md-6" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="fas fa-syringe fa-2x text-success"></i>
                            </div>
                            <h6 class="fw-bold mb-0"><?php echo getLocalizedContent($vaccine, 'name'); ?></h6>
                        </div>
                        <p class="small text-muted mb-2"><?php echo getLocalizedContent($vaccine, 'description'); ?></p>
                        <div class="border-top pt-2 mt-2">
                            <small class="d-block"><strong><?php echo __('Application'); ?>:</strong> <?php echo $vaccine['application_method']; ?></small>
                            <small class="d-block"><strong><?php echo __('Age'); ?>:</strong> <?php echo $vaccine['age_recommendation']; ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Medicines Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('our_medicines'); ?></h2>
            <p class="lead text-muted"><?php echo __('Complete veterinary care for your flock'); ?></p>
        </div>
        
        <?php
        $medicines = $db->fetchAll(
            "SELECT * FROM medicines WHERE is_active = true ORDER BY 
                CASE 
                    WHEN category = 'Antibiotic' THEN 1
                    WHEN category = 'Antiparasitic' THEN 2
                    WHEN category = 'Support' THEN 3
                    ELSE 4
                END,
                display_order"
        );
        
        // Group by category
        $meds_by_category = [];
        foreach ($medicines as $medicine) {
            $meds_by_category[$medicine['category']][] = $medicine;
        }
        ?>
        
        <div class="row">
            <?php foreach ($meds_by_category as $category => $category_meds): ?>
            <div class="col-12 mb-5">
                <h4 class="fw-bold mb-4">
                    <span class="border-bottom border-success border-3 pb-2">
                        <?php 
                        if ($category == 'Antibiotic') echo __('Antibiotics');
                        elseif ($category == 'Antiparasitic') echo __('Antiparasitic');
                        elseif ($category == 'Support') echo __('Support & Supplements');
                        else echo $category;
                        ?>
                    </span>
                </h4>
                <div class="row g-3">
                    <?php foreach ($category_meds as $medicine): ?>
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-pills text-success me-2"></i>
                                    <h6 class="fw-bold mb-0 small"><?php echo getLocalizedContent($medicine, 'name'); ?></h6>
                                </div>
                                <p class="small text-muted mb-1"><?php echo getLocalizedContent($medicine, 'description'); ?></p>
                                <div class="border-top pt-1 mt-1">
                                    <small class="d-block"><strong><?php echo __('Dosage'); ?>:</strong> <?php echo $medicine['dosage']; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Consultation CTA -->
<section class="py-5 bg-success text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4" data-aos="fade-up"><?php echo __('Need Expert Consultation?'); ?></h2>
        <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100"><?php echo __('Our veterinarians and poultry experts are ready to help you succeed.'); ?></p>
        <div data-aos="fade-up" data-aos-delay="200">
            <a href="<?php echo url('/public/contact.php'); ?>" class="btn btn-light btn-lg me-3">
                <i class="fas fa-calendar-alt me-2"></i><?php echo __('Book Consultation'); ?>
            </a>
            <a href="<?php echo url('/public/locations.php'); ?>" class="btn btn-outline-light btn-lg">
                <i class="fas fa-map-marker-alt me-2"></i><?php echo __('Visit Our Branches'); ?>
            </a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>