    </main>
    
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-uppercase">LUKU Farm</h5>
                    <p class="text-muted"><?php echo __('Expertise begets quality'); ?></p>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Addis Ababa, Ethiopia</p>
                    <p><i class="fas fa-phone me-2"></i> +251-11-123-4567</p>
                    <p><i class="fas fa-envelope me-2"></i> info@lukufarm.com</p>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-uppercase"><?php echo __('Quick Links'); ?></h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="<?php echo url('/public/'); ?>" class="text-white-50 text-decoration-none"><?php echo __('nav_home'); ?></a></li>
                        <li class="mb-2"><a href="<?php echo url('/public/about.php'); ?>" class="text-white-50 text-decoration-none"><?php echo __('nav_about'); ?></a></li>
                        <li class="mb-2"><a href="<?php echo url('/public/services.php'); ?>" class="text-white-50 text-decoration-none"><?php echo __('nav_services'); ?></a></li>
                        <li class="mb-2"><a href="<?php echo url('/public/locations.php'); ?>" class="text-white-50 text-decoration-none"><?php echo __('nav_locations'); ?></a></li>
                        <li class="mb-2"><a href="<?php echo url('/public/contact.php'); ?>" class="text-white-50 text-decoration-none"><?php echo __('nav_contact'); ?></a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-uppercase"><?php echo __('follow_us'); ?></h5>
                    <div class="d-flex">
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-telegram fa-2x"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-youtube fa-2x"></i></a>
                    </div>
                    
                    <hr class="bg-light my-4">
                    
                    <h6><?php echo __('Subscribe to Newsletter'); ?></h6>
                    <form class="mt-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your email">
                            <button class="btn btn-success" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="text-muted mb-0">
                        &copy; <?php echo date('Y'); ?> LUKU Farm. <?php echo __('rights_reserved'); ?>.
                        <?php echo __('developed_by'); ?> LUKU Technologies
                    </p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo url('/public/assets/js/main.js'); ?>"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>