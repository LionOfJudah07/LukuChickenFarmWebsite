<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/language.php';

$page_title = __('About Us');
$db = Database::getInstance();

// Get page content
$page_content = getPageContent('about');

include '../includes/header.php';
?>

<!-- Page Header - UPDATED -->
<section class="hero-section" style="padding: 80px 0;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center" data-aos="fade-up">
                <h1 class="display-4 fw-bold text-white"><?php echo __('About LUKU Farm'); ?></h1>
                <p class="lead text-white"><?php echo __("Ethiopia's Premier Poultry Solutions Provider"); ?></p>
            </div>
        </div>
    </div>
</section>
<!-- Our Story -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <h2 class="display-5 fw-bold mb-4"><?php echo __('Our Story'); ?></h2>
                <p class="lead text-success"><?php echo __('Expertise begets quality'); ?></p>
                <p><?php echo __("Founded in 2010, LUKU Farm began with a vision to transform poultry farming in Ethiopia. What started as a small family farm has grown into one of Addis Ababa's most trusted poultry solutions providers."); ?></p>
                <p><?php echo __('Today, we operate multiple branches across Addis Ababa, serving thousands of satisfied customers. Our commitment to quality, innovation, and customer service has made us the preferred choice for poultry farmers of all sizes.'); ?></p>
                
                <div class="row mt-5">
                    <div class="col-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="border-start border-success border-4 ps-3">
                            <h3 class="display-4 fw-bold text-success">2010</h3>
                            <p class="text-muted"><?php echo __('Year Established'); ?></p>
                        </div>
                    </div>
                    <div class="col-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="border-start border-success border-4 ps-3">
                            <h3 class="display-4 fw-bold text-success">5+</h3>
                            <p class="text-muted"><?php echo __('Branches'); ?></p>
                        </div>
                    </div>
                    <div class="col-6 mt-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="border-start border-success border-4 ps-3">
                            <h3 class="display-4 fw-bold text-success">10k+</h3>
                            <p class="text-muted"><?php echo __('Happy Customers'); ?></p>
                        </div>
                    </div>
                    <div class="col-6 mt-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="border-start border-success border-4 ps-3">
                            <h3 class="display-4 fw-bold text-success">50+</h3>
                            <p class="text-muted"><?php echo __('Team Members'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="position-relative">
                    <img src="https://placehold.co/600x500/28a745/white?text=LUKU+Farm+History" alt="LUKU Farm History" class="img-fluid rounded-3 shadow-lg">
                    <div class="position-absolute bottom-0 end-0 bg-white p-4 rounded-3 shadow" style="transform: translate(20px, -20px);">
                        <p class="mb-0 fw-bold text-success"><i class="fas fa-quote-left me-2"></i><?php echo __('Quality is not an act, it is a habit'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6" data-aos="zoom-in">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <div class="service-icon mx-auto bg-primary bg-gradient mb-4">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="fw-bold mb-4"><?php echo __('Our Mission'); ?></h3>
                        <p class="mb-0"><?php echo __('To provide high-quality poultry products and services that meet international standards while supporting local farmers with expert knowledge and sustainable solutions.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <div class="service-icon mx-auto bg-warning bg-gradient mb-4">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="fw-bold mb-4"><?php echo __('Our Vision'); ?></h3>
                        <p class="mb-0"><?php echo __("To be Ethiopia's leading poultry solutions provider, recognized for excellence in quality, innovation, and customer service, while contributing to food security and economic growth."); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('Our Core Values'); ?></h2>
            <p class="lead text-muted"><?php echo __('The principles that guide everything we do'); ?></p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="card h-100 border-0 shadow-sm hover-scale">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-medal fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?php echo __('Quality'); ?></h4>
                        <p class="text-muted mb-0"><?php echo __('We never compromise on quality, from our feeds to our services.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm hover-scale">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-handshake fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?php echo __('Integrity'); ?></h4>
                        <p class="text-muted mb-0"><?php echo __('We operate with honesty, transparency, and ethical practices.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm hover-scale">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-users fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?php echo __('Customer Focus'); ?></h4>
                        <p class="text-muted mb-0"><?php echo __("Our customers' success is our success."); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 border-0 shadow-sm hover-scale">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-flask fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?php echo __('Innovation'); ?></h4>
                        <p class="text-muted mb-0"><?php echo __('We continuously improve and adopt modern farming techniques.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="card h-100 border-0 shadow-sm hover-scale">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-leaf fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?php echo __('Sustainability'); ?></h4>
                        <p class="text-muted mb-0"><?php echo __('We care for the environment and promote sustainable farming.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="card h-100 border-0 shadow-sm hover-scale">
                    <div class="card-body text-center p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-heart fa-3x text-success"></i>
                        </div>
                        <h4 class="fw-bold mb-3"><?php echo __('Community'); ?></h4>
                        <p class="text-muted mb-0"><?php echo __('We support local farmers and contribute to community development.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('Our Leadership Team'); ?></h2>
            <p class="lead text-muted"><?php echo __('Experienced professionals dedicated to your success'); ?></p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="flip-left">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://placehold.co/400x300/28a745/white?text=CEO" class="card-img-top" alt="CEO">
                    <div class="card-body text-center">
                        <h4 class="fw-bold mb-1">Tadiyos Assfaw</h4>
                        <p class="text-success mb-2"><?php echo __('Founder & CEO'); ?></p>
                        <p class="small text-muted"><?php echo __('30+ years experience in poultry farming'); ?></p>
                        <div class="social-links">
                            <a href="#" class="text-success me-2"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-success"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="flip-left" data-aos-delay="100">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://placehold.co/400x300/218838/white?text=Operations" class="card-img-top" alt="Operations Director">
                    <div class="card-body text-center">
                        <h4 class="fw-bold mb-1">Tigist Haile</h4>
                        <p class="text-success mb-2"><?php echo __('Director of Operations'); ?></p>
                        <p class="small text-muted"><?php echo __('15+ years in agricultural management'); ?></p>
                        <div class="social-links">
                            <a href="#" class="text-success me-2"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-success"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="flip-left" data-aos-delay="200">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://placehold.co/400x300/34ce57/white?text=Vet" class="card-img-top" alt="Chief Veterinarian">
                    <div class="card-body text-center">
                        <h4 class="fw-bold mb-1">Dr. Bekele Desta</h4>
                        <p class="text-success mb-2"><?php echo __('Chief Veterinarian'); ?></p>
                        <p class="small text-muted"><?php echo __('DVM, MSc in Poultry Medicine'); ?></p>
                        <div class="social-links">
                            <a href="#" class="text-success me-2"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="text-success"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>