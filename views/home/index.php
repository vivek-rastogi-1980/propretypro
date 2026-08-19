<!-- Full Screen Cinematic Hero -->
<section class="hero-section-cinematic position-relative d-flex align-items-center overflow-hidden">
    <!-- Video background overlay -->
    <div class="hero-video-bg">
        <div class="video-overlay-tint"></div>
        <video autoplay muted loop playsinline class="w-100 h-100 object-fit-cover" poster="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-modern-apartment-building-in-a-city-40718-large.mp4" type="video/mp4">
        </video>
    </div>
    
    <!-- Three.js Canvas Placeholder for floating 3D building particles -->
    <canvas id="hero-three-canvas" class="position-absolute w-100 h-100 top-0 start-0 z-0"></canvas>

    <div class="container position-relative z-1 py-5 text-center text-lg-start">
        <div class="row align-items-center min-vh-100 pt-5">
            <div class="col-lg-8 text-white select-none">
                <span class="badge hero-luxe-badge mb-3 animated-item"><i class="fa-solid fa-gem text-warning me-2"></i>THE APEX OF LUXURY LIVING</span>
                <h1 class="display-2 font-cinzel fw-bold mb-4 animated-item" style="letter-spacing: -1px; line-height:1.1;">
                    Discover <br class="d-none d-md-block">
                    <span class="text-gold-gradient">Architectural</span> Masterpieces
                </h1>
                <p class="lead mb-5 fs-5 text-light-muted animated-item" style="max-width: 600px;">
                    Bespoke estates, oceanfront penthouses, and private sanctuaries designed for those who demand the extraordinary.
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3 animated-item">
                    <a href="#featured-listings" class="btn btn-premium px-5 py-3 d-inline-flex align-items-center justify-content-center">
                        <span>View Collection</span><i class="fa-solid fa-arrow-right ms-3 text-warning"></i>
                    </a>
                    <a href="#" class="btn btn-glass-light px-5 py-3 d-inline-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#brandVideoModal">
                        <i class="fa-solid fa-play me-3 text-warning"></i><span>Watch Film</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Animated Search Panel -->
<div class="container position-relative z-2">
    <div class="search-panel-wrapper glass-card-luxury p-4 rounded-4" style="margin-top: -80px;">
        <form action="<?php echo BASE_URL; ?>properties" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-gold-accent small uppercase tracking-wider fw-bold"><i class="fa-solid fa-location-dot me-2"></i>Location</label>
                    <input type="text" name="location" class="form-control luxury-input" placeholder="e.g. Beverly Hills, Malibu">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-gold-accent small uppercase tracking-wider fw-bold"><i class="fa-solid fa-building me-2"></i>Type</label>
                    <select name="category" class="form-select luxury-input">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-gold-accent small uppercase tracking-wider fw-bold"><i class="fa-solid fa-circle-dollar-to-slot me-2"></i>Max Budget</label>
                    <select name="budget_max" class="form-select luxury-input">
                        <option value="">No Limit</option>
                        <option value="500000">$500,000</option>
                        <option value="1000000">$1,000,000</option>
                        <option value="2500000">$2,500,000</option>
                        <option value="5000000">$5,000,000</option>
                        <option value="10000000">$10,000,000</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-gold-accent small uppercase tracking-wider fw-bold"><i class="fa-solid fa-bed me-2"></i>Bedrooms</label>
                    <select name="bedrooms" class="form-select luxury-input">
                        <option value="">Any</option>
                        <option value="1">1+ Bed</option>
                        <option value="2">2+ Bed</option>
                        <option value="3">3+ Bed</option>
                        <option value="4">4+ Bed</option>
                        <option value="5">5+ Bed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-gold-solid w-100 py-3 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-magnifying-glass me-2"></i>Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Company Overview -->
<section class="py-5 my-5 section-dark-luxe overflow-hidden position-relative">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-6 mb-5 mb-lg-0 scroll-reveal-left">
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-feather-pointed me-2"></i>ABOUT LUXEHAVEN</span>
                <h2 class="display-5 font-cinzel text-white fw-bold mb-4">Shaping Masterpieces of <span class="text-gold-gradient">Luxury Living</span></h2>
                <p class="text-light-muted mb-4 fs-6 lh-lg">
                    LuxeHaven represents more than a brokerage; we are curators of structural artwork. Each estate in our private portfolio is handpicked for its design excellence, panoramic settings, and premium automation.
                </p>
                <p class="text-light-muted mb-4 fs-6 lh-lg">
                    With offices in Beverly Hills, Aspen, and Palm Beach, we cater to an elite clientele with absolute privacy and boutique services tailored to family offices and high-net-worth individuals.
                </p>
                <div class="row g-4 mt-2">
                    <div class="col-6">
                        <h4 class="text-gold-gradient font-cinzel display-6 fw-bold mb-1">$4.2B+</h4>
                        <p class="text-white small tracking-wider mb-0">Total Sales Volume</p>
                    </div>
                    <div class="col-6">
                        <h4 class="text-gold-gradient font-cinzel display-6 fw-bold mb-1">98.4%</h4>
                        <p class="text-white small tracking-wider mb-0">Client Retention Rate</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 scroll-reveal-right position-relative">
                <div class="image-reveal-wrapper rounded-4 overflow-hidden shadow-2xl glass-border">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80" alt="Luxury Architecture" class="w-100 h-100 object-fit-cover hover-scale-img" style="min-height: 480px;">
                </div>
                <!-- Glass panel floating item -->
                <div class="glass-floating-badge p-4 rounded-4 position-absolute bottom-0 start-0 m-4 glass-card-dark" style="max-width: 250px;">
                    <i class="fa-solid fa-quote-left text-warning fs-3 mb-2"></i>
                    <p class="text-white small mb-0 font-italic">"An absolute masterpiece of a listing portal. The interface is art itself."</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Properties (Swiper Slider) -->
<section id="featured-listings" class="py-5 bg-dark-deep">
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-5">
            <div class="scroll-reveal-left">
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-star me-2"></i>CURATED PORTFOLIO</span>
                <h2 class="display-5 font-cinzel text-white fw-bold">Featured <span class="text-gold-gradient">Properties</span></h2>
            </div>
            <a href="<?php echo BASE_URL; ?>properties" class="btn btn-outline-gold px-4 py-2 scroll-reveal-right mt-3 mt-md-0">
                View All Listings<i class="fa-solid fa-chevron-right ms-2 fs-xs"></i>
            </a>
        </div>

        <div class="swiper featured-swiper scroll-reveal-fade">
            <div class="swiper-wrapper">
                <?php foreach ($featuredProperties as $prop): ?>
                    <div class="swiper-slide">
                        <div class="property-luxury-card rounded-4 overflow-hidden glass-card-dark position-relative">
                            <!-- Overlay links/icons -->
                            <span class="property-status-tag"><?php echo htmlspecialchars($prop['status']); ?></span>
                            <?php if ($prop['is_featured']): ?>
                                <span class="property-featured-tag"><i class="fa-solid fa-award me-1"></i>FEATURED</span>
                            <?php endif; ?>
                            
                            <div class="property-image-holder">
                                <img src="<?php echo BASE_URL . ($prop['image_path'] ?? 'assets/images/default_property.png'); ?>" alt="<?php echo htmlspecialchars($prop['title']); ?>" class="w-100 h-100 object-fit-cover">
                                <div class="image-gradient-shade"></div>
                            </div>
                            
                            <div class="property-content-holder p-4">
                                <span class="text-gold-accent uppercase small tracking-widest fw-semibold d-block mb-2"><?php echo htmlspecialchars($prop['category_name']); ?></span>
                                <h4 class="font-cinzel text-white fw-bold mb-3"><?php echo htmlspecialchars($prop['title']); ?></h4>
                                <p class="text-light-muted small mb-4 line-clamp-2"><?php echo htmlspecialchars($prop['short_description']); ?></p>
                                
                                <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-15 pt-3">
                                    <h5 class="text-warning font-cinzel mb-0 fw-bold">$<?php echo number_format($prop['price']); ?></h5>
                                    <div class="property-specs text-secondary fs-xs">
                                        <?php if ($prop['bedrooms']): ?>
                                            <span class="me-3"><i class="fa-solid fa-bed text-gold-accent me-1"></i><?php echo $prop['bedrooms']; ?> Beds</span>
                                        <?php endif; ?>
                                        <span><i class="fa-solid fa-maximize text-gold-accent me-1"></i><?php echo number_format($prop['area']); ?> SqFt</span>
                                    </div>
                                </div>
                                <a href="<?php echo BASE_URL; ?>property/<?php echo $prop['slug']; ?>" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Pagination / Navigation -->
            <div class="swiper-pagination mt-4"></div>
        </div>
    </div>
</section>

<!-- Latest Properties -->
<section class="py-5 bg-black">
    <div class="container py-5">
        <div class="text-center mb-5 scroll-reveal-fade">
            <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-clock me-2"></i>JUST ADDED</span>
            <h2 class="display-5 font-cinzel text-white fw-bold">Latest <span class="text-gold-gradient">Creations</span></h2>
            <div class="luxe-separator mx-auto mt-3"></div>
        </div>

        <div class="row g-4">
            <?php foreach ($latestProperties as $prop): ?>
                <div class="col-lg-4 col-md-6 scroll-reveal-fade">
                    <div class="property-luxury-card rounded-4 overflow-hidden glass-card-dark position-relative">
                        <span class="property-status-tag"><?php echo htmlspecialchars($prop['status']); ?></span>
                        
                        <div class="property-image-holder">
                            <img src="<?php echo BASE_URL . ($prop['image_path'] ?? 'assets/images/default_property.png'); ?>" alt="<?php echo htmlspecialchars($prop['title']); ?>" class="w-100 h-100 object-fit-cover">
                            <div class="image-gradient-shade"></div>
                        </div>
                        
                        <div class="property-content-holder p-4">
                            <span class="text-gold-accent uppercase small tracking-widest fw-semibold d-block mb-2"><?php echo htmlspecialchars($prop['category_name']); ?></span>
                            <h4 class="font-cinzel text-white fw-bold mb-3"><?php echo htmlspecialchars($prop['title']); ?></h4>
                            <p class="text-light-muted small mb-4 line-clamp-2"><?php echo htmlspecialchars($prop['short_description']); ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-15 pt-3">
                                <h5 class="text-warning font-cinzel mb-0 fw-bold">$<?php echo number_format($prop['price']); ?></h5>
                                <div class="property-specs text-secondary fs-xs">
                                    <?php if ($prop['bedrooms']): ?>
                                        <span class="me-3"><i class="fa-solid fa-bed text-gold-accent me-1"></i><?php echo $prop['bedrooms']; ?> Beds</span>
                                    <?php endif; ?>
                                    <span><i class="fa-solid fa-maximize text-gold-accent me-1"></i><?php echo number_format($prop['area']); ?> SqFt</span>
                                </div>
                            </div>
                            <a href="<?php echo BASE_URL; ?>property/<?php echo $prop['slug']; ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Luxury Statistics -->
<section class="py-5 section-dark-luxe stat-scroll-trigger">
    <div class="container py-5 text-center">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stat-luxury-box">
                    <i class="fa-solid fa-dollar-sign text-warning display-5 mb-3"></i>
                    <h3 class="display-4 font-cinzel fw-bold text-white stat-counter" data-count="4.2">0</h3>
                    <p class="text-gold-accent small uppercase tracking-wider mb-0">Sales Volume (Billion)</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-luxury-box">
                    <i class="fa-solid fa-map-location-dot text-warning display-5 mb-3"></i>
                    <h3 class="display-4 font-cinzel fw-bold text-white stat-counter" data-count="12">0</h3>
                    <p class="text-gold-accent small uppercase tracking-wider mb-0">Global Cities Active</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-luxury-box">
                    <i class="fa-solid fa-handshake text-warning display-5 mb-3"></i>
                    <h3 class="display-4 font-cinzel fw-bold text-white stat-counter" data-count="500">0</h3>
                    <p class="text-gold-accent small uppercase tracking-wider mb-0">Transactions Closed</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-luxury-box">
                    <i class="fa-solid fa-trophy text-warning display-5 mb-3"></i>
                    <h3 class="display-4 font-cinzel fw-bold text-white stat-counter" data-count="35">0</h3>
                    <p class="text-gold-accent small uppercase tracking-wider mb-0">Industry Awards</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Testimonials & Video Testimonials -->
<section class="py-5 bg-dark-deep">
    <div class="container py-5">
        <div class="text-center mb-5 scroll-reveal-fade">
            <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-comments me-2"></i>ENDORSEMENTS</span>
            <h2 class="display-5 font-cinzel text-white fw-bold">Testimonials from our <span class="text-gold-gradient">Clients</span></h2>
            <div class="luxe-separator mx-auto mt-3"></div>
        </div>

        <div class="row align-items-center mt-5">
            <!-- Testimonial text slider -->
            <div class="col-lg-6 mb-5 mb-lg-0 scroll-reveal-left">
                <div class="swiper testimonials-swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 position-relative">
                                <i class="fa-solid fa-quote-left text-warning fs-1 opacity-20 mb-4 d-block"></i>
                                <p class="text-light-muted fs-5 lh-lg mb-4">
                                    "LuxeHaven provided an elite concierge experience. They negotiated our off-market estate in Malibu with absolute discretion and precision. Their team was professional beyond expectations."
                                </p>
                                <div class="client-info">
                                    <h5 class="text-white font-cinzel fw-bold mb-1">Marcus Vance</h5>
                                    <p class="text-gold-accent small uppercase tracking-wider mb-0">CEO, Vance Capital Group</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 position-relative">
                                <i class="fa-solid fa-quote-left text-warning fs-1 opacity-20 mb-4 d-block"></i>
                                <p class="text-light-muted fs-5 lh-lg mb-4">
                                    "The obsidian penthouse is a true architectural masterpiece. The layout, standard of marble detailing, and the transaction service from LuxeHaven made buying our dream home an absolute joy."
                                </p>
                                <div class="client-info">
                                    <h5 class="text-white font-cinzel fw-bold mb-1">Sophia Loren</h5>
                                    <p class="text-gold-accent small uppercase tracking-wider mb-0">Fashion Designer & Investor</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination testimonials-pagination mt-4 text-start"></div>
                </div>
            </div>

            <!-- Video Testimonials overlay preview -->
            <div class="col-lg-6 scroll-reveal-right">
                <div class="video-testimonial-card rounded-4 overflow-hidden position-relative shadow-2xl glass-border">
                    <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1200&q=80" alt="Client Review Video" class="w-100 h-100 object-fit-cover" style="min-height: 380px;">
                    <div class="image-gradient-shade flex-center"></div>
                    <div class="video-play-btn-wrapper position-absolute top-50 start-50 translate-middle text-center">
                        <button class="play-btn-pulse" data-bs-toggle="modal" data-bs-target="#clientVideoModal">
                            <i class="fa-solid fa-play text-warning"></i>
                        </button>
                        <h6 class="text-white font-cinzel mt-4 uppercase tracking-widest">Video Review - Vance Family Office</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services & Investment Benefits -->
<section class="py-5 bg-black">
    <div class="container py-5">
        <div class="text-center mb-5 scroll-reveal-fade">
            <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-crown me-2"></i>OUR EXPERTISE</span>
            <h2 class="display-5 font-cinzel text-white fw-bold">Exclusive concierge <span class="text-gold-gradient">Services</span></h2>
            <div class="luxe-separator mx-auto mt-3"></div>
        </div>

        <div class="row g-4 mt-3">
            <div class="col-lg-4 scroll-reveal-fade">
                <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 text-center text-lg-start h-100 hover-y-translate">
                    <div class="service-icon-box mb-4">
                        <i class="fa-solid fa-shield-halved text-warning display-6"></i>
                    </div>
                    <h4 class="font-cinzel text-white fw-bold mb-3">Off-Market Acquisition</h4>
                    <p class="text-light-muted small lh-lg mb-0">
                        Gain private entry to architectural masterpieces not published on standard MLS databases. Complete confidentiality.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 scroll-reveal-fade">
                <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 text-center text-lg-start h-100 hover-y-translate">
                    <div class="service-icon-box mb-4">
                        <i class="fa-solid fa-briefcase text-warning display-6"></i>
                    </div>
                    <h4 class="font-cinzel text-white fw-bold mb-3">Asset Structuring</h4>
                    <p class="text-light-muted small lh-lg mb-0">
                        Maximize fiscal shielding and generational transfer advantages by structured ownership models matching legal trusts.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 scroll-reveal-fade">
                <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 text-center text-lg-start h-100 hover-y-translate">
                    <div class="service-icon-box mb-4">
                        <i class="fa-solid fa-chart-line text-warning display-6"></i>
                    </div>
                    <h4 class="font-cinzel text-white fw-bold mb-3">Wealth Management</h4>
                    <p class="text-light-muted small lh-lg mb-0">
                        Treat real estate properties as financial hedges. Detailed yield analysis, rental automation, and portfolio reports.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Luxury FAQ & Awards -->
<section class="py-5 bg-dark-deep">
    <div class="container py-5">
        <div class="row py-3">
            <!-- FAQ Section -->
            <div class="col-lg-7 scroll-reveal-left mb-5 mb-lg-0">
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-circle-question me-2"></i>KNOWLEDGEBASE</span>
                <h2 class="display-6 font-cinzel text-white fw-bold mb-4">Frequently Asked <span class="text-gold-gradient">Questions</span></h2>
                
                <div class="accordion accordion-flush luxury-accordion mt-4" id="faqAccordion">
                    <div class="accordion-item bg-transparent border-bottom border-secondary border-opacity-15 py-3">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button bg-transparent text-white font-cinzel collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                What is an off-market luxury property?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-light-muted small lh-lg">
                                Off-market listings are exclusive property records sold privately without general advertising. This maintains complete client privacy and keeps details secure from database scanning systems.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-transparent border-bottom border-secondary border-opacity-15 py-3">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button bg-transparent text-white font-cinzel collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                How can I verify a property's RERA registration?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-light-muted small lh-lg">
                                Each applicable property is listed with its verified government RERA identifier. You can enter this ID into the national portal database or ask our brokerage concierge for certified documentation.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-transparent border-bottom border-secondary border-opacity-15 py-3">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button bg-transparent text-white font-cinzel collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Do you provide concierge translation & legal services?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-light-muted small lh-lg">
                                Yes. Through our luxury boutique partners, we support international clients with structured corporate legal structuring, trust transfers, certified translations, and corporate bank routing support.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Awards Section -->
            <div class="col-lg-5 scroll-reveal-right text-center text-lg-start">
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-award me-2"></i>ACCREDITATION</span>
                <h2 class="display-6 font-cinzel text-white fw-bold mb-4">Our <span class="text-gold-gradient">Awards</span></h2>
                
                <div class="row g-4 mt-2">
                    <div class="col-6">
                        <div class="award-luxury-card p-4 rounded-4 glass-card-dark text-center">
                            <i class="fa-solid fa-medal text-warning display-6 mb-3"></i>
                            <h6 class="text-white font-cinzel mb-1 fw-bold">CSS Design Award</h6>
                            <p class="text-secondary fs-xs mb-0">Best UI/UX Redesign</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="award-luxury-card p-4 rounded-4 glass-card-dark text-center">
                            <i class="fa-solid fa-trophy text-warning display-6 mb-3"></i>
                            <h6 class="text-white font-cinzel mb-1 fw-bold">Awwwards Honorable</h6>
                            <p class="text-secondary fs-xs mb-0">Luxury Digital Portal</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="award-luxury-card p-4 rounded-4 glass-card-dark text-center">
                            <i class="fa-solid fa-ribbon text-warning display-6 mb-3"></i>
                            <h6 class="text-white font-cinzel mb-1 fw-bold">Real Estate Forum</h6>
                            <p class="text-secondary fs-xs mb-0">Best Luxury Agency</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="award-luxury-card p-4 rounded-4 glass-card-dark text-center">
                            <i class="fa-solid fa-crown text-warning display-6 mb-3"></i>
                            <h6 class="text-white font-cinzel mb-1 fw-bold">International Prop</h6>
                            <p class="text-secondary fs-xs mb-0">Outstanding Architecture</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action (Cinematic Blur Overlay) -->
<section class="py-5 cta-section position-relative overflow-hidden">
    <div class="cta-video-bg position-absolute w-100 h-100 top-0 start-0 z-0 opacity-40">
        <video autoplay muted loop playsinline class="w-100 h-100 object-fit-cover">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-modern-apartment-building-in-a-city-40718-large.mp4" type="video/mp4">
        </video>
    </div>
    <div class="cta-tint-overlay"></div>

    <div class="container position-relative z-1 py-5 text-center">
        <div class="row justify-content-center py-4">
            <div class="col-lg-8 scroll-reveal-fade">
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-envelope me-2"></i>CONTACT CONCIERGE</span>
                <h2 class="display-4 font-cinzel text-white fw-bold mb-4">Acquire Your Next Piece of <br><span class="text-gold-gradient">Structural Artwork</span></h2>
                <p class="lead text-light-muted fs-5 mb-5 mx-auto" style="max-width: 600px;">
                    Our executive brokers are online to arrange private jets, helipad landings, and custom structural viewings.
                </p>
                <a href="<?php echo BASE_URL; ?>contact" class="btn btn-premium px-5 py-3 btn-lg">
                    Schedule Private Tour<i class="fa-solid fa-headset ms-3 text-warning"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Modals for films -->
<div class="modal fade" id="brandVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-black border-secondary border-opacity-15">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="clientVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-black border-secondary border-opacity-15">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

