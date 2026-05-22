<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/language.php';

$page_title = __('Our Partner - Jagdish Agro Industry');
$db = Database::getInstance();

// Get page content
$page_content = getPageContent('partner');

include '../includes/header.php';
?>

<!-- Page Header -->
<section class="bg-success text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center" data-aos="fade-up">
                <h1 class="display-4 fw-bold"><?php echo __('Jagdish Agro Industry'); ?></h1>
                <p class="lead"><?php echo __('Official Licensed Distributor for Ethiopia'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Partnership Intro -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="bg-light p-5 rounded-4 shadow-sm">
                    <img src="https://placehold.co/400x100/28a745/white?text=Jagdish+Agro+Industry" alt="Jagdish Agro Logo" class="img-fluid mb-4">
                    <h2 class="fw-bold mb-4"><?php echo __('A Partnership Built on Quality'); ?></h2>
                    <p class="lead text-success"><?php echo __('LUKU Farm is proud to be the official licensed distributor of Jagdish Agro Industry feeds in Addis Ababa.'); ?></p>
                    <p class="mb-4"><?php echo __('Since 2015, we have partnered with Jagdish Agro Industry to bring world-class poultry nutrition to Ethiopian farmers. Their commitment to scientific research and quality manufacturing aligns perfectly with our mission of providing excellence to our customers.'); ?></p>
                    
                    <div class="d-flex align-items-center mt-4">
                        <div class="bg-white p-3 rounded-circle shadow-sm me-3">
                            <i class="fas fa-certificate fa-3x text-success"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1"><?php echo __('Official License No.'); ?></h6>
                            <p class="text-muted mb-0">JAI-ETH-2024-001</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="fas fa-flask fa-3x text-success"></i>
                                </div>
                                <h5><?php echo __('Research Backed'); ?></h5>
                                <p class="small text-muted"><?php echo __('Formulated by poultry nutritionists'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="fas fa-globe-asia fa-3x text-success"></i>
                                </div>
                                <h5><?php echo __('Global Standards'); ?></h5>
                                <p class="small text-muted"><?php echo __('ISO 9001:2015 certified'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="fas fa-leaf fa-3x text-success"></i>
                                </div>
                                <h5><?php echo __('Natural Ingredients'); ?></h5>
                                <p class="small text-muted"><?php echo __('No harmful additives'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                                    <i class="fas fa-chart-line fa-3x text-success"></i>
                                </div>
                                <h5><?php echo __('Proven Results'); ?></h5>
                                <p class="small text-muted"><?php echo __('20% better feed conversion'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jagdish Products -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('Jagdish Agro Feed Range'); ?></h2>
            <p class="lead text-muted"><?php echo __('Premium quality feeds for every stage of poultry development'); ?></p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-egg fa-3x text-warning"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?php echo __('Jagdish Layer Excel'); ?></h4>
                        <p class="text-muted"><?php echo __('High calcium formula for maximum egg production, strong shells, and optimal hen health.'); ?></p>
                        <ul class="list-unstyled text-start mt-3">
                            <li><i class="fas fa-check-circle text-success me-2"></i> 18% Protein</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> 4% Calcium</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Added Probiotics</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-drumstick-bite fa-3x text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?php echo __('Jagdish Broiler Pro'); ?></h4>
                        <p class="text-muted"><?php echo __('High energy formula for rapid weight gain and excellent meat quality in broilers.'); ?></p>
                        <ul class="list-unstyled text-start mt-3">
                            <li><i class="fas fa-check-circle text-success me-2"></i> 22% Protein</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> 3,200 Kcal/kg</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Growth Promoters</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                            <i class="fas fa-seedling fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?php echo __('Jagdish Starter Gold'); ?></h4>
                        <p class="text-muted"><?php echo __('Complete nutrition for day-old chicks, ensuring strong immune system and healthy development.'); ?></p>
                        <ul class="list-unstyled text-start mt-3">
                            <li><i class="fas fa-check-circle text-success me-2"></i> 20% Protein</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Coccidiostat</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Easy crumble form</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6" data-aos="fade-right">
                <h3 class="fw-bold mb-4"><?php echo __('Why Choose Jagdish Agro?'); ?></h3>
                <div class="accordion" id="benefitsAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-success text-white" type="button" data-bs-toggle="collapse" data-bs-target="#benefit1">
                                <i class="fas fa-microscope me-2"></i>
                                <?php echo __('Scientifically Formulated'); ?>
                            </button>
                        </h2>
                        <div id="benefit1" class="accordion-collapse collapse show" data-bs-parent="#benefitsAccordion">
                            <div class="accordion-body">
                                <?php echo __('Each feed formula is developed by PhD nutritionists based on the latest poultry science research, ensuring optimal nutrient absorption and bird health.'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-success text-white" type="button" data-bs-toggle="collapse" data-bs-target="#benefit2">
                                <i class="fas fa-medal me-2"></i>
                                <?php echo __('Quality Assurance'); ?>
                            </button>
                        </h2>
                        <div id="benefit2" class="accordion-collapse collapse" data-bs-parent="#benefitsAccordion">
                            <div class="accordion-body">
                                <?php echo __('Every batch is tested for quality, safety, and nutritional consistency. HACCP certified manufacturing process.'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-success text-white" type="button" data-bs-toggle="collapse" data-bs-target="#benefit3">
                                <i class="fas fa-truck-fast me-2"></i>
                                <?php echo __('Consistent Supply'); ?>
                            </button>
                        </h2>
                        <div id="benefit3" class="accordion-collapse collapse" data-bs-parent="#benefitsAccordion">
                            <div class="accordion-body">
                                <?php echo __('As the official distributor, we maintain optimal inventory levels to ensure you never run out of feed.'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="bg-success text-white p-5 rounded-4">
                    <h4 class="fw-bold mb-4"><?php echo __('Become a Jagdish Feeds Customer'); ?></h4>
                    <p class="mb-4"><?php echo __('Join hundreds of successful farmers who trust Jagdish Agro Industry for their poultry nutrition needs.'); ?></p>
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fas fa-check-circle me-2"></i> <?php echo __('Volume discounts available'); ?></li>
                        <li class="mb-3"><i class="fas fa-check-circle me-2"></i> <?php echo __('Free technical support'); ?></li>
                        <li class="mb-3"><i class="fas fa-check-circle me-2"></i> <?php echo __('Regular farm visits'); ?></li>
                        <li class="mb-3"><i class="fas fa-check-circle me-2"></i> <?php echo __('Nutritional consulting'); ?></li>
                    </ul>
                    <a href="<?php echo url('/public/contact.php'); ?>" class="btn btn-light btn-lg mt-3 w-100">
                        <i class="fas fa-file-signature me-2"></i><?php echo __('Inquire About Jagdish Feeds'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonial -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center" data-aos="zoom-in">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <i class="fas fa-quote-right fa-4x text-success opacity-25 mb-3"></i>
                        <h4 class="fw-normal mb-4">
                            "<?php echo __('Our partnership with Jagdish Agro Industry has allowed us to offer Ethiopian farmers the same quality feeds used by top poultry producers in India and Europe. The results speak for themselves - better feed conversion, healthier birds, and higher profits for our customers.'); ?>"
                        </h4>
                        <div class="d-flex justify-content-center align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">Tadiyos Assfaw</h6>
                                <p class="text-muted small"><?php echo __('Founder & CEO, LUKU Farm'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 bg-success text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4" data-aos="fade-up"><?php echo __('Ready to Switch to Jagdish Feeds?'); ?></h2>
        <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100"><?php echo __('Contact us today for pricing and availability.'); ?></p>
        <div data-aos="fade-up" data-aos-delay="200">
            <a href="<?php echo url('/public/contact.php'); ?>" class="btn btn-light btn-lg me-3">
                <i class="fas fa-envelope me-2"></i><?php echo __('Contact Us'); ?>
            </a>
            <a href="<?php echo url('/public/locations.php'); ?>" class="btn btn-outline-light btn-lg">
                <i class="fas fa-store me-2"></i><?php echo __('Visit Our Branches'); ?>
            </a>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>