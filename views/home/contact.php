<?php
use App\Helpers\CSRFHelper;
?>

<!-- Cinematic Header -->
<section class="luxury-inner-hero position-relative d-flex align-items-center overflow-hidden">
    <div class="inner-hero-bg" style="background-image: url('<?php echo !empty($globalSettings['contact_hero_image']) ? BASE_URL . $globalSettings['contact_hero_image'] : 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80'; ?>');"></div>
    <div class="video-overlay-tint"></div>
    
    <div class="container position-relative z-1 text-center py-5">
        <span class="badge hero-luxe-badge mb-3"><i class="fa-solid fa-gem text-warning me-2"></i><?php echo htmlspecialchars($globalSettings['contact_hero_badge'] ?? 'CONCIERGE DESK'); ?></span>
        <h1 class="display-3 font-cinzel text-white fw-bold"><?php echo htmlspecialchars($globalSettings['contact_hero_title'] ?? 'Connect with Vigtez Reality'); ?></h1>
        <p class="lead text-white mx-auto" style="max-width: 600px;"><?php echo htmlspecialchars($globalSettings['contact_hero_desc'] ?? 'Schedule private helicopter viewings, charter tours, or off-market portfolios.'); ?></p>
    </div>
</section>

<!-- Contact Layout -->
<section class="py-5 bg-black text-white">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Contact info -->
            <div class="col-lg-5 scroll-reveal-left">
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-hotel me-2"></i><?php echo htmlspecialchars($globalSettings['contact_channels_badge'] ?? 'OUR CHANNELS'); ?></span>
                <h2 class="display-6 font-cinzel text-white fw-bold mb-4"><?php echo htmlspecialchars($globalSettings['contact_channels_title'] ?? 'Acquisition Inquiries'); ?></h2>
                <p class="text-light-muted mb-5 lh-lg">
                    <?php echo nl2br(htmlspecialchars($globalSettings['contact_channels_desc'] ?? 'Connect with our luxury partners. For ultra-high-net-worth portfolio acquisitions or private valuations, please submit the form or contact our concierge line directly.')); ?>
                </p>

                <div class="contact-luxe-details">
                    <?php if (!empty($globalSettings['office_address'])): ?>
                        <div class="d-flex align-items-start mb-4">
                            <div class="contact-icon-circle me-3"><i class="fa-solid fa-location-dot text-warning"></i></div>
                            <div>
                                <h6 class="text-white font-cinzel mb-1 fw-bold">Principal Office</h6>
                                <p class="text-secondary small mb-0"><?php echo htmlspecialchars($globalSettings['office_address']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($globalSettings['company_email'])): ?>
                        <div class="d-flex align-items-start mb-4">
                            <div class="contact-icon-circle me-3"><i class="fa-solid fa-envelope-open text-warning"></i></div>
                            <div>
                                <h6 class="text-white font-cinzel mb-1 fw-bold">Email Communications</h6>
                                <p class="text-secondary small mb-0"><?php echo htmlspecialchars($globalSettings['company_email']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($globalSettings['company_phone'])): ?>
                        <div class="d-flex align-items-start mb-4">
                            <div class="contact-icon-circle me-3"><i class="fa-solid fa-phone text-warning"></i></div>
                            <div>
                                <h6 class="text-white font-cinzel mb-1 fw-bold">Concierge Hotline</h6>
                                <p class="text-secondary small mb-0"><?php echo htmlspecialchars($globalSettings['company_phone']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="contact-icon-circle me-3"><i class="fa-solid fa-clock text-warning"></i></div>
                        <div>
                            <h6 class="text-white font-cinzel mb-1 fw-bold">Business Hours</h6>
                            <p class="text-secondary small mb-0"><?php echo $globalSettings['contact_business_hours'] ?? 'Monday – Saturday: 9:00 AM – 6:00 PM PST<br>Private emergency hotline: 24/7 (Registered Clients)'; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="col-lg-7 scroll-reveal-right">
                <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 shadow-2xl">
                    <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><?php echo htmlspecialchars($globalSettings['contact_form_badge'] ?? 'SUBMIT ENQUIRY'); ?></span>
                    <h3 class="font-cinzel text-white fw-bold mb-4"><?php echo htmlspecialchars($globalSettings['contact_form_title'] ?? 'Schedule a Private Viewing'); ?></h3>
                    
                    <form action="<?php echo BASE_URL; ?>contact/submit" method="POST" class="ajax-enquiry-form mt-4">
                        <?php echo CSRFHelper::getTokenField(); ?>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Your Name *</label>
                                <input type="text" name="name" class="form-control luxury-input-text" required placeholder="e.g. Marcus Vance">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-bold">Email Address *</label>
                                <input type="email" name="email" class="form-control luxury-input-text" required placeholder="name@domain.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-secondary small fw-bold">Phone Number</label>
                                <input type="tel" name="phone" class="form-control luxury-input-text" placeholder="e.g. +1 (555) 000-0000">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-secondary small fw-bold">Bespoke Message *</label>
                                <textarea name="message" rows="5" class="form-control luxury-input-text" required placeholder="Describe the type of property, category, location, and parameters you are looking to acquire..."></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-response-message my-3" style="display: none;"></div>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-gold-solid px-5 py-3 w-100 font-cinzel uppercase tracking-widest fw-bold">
                                    Send Private Inquiry
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
