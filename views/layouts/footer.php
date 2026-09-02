<?php
$companyName = htmlspecialchars($globalSettings['company_name'] ?? 'Vigtez Reality Estates');
$companyPhone = htmlspecialchars($globalSettings['company_phone'] ?? '');
$companyEmail = htmlspecialchars($globalSettings['company_email'] ?? '');
$officeAddress = htmlspecialchars($globalSettings['office_address'] ?? '');
$whatsappNumber = htmlspecialchars($globalSettings['whatsapp_number'] ?? '');
$footerContent = htmlspecialchars($globalSettings['footer_content'] ?? '');

$facebook = htmlspecialchars($globalSettings['social_facebook'] ?? '');
$instagram = htmlspecialchars($globalSettings['social_instagram'] ?? '');
$twitter = htmlspecialchars($globalSettings['social_twitter'] ?? '');
?>
    
    <!-- Footer Section -->
    <footer class="luxury-footer pt-5 pb-4 mt-5 border-top border-secondary border-opacity-10">
        <div class="container text-md-left">
            <div class="row g-4">
                <!-- Company Details Column -->
                <div class="col-lg-5 col-md-6 footer-brand-column">
                    <h4 class="text-uppercase mb-4 font-weight-bold text-gradient-light brand-logo-footer">
                        <i class="fa-solid fa-hotel me-2"></i><?php echo $companyName; ?>
                    </h4>
                    <p class="text-secondary small mb-4 lh-lg">
                        <?php echo htmlspecialchars($globalSettings['company_description'] ?? 'Crafting environments of absolute trust, bespoke services, and unmatched luxury real estate. Experience architectural masterpieces with Vigtez Reality.'); ?>
                    </p>
                    <div class="footer-social-icons d-flex">
                        <?php if ($facebook): ?>
                            <a href="<?php echo $facebook; ?>" target="_blank" class="social-circle-link me-2" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if ($instagram): ?>
                            <a href="<?php echo $instagram; ?>" target="_blank" class="social-circle-link me-2" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($twitter): ?>
                            <a href="<?php echo $twitter; ?>" target="_blank" class="social-circle-link" aria-label="Twitter / X"><i class="fa-brands fa-x-twitter"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Fast Links Column -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase mb-4 font-weight-bold text-white small tracking-widest text-gold-accent">Quick Navigation</h6>
                    <ul class="list-unstyled footer-link-list">
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>" class="footer-link">Home Portal</a></li>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>about" class="footer-link">About Luxe</a></li>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>properties" class="footer-link">Exclusive Collection</a></li>
                        <li class="mb-2"><a href="<?php echo BASE_URL; ?>contact" class="footer-link">Connect with Us</a></li>
                    </ul>
                </div>

                <!-- Contact details Column -->
                <div class="col-lg-4 col-md-12">
                    <h6 class="text-uppercase mb-4 font-weight-bold text-white small tracking-widest text-gold-accent">Contact Details</h6>
                    <div class="text-secondary small footer-contact-details">
                        <?php if ($officeAddress): ?>
                            <p class="d-flex align-items-start mb-3"><i class="fa-solid fa-location-dot me-3 text-warning mt-1"></i><span><?php echo $officeAddress; ?></span></p>
                        <?php endif; ?>
                        <?php if ($companyEmail): ?>
                            <p class="d-flex align-items-center mb-3"><i class="fa-solid fa-envelope-open me-3 text-warning"></i><span><?php echo $companyEmail; ?></span></p>
                        <?php endif; ?>
                        <?php if ($companyPhone): ?>
                            <p class="d-flex align-items-center"><i class="fa-solid fa-phone me-3 text-warning"></i><span><?php echo $companyPhone; ?></span></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <hr class="mb-4 mt-5 border-secondary border-opacity-10">

            <div class="row align-items-center footer-bottom-row">
                <div class="col-md-7 col-lg-8">
                    <p class="text-secondary small mb-0"><?php echo $footerContent ?: "© " . date('Y') . " {$companyName}. All rights reserved."; ?></p>
                </div>
                <div class="col-md-5 col-lg-4 text-md-end">
                    <span class="text-secondary small">Designed by <a href="#" class="text-decoration-none text-gold-accent fw-bold"><i class="fa-solid fa-award me-1"></i>Digital Logic India</a></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <?php if ($whatsappNumber): ?>
        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $whatsappNumber); ?>" class="whatsapp-float-luxury" target="_blank" aria-label="Chat on WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    <?php endif; ?>

    <!-- JS Core scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Motion and slider libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    
    <script src="<?php echo BASE_URL; ?>assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
