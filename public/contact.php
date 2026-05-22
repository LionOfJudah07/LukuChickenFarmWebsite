<?php
require_once '../includes/db.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/language.php';

$page_title = __('Contact Us');
$db = Database::getInstance();
$success = '';
$error = '';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrf($csrf_token)) {
        $error = 'Invalid CSRF token';
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $subject = sanitize($_POST['subject'] ?? '');
        $message = sanitize($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($message)) {
            $error = __('Please fill in all required fields');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = __('Please enter a valid email address');
        } else {
            try {
                $db->insert('messages', [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'subject' => $subject,
                    'message' => $message,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                // Send email notification (if configured)
                // mail($to, $subject, $message);
                
                $success = __('Thank you for your message. We will contact you soon!');
                
                // Clear form
                $_POST = [];
            } catch (Exception $e) {
                $error = __('Failed to send message. Please try again.');
                error_log("Contact form error: " . $e->getMessage());
            }
        }
    }
}

// Get head office for contact info
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
                <h1 class="display-4 fw-bold"><?php echo __('contact_us'); ?></h1>
                <p class="lead"><?php echo __('Get in touch with our team'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-map-marker-alt fa-3x text-success mt-2"></i>
                    </div>
                    <h5 class="fw-bold"><?php echo __('Visit Us'); ?></h5>
                    <p class="text-muted mb-0">
                        <?php echo $head_office ? getLocalizedContent($head_office, 'address') : 'Addis Ababa, Ethiopia'; ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-phone-alt fa-3x text-success mt-2"></i>
                    </div>
                    <h5 class="fw-bold"><?php echo __('call_us'); ?></h5>
                    <p class="text-muted mb-0">
                        <?php echo $head_office ? $head_office['phone'] : '+251-11-123-4567'; ?><br>
                        <?php echo $head_office['phone_secondary'] ?? ''; ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-envelope fa-3x text-success mt-2"></i>
                    </div>
                    <h5 class="fw-bold"><?php echo __('email_us'); ?></h5>
                    <p class="text-muted mb-0">
                        info@lukufarm.com<br>
                        support@lukufarm.com
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mx-auto mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-clock fa-3x text-success mt-2"></i>
                    </div>
                    <h5 class="fw-bold"><?php echo __('Working Hours'); ?></h5>
                    <p class="text-muted mb-0">
                        <?php echo $head_office ? getLocalizedContent($head_office, 'opening_hours') : 'Mon-Fri: 8AM-6PM'; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Map -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <h3 class="fw-bold mb-4"><?php echo __('Send Us a Message'); ?></h3>
                        
                        <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" class="contact-form">
                            <?php echo csrfField(); ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?php echo __('your_name'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg" 
                                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?php echo __('your_email'); ?> <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg" 
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?php echo __('your_phone'); ?></label>
                                    <input type="tel" name="phone" class="form-control form-control-lg" 
                                           value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><?php echo __('subject'); ?></label>
                                    <input type="text" name="subject" class="form-control form-control-lg" 
                                           value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                                </div>
                                <div class="col-12 mb-4">
                                    <label class="form-label"><?php echo __('your_message'); ?> <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control form-control-lg" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-paper-plane me-2"></i><?php echo __('send_message'); ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body p-0">
                        <div class="ratio ratio-1x1">
                            <?php if ($head_office): ?>
                            <iframe 
                                src="https://maps.google.com/maps?q=<?php echo $head_office['latitude']; ?>,<?php echo $head_office['longitude']; ?>&hl=en&z=15&output=embed"
                                allowfullscreen
                                loading="lazy"
                                style="border:0;">
                            </iframe>
                            <?php else: ?>
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126115.1243134439!2d38.705154!3d9.024825!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b85cef5ab402d%3A0x8467b6b37a5d9c0a!2sAddis%20Ababa!5e0!3m2!1sen!2set!4v1700000000000!5m2!1sen!2set"
                                allowfullscreen
                                loading="lazy"
                                style="border:0;">
                            </iframe>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold"><?php echo __('Frequently Asked Questions'); ?></h2>
            <p class="lead text-muted"><?php echo __('Find quick answers to common questions'); ?></p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm" data-aos="fade-up">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-success text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <?php echo __('What are your delivery hours?'); ?>
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo __('We deliver Monday through Saturday from 8:00 AM to 6:00 PM. Sunday deliveries are available for emergency orders with prior arrangement.'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-success text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <?php echo __('Do you offer credit facilities?'); ?>
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo __('Yes, we offer credit facilities for registered customers with established payment history. Please contact our branch managers for more information.'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-success text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <?php echo __('What payment methods do you accept?'); ?>
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo __('We accept cash, bank transfer, mobile money (TeleBirr, Amole), and credit/debit cards at our branches.'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm" data-aos="fade-up" data-aos-delay="300">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-success text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                <?php echo __('Do you provide vaccination services on farm?'); ?>
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo __('Yes, our veterinary team provides on-farm vaccination services. Please book at least 3 days in advance.'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm" data-aos="fade-up" data-aos-delay="400">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-success text-white" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                <?php echo __('How can I become a distributor?'); ?>
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?php echo __('We are always looking for reliable distributors. Please contact our head office with your business proposal.'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Social Media Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <h3 class="fw-bold mb-2"><?php echo __('Connect With Us'); ?></h3>
                <p class="lead text-muted"><?php echo __('Follow us on social media for updates and farming tips'); ?></p>
            </div>
            <div class="col-lg-6">
                <div class="d-flex justify-content-center justify-content-lg-end gap-4">
                    <a href="#" class="btn btn-outline-success btn-lg rounded-circle p-3" style="width: 70px; height: 70px;">
                        <i class="fab fa-facebook-f fa-2x"></i>
                    </a>
                    <a href="#" class="btn btn-outline-success btn-lg rounded-circle p-3" style="width: 70px; height: 70px;">
                        <i class="fab fa-twitter fa-2x"></i>
                    </a>
                    <a href="#" class="btn btn-outline-success btn-lg rounded-circle p-3" style="width: 70px; height: 70px;">
                        <i class="fab fa-instagram fa-2x"></i>
                    </a>
                    <a href="#" class="btn btn-outline-success btn-lg rounded-circle p-3" style="width: 70px; height: 70px;">
                        <i class="fab fa-telegram-plane fa-2x"></i>
                    </a>
                    <a href="#" class="btn btn-outline-success btn-lg rounded-circle p-3" style="width: 70px; height: 70px;">
                        <i class="fab fa-youtube fa-2x"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>