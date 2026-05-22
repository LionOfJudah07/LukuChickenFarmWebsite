<?php
// First load config
require_once '../includes/config.php';
// Then database
require_once '../includes/db.php';
// Then functions (which now doesn't depend on db being loaded first)
require_once '../includes/functions.php';
// Then language
require_once '../includes/language.php';

$page_title = __('Home');
$db = Database::getInstance();

// Get page content
$page_content = getPageContent('home');

// Get featured services
$services = $db->fetchAll(
    "SELECT * FROM services WHERE is_active = true ORDER BY display_order LIMIT 6"
);

// Get featured products
$feeds = $db->fetchAll(
    "SELECT * FROM feeds WHERE is_active = true ORDER BY display_order LIMIT 4"
);

include '../includes/header.php';
?>
<!-- Hero Section - UPDATED -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="hero-title"><?php echo __('Welcome to LUKU Farm'); ?></h1>
                <p class="hero-subtitle mb-4"><?php echo __('Expertise begets quality'); ?></p>
                <p class="lead mb-4 text-white"><?php echo __('Your trusted partner in poultry farming and feed supply since 2010.'); ?></p>
                <div class="d-flex gap-3">
                    <a href="<?php echo url('/public/services.php'); ?>" class="btn btn-light">
                        <i class="fas fa-cogs me-2"></i><?php echo __('Our Services'); ?>
                    </a>
                    <a href="<?php echo url('/public/contact.php'); ?>" class="btn btn-outline-light">
                        <i class="fas fa-envelope me-2"></i><?php echo __('Contact Us'); ?>
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="<?php echo url('/public/assets/images/Logo4.jpg'); ?>" 
                     alt="LUKU Farm" 
                     class="img-fluid rounded-3 shadow-lg"
                     onerror="this.src='https://placehold.co/600x400/28a745/white?text=LUKU+Farm'">
            </div>
        </div>
    </div>
</section>
<!-- About Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-4"><?php echo __('About LUKU Farm'); ?></h2>
                <p class="lead" style="color: var(--primary-color);"><?php echo __("Ethiopia's Premier Poultry Solutions Provider"); ?></p>
                <p><?php echo __('LUKU Farm is a professional poultry farm located in Addis Ababa with multiple branches across the city. We specialize in healthy vaccinated chickens, high-quality feeds, medicines, veterinary support, consultation, and farm setup services.'); ?></p>
                
                <div class="row mt-5">
                    <div class="col-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon me-3" style="width: 50px; height: 50px; background: var(--bg-hero);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?php echo __('Years of Experience'); ?></h6>
                                <small class="text-muted"><?php echo __('Since 2010'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon me-3" style="width: 50px; height: 50px; background: var(--bg-hero);">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?php echo __('Multiple Branches'); ?></h6>
                                <small class="text-muted"><?php echo __('Across Addis Ababa'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon me-3" style="width: 50px; height: 50px; background: var(--bg-hero);">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?php echo __('Loyal Customers'); ?></h6>
                                <small class="text-muted"><?php echo __('Nationwide'); ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="d-flex align-items-center mb-3">
                            <div class="service-icon me-3" style="width: 50px; height: 50px; background: var(--bg-hero);">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?php echo __('Feed Delivery'); ?></h6>
                                <small class="text-muted"><?php echo __('Orders > 400 KG'); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="row g-3">
                    <div class="col-6">
                        <img src="https://placehold.co/300x300/28a745/white?text=Quality+Feeds" class="img-fluid rounded-3 shadow" alt="Feeds">
                    </div>
                    <div class="col-6">
                        <img src="https://placehold.co/300x300/218838/white?text=Healthy+Chickens" class="img-fluid rounded-3 shadow" alt="Chickens">
                    </div>
                    <div class="col-6">
                        <img src="https://placehold.co/300x300/34ce57/white?text=Veterinary+Care" class="img-fluid rounded-3 shadow" alt="Veterinary">
                    </div>
                    <div class="col-6">
                        <img src="https://placehold.co/300x300/28a745/white?text=Farm+Setup" class="img-fluid rounded-3 shadow" alt="Farm Setup">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5 bg-secondary">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('our_services'); ?></h2>
            <p class="lead text-muted"><?php echo __('Comprehensive poultry solutions for every need'); ?></p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($services as $service): ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $service['display_order'] * 50; ?>">
                <div class="card h-100 service-card">
                    <div class="card-body text-center p-4">
                        <div class="service-icon mx-auto">
                            <i class="fas fa-<?php echo $service['icon'] ?? 'cog'; ?>"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">
                            <?php echo getLocalizedContent($service, 'title'); ?>
                        </h5>
                        <p class="card-text">
                            <?php echo substr(getLocalizedContent($service, 'description'), 0, 100) . '...'; ?>
                        </p>
                        <a href="<?php echo url('/public/services.php'); ?>" class="btn btn-outline-success mt-3">
                            <?php echo __('read_more'); ?> <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?php echo url('/public/services.php'); ?>" class="btn btn-success btn-lg">
                <?php echo __('View All Services'); ?> <i class="fas fa-arrow-right ms-2"></i>
            </a>
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
        
        <div class="row g-4">
            <?php foreach ($feeds as $feed): ?>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="<?php echo $feed['display_order'] * 50; ?>">
                <div class="card h-100 product-card">
                    <?php if ($feed['image_url']): ?>
                    <img src="<?php echo $feed['image_url']; ?>" class="card-img-top" alt="<?php echo getLocalizedContent($feed, 'name'); ?>">
                    <?php else: ?>
                    <div class="card-img-top bg-success d-flex align-items-center justify-content-center" style="height: 200px; background: var(--bg-hero) !important;">
                        <i class="fas fa-seedling fa-4x text-white"></i>
                    </div>
                    <?php endif; ?>
                    <div class="card-body text-center">
                        <?php if ($feed['is_medicated']): ?>
                        <span class="badge mb-2" style="background-color: var(--warning-color); color: var(--text-dark);"><?php echo __('Medicated'); ?></span>
                        <?php endif; ?>
                        <h5 class="card-title fw-bold">
                            <?php echo getLocalizedContent($feed, 'name'); ?>
                        </h5>
                        <p class="card-text small">
                            <?php echo $feed['stage']; ?> | <?php echo $feed['form']; ?>
                        </p>
                        <p class="fw-bold mb-0" style="color: var(--primary-color);">
                            <?php echo formatCurrency($feed['price'] ?? 0); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Partner Section -->
<section class="py-5 bg-secondary">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center" data-aos="fade-right">
                <img src="https://placehold.co/400x200/28a745/white?text=Jagdish+Agro" alt="Jagdish Agro Industry" class="img-fluid mb-4">
                <h3 class="fw-bold"><?php echo __('Official Distributor'); ?></h3>
                <h4 style="color: var(--primary-color);"><?php echo __('Jagdish Agro Industry'); ?></h4>
                <p><?php echo __('Licensed distributor of premium quality feeds'); ?></p>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="p-5 rounded-3 shadow" style="background-color: var(--bg-card);">
                    <h3 class="fw-bold mb-4"><?php echo __('Why Choose Jagdish Agro Feeds?'); ?></h3>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fas fa-check-circle me-2" style="color: var(--primary-color);"></i> <?php echo __('International quality standards'); ?></li>
                        <li class="mb-3"><i class="fas fa-check-circle me-2" style="color: var(--primary-color);"></i> <?php echo __('Scientifically formulated nutrition'); ?></li>
                        <li class="mb-3"><i class="fas fa-check-circle me-2" style="color: var(--primary-color);"></i> <?php echo __('Consistent quality batch after batch'); ?></li>
                        <li class="mb-3"><i class="fas fa-check-circle me-2" style="color: var(--primary-color);"></i> <?php echo __('Trusted by leading farmers'); ?></li>
                    </ul>
                    <a href="<?php echo url('/public/partner.php'); ?>" class="btn btn-success mt-3">
                        <?php echo __('Learn More About Our Partner'); ?> <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: var(--bg-hero);">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4 text-white" data-aos="fade-up"><?php echo __('Ready to Start Your Poultry Journey?'); ?></h2>
        <p class="lead mb-4 text-white" data-aos="fade-up" data-aos-delay="100"><?php echo __('Contact us today for expert consultation and quality products.'); ?></p>
        <div data-aos="fade-up" data-aos-delay="200">
            <a href="<?php echo url('/public/contact.php'); ?>" class="btn btn-light btn-lg me-3">
                <i class="fas fa-envelope me-2"></i><?php echo __('Contact Us'); ?>
            </a>
            <a href="<?php echo url('/public/locations.php'); ?>" class="btn btn-outline-light btn-lg">
                <i class="fas fa-map-marker-alt me-2"></i><?php echo __('Find a Branch'); ?>
            </a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>