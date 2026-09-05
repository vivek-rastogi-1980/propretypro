<!-- Full Screen Cinematic Hero with Swiper Slideshow -->
<section class="hero-section-cinematic position-relative overflow-hidden">
    <!-- Main Swiper Slideshow -->
    <div class="swiper hero-main-swiper h-100 w-100 position-absolute top-0 start-0">
        <div class="swiper-wrapper">
            <?php 
            $slides = !empty($sliderProperties) ? $sliderProperties : (!empty($featuredProperties) ? array_slice($featuredProperties, 0, 5) : []);
            if (empty($slides)) {
                // Fallback slides if no properties are found
                $slides = [
                    [
                        'image_path' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80',
                        'title' => 'The Obsidian Mansion',
                        'category_name' => 'VILLAS',
                        'short_description' => 'Bespoke estate designed for those who demand the extraordinary, located in Malibu.',
                        'price' => 14500000,
                        'slug' => '#'
                    ]
                ];
            }
            foreach ($slides as $slide):
                $imgPath = !empty($slide['image_path']) && str_starts_with($slide['image_path'], 'http') ? $slide['image_path'] : (!empty($slide['image_path']) ? BASE_URL . $slide['image_path'] : BASE_URL . 'assets/images/default_property.png');
            ?>
                <div class="swiper-slide position-relative">
                    <div class="hero-slide-bg" style="background-image: url('<?php echo $imgPath; ?>');"></div>
                    <div class="video-overlay-tint"></div>
                    
                    <div class="container h-100 position-relative z-2 d-flex align-items-center">
                        <div class="row w-100">
                            <div class="col-lg-8 text-white select-none hero-content-col">
                                <h1 class="hero-top-h1 font-cinzel fw-bold mb-3 animated-hero-item" style="font-size: 14px;">
                                    Premium Land for Sale &amp; Investment in Uttarakhand
                                </h1>
                                <h2 class="display-2 font-cinzel fw-bold mb-4 animated-hero-item text-white">
                                    <?php echo htmlspecialchars($slide['title']); ?>
                                </h2>
                                <div class="d-flex flex-wrap align-items-center gap-3 animated-hero-item">
                                    <a href="<?php echo BASE_URL . 'property/' . $slide['slug']; ?>" class="btn btn-premium px-5 py-3 d-inline-flex align-items-center justify-content-center">
                                        <span>Explore Listing</span><i class="fa-solid fa-arrow-right ms-3 text-warning"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Three.js Canvas Placeholder -->
    <canvas id="hero-three-canvas" class="position-absolute w-100 h-100 top-0 start-0 z-0 pointer-events-none"></canvas>

    <!-- Floating Thumbnails Preview Gallery (WOW Feeling) -->
    <div class="hero-thumbs-container position-absolute z-3 d-none d-lg-block">
        <div class="swiper hero-thumbs-swiper">
            <div class="swiper-wrapper">
                <?php foreach ($slides as $slide): 
                    $imgPath = !empty($slide['image_path']) && str_starts_with($slide['image_path'], 'http') ? $slide['image_path'] : (!empty($slide['image_path']) ? BASE_URL . $slide['image_path'] : BASE_URL . 'assets/images/default_property.png');
                ?>
                    <div class="swiper-slide">
                        <div class="thumb-preview-box rounded-3 overflow-hidden position-relative border border-white border-opacity-10">
                            <img src="<?php echo $imgPath; ?>" class="w-100 h-100 object-fit-cover" alt="preview">
                            <div class="thumb-overlay"></div>
                            <div class="thumb-title font-cinzel text-white text-truncate"><?php echo htmlspecialchars($slide['title']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Swiper Controls -->
        <div class="hero-swiper-controls d-flex justify-content-between align-items-center mt-3 px-1">
            <div class="hero-prev-btn text-white"><i class="fa-solid fa-arrow-left"></i></div>
            <div class="hero-swiper-fraction font-cinzel text-white"></div>
            <div class="hero-next-btn text-white"><i class="fa-solid fa-arrow-right"></i></div>
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
                    <input type="text" name="location" class="form-control luxury-input" placeholder="e.g. Dehradun, Thano, Mussoorie, Rishikesh">
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
                        <option value="2500000">₹25 Lakhs</option>
                        <option value="5000000">₹50 Lakhs</option>
                        <option value="10000000">₹1 Crore</option>
                        <option value="25000000">₹2.5 Crore</option>
                        <option value="50000000">₹5 Crore</option>
                        <option value="100000000">₹10 Crore</option>
                        <option value="250000000">₹25+ Crore</option>
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
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-feather-pointed me-2"></i><?php echo htmlspecialchars($globalSettings['home_overview_badge'] ?? 'ABOUT VIGTEZ REALTY'); ?></span>
                <h2 class="display-5 font-cinzel text-white fw-bold mb-4"><?php echo htmlspecialchars($globalSettings['home_overview_title'] ?? 'Shaping Masterpieces of Luxury Living'); ?></h2>
                <p class="text-light-muted mb-4 fs-6 lh-lg">
                    <?php echo nl2br(htmlspecialchars($globalSettings['home_overview_desc_1'] ?? 'Vigtez Realty Pvt. Ltd. is a Uttarakhand-based real estate company specializing in premium land, villas, and second-home projects. We create investment opportunities in strategically located destinations across Uttarakhand with a focus on quality, transparency, and long-term value.')); ?>
                </p>
                <p class="text-light-muted mb-4 fs-6 lh-lg">
                    <?php echo nl2br(htmlspecialchars($globalSettings['home_overview_desc_2'] ?? 'incorporated in 2026, is a Uttarakhand-based real estate company focused on premium land, villas, and second-home projects. We aim to deliver trusted, transparent, and value-driven real estate opportunities.')); ?>
                </p>
                <div class="row g-4 mt-2">
                    <div class="col-6">
                        <h4 class="text-gold-gradient font-cinzel display-6 fw-bold mb-1"><?php echo htmlspecialchars($globalSettings['home_overview_stat1_val'] ?? '$4.2B+'); ?></h4>
                        <p class="text-white small tracking-wider mb-0"><?php echo htmlspecialchars($globalSettings['home_overview_stat1_lbl'] ?? 'Total Sales Volume'); ?></p>
                    </div>
                    <div class="col-6">
                        <h4 class="text-gold-gradient font-cinzel display-6 fw-bold mb-1"><?php echo htmlspecialchars($globalSettings['home_overview_stat2_val'] ?? '98.4%'); ?></h4>
                        <p class="text-white small tracking-wider mb-0"><?php echo htmlspecialchars($globalSettings['home_overview_stat2_lbl'] ?? 'Client Retention Rate'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 scroll-reveal-right position-relative">
                <div class="image-reveal-wrapper rounded-4 overflow-hidden shadow-2xl glass-border">
                    <img src="<?php echo !empty($globalSettings['home_overview_image']) ? BASE_URL . $globalSettings['home_overview_image'] : 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80'; ?>" alt="Luxury Architecture" class="w-100 h-100 object-fit-cover hover-scale-img" style="min-height: 480px;">
                </div>
                <!-- Glass panel floating item -->
                <div class="glass-floating-badge p-4 rounded-4 position-absolute bottom-0 start-0 m-4 glass-card-dark" style="max-width: 250px;">
                    <i class="fa-solid fa-quote-left text-warning fs-3 mb-2"></i>
                    <p class="text-white small mb-0 font-italic"><?php echo htmlspecialchars($globalSettings['home_overview_quote'] ?? '"An absolute masterpiece of a listing portal. The interface is art itself."'); ?></p>
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
                                <img src="<?php echo BASE_URL . ($prop['image_path'] ?? 'assets/images/default_property.png'); ?>" onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/default_property.png';" alt="<?php echo htmlspecialchars($prop['title']); ?>" class="w-100 h-100 object-fit-cover">
                                <div class="image-gradient-shade"></div>
                            </div>
                            
                            <div class="property-content-holder p-4">
                                <span class="text-gold-accent uppercase small tracking-widest fw-semibold d-block mb-2"><?php echo htmlspecialchars($prop['category_name']); ?></span>
                                <h4 class="font-cinzel text-white fw-bold mb-3"><?php echo htmlspecialchars($prop['title']); ?></h4>
                                <p class="text-light-muted small mb-4 line-clamp-2"><?php echo htmlspecialchars($prop['short_description']); ?></p>
                                
                                <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-15 pt-3">
                                    <h5 class="text-warning font-cinzel mb-0 fw-bold">₹<?php echo number_format($prop['price']); ?></h5>
                                    <div class="property-specs text-secondary fs-xs">
                                        <?php if ($prop['bedrooms']): ?>
                                            <span class="me-3"><i class="fa-solid fa-bed text-gold-accent me-1"></i><?php echo $prop['bedrooms']; ?> Beds</span>
                                        <?php endif; ?>
                                        <span><i class="fa-solid fa-maximize text-gold-accent me-1"></i><?php echo number_format($prop['area']); ?> <?php echo htmlspecialchars($prop['area_unit'] ?? 'Sq. Ft.'); ?></span>
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
                            <img src="<?php echo BASE_URL . ($prop['image_path'] ?? 'assets/images/default_property.png'); ?>" onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/default_property.png';" alt="<?php echo htmlspecialchars($prop['title']); ?>" class="w-100 h-100 object-fit-cover">
                            <div class="image-gradient-shade"></div>
                        </div>
                        
                        <div class="property-content-holder p-4">
                            <span class="text-gold-accent uppercase small tracking-widest fw-semibold d-block mb-2"><?php echo htmlspecialchars($prop['category_name']); ?></span>
                            <h4 class="font-cinzel text-white fw-bold mb-3"><?php echo htmlspecialchars($prop['title']); ?></h4>
                            <p class="text-light-muted small mb-4 line-clamp-2"><?php echo htmlspecialchars($prop['short_description']); ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-15 pt-3">
                                <h5 class="text-warning font-cinzel mb-0 fw-bold">₹<?php echo number_format($prop['price']); ?></h5>
                                <div class="property-specs text-secondary fs-xs">
                                    <?php if ($prop['bedrooms']): ?>
                                        <span class="me-3"><i class="fa-solid fa-bed text-gold-accent me-1"></i><?php echo $prop['bedrooms']; ?> Beds</span>
                                    <?php endif; ?>
                                    <span><i class="fa-solid fa-maximize text-gold-accent me-1"></i><?php echo number_format($prop['area']); ?> <?php echo htmlspecialchars($prop['area_unit'] ?? 'Sq. Ft.'); ?></span>
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
                    <i class="fa-solid fa-indian-rupee-sign text-warning display-5 mb-3"></i>
                    <h3 class="display-4 font-cinzel fw-bold text-white stat-counter" data-count="<?php echo htmlspecialchars($globalSettings['home_stat1_num'] ?? '4.2'); ?>">0</h3>
                    <p class="text-gold-accent small uppercase tracking-wider mb-0"><?php echo htmlspecialchars($globalSettings['home_stat1_lbl'] ?? 'Sales Volume (Billion)'); ?></p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-luxury-box">
                    <i class="fa-solid fa-map-location-dot text-warning display-5 mb-3"></i>
                    <h3 class="display-4 font-cinzel fw-bold text-white stat-counter" data-count="<?php echo htmlspecialchars($globalSettings['home_stat2_num'] ?? '12'); ?>">0</h3>
                    <p class="text-gold-accent small uppercase tracking-wider mb-0"><?php echo htmlspecialchars($globalSettings['home_stat2_lbl'] ?? 'Global Cities Active'); ?></p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-luxury-box">
                    <i class="fa-solid fa-handshake text-warning display-5 mb-3"></i>
                    <h3 class="display-4 font-cinzel fw-bold text-white stat-counter" data-count="<?php echo htmlspecialchars($globalSettings['home_stat3_num'] ?? '500'); ?>">0</h3>
                    <p class="text-gold-accent small uppercase tracking-wider mb-0"><?php echo htmlspecialchars($globalSettings['home_stat3_lbl'] ?? 'Transactions Closed'); ?></p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-luxury-box">
                    <i class="fa-solid fa-trophy text-warning display-5 mb-3"></i>
                    <h3 class="display-4 font-cinzel fw-bold text-white stat-counter" data-count="<?php echo htmlspecialchars($globalSettings['home_stat4_num'] ?? '35'); ?>">0</h3>
                    <p class="text-gold-accent small uppercase tracking-wider mb-0"><?php echo htmlspecialchars($globalSettings['home_stat4_lbl'] ?? 'Industry Awards'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Customer Testimonials & Video Testimonials -->
<section class="py-5 bg-dark-deep">
    <div class="container py-5">
        <div class="text-center mb-5 scroll-reveal-fade">
            <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-comments me-2"></i><?php echo htmlspecialchars($globalSettings['home_testimonials_badge'] ?? 'ENDORSEMENTS'); ?></span>
            <h2 class="display-5 font-cinzel text-white fw-bold"><?php echo htmlspecialchars($globalSettings['home_testimonials_title'] ?? 'Testimonials from our Clients'); ?></h2>
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
                                    <?php echo htmlspecialchars($globalSettings['home_testimonial1_text'] ?? 'Vigtez Realty provided an exceptional property advisory experience. They helped us acquire our prime hillside estate in Thano Valley, Dehradun with absolute discretion, clean land titles, and effortless legal verification. Truly unmatched professionalism.'); ?>
                                </p>
                                <div class="client-info">
                                    <h5 class="text-white font-cinzel fw-bold mb-1"><?php echo htmlspecialchars($globalSettings['home_testimonial1_author'] ?? 'Rajesh Singhania'); ?></h5>
                                    <p class="text-gold-accent small uppercase tracking-wider mb-0"><?php echo htmlspecialchars($globalSettings['home_testimonial1_role'] ?? 'Managing Director, Singhania Enterprises, New Delhi'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 position-relative">
                                <i class="fa-solid fa-quote-left text-warning fs-1 opacity-20 mb-4 d-block"></i>
                                <p class="text-light-muted fs-5 lh-lg mb-4">
                                    <?php echo htmlspecialchars($globalSettings['home_testimonial2_text'] ?? 'Finding a clear-title luxury villa and scenic farmland in Uttarakhand can be daunting, but Vigtez Realty made our retreat acquisition seamless. Their transparency, RERA compliance, and personalised concierge service made investing in Dehradun an absolute pleasure.'); ?>
                                </p>
                                <div class="client-info">
                                    <h5 class="text-white font-cinzel fw-bold mb-1"><?php echo htmlspecialchars($globalSettings['home_testimonial2_author'] ?? 'Dr. Ananya Sharma'); ?></h5>
                                    <p class="text-gold-accent small uppercase tracking-wider mb-0"><?php echo htmlspecialchars($globalSettings['home_testimonial2_role'] ?? 'Cardiologist & Estate Owner, Gurugram'); ?></p>
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
                    <img src="<?php echo !empty($globalSettings['home_testimonial_video_image']) ? BASE_URL . $globalSettings['home_testimonial_video_image'] : 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1200&q=80'; ?>" alt="Client Review Video" class="w-100 h-100 object-fit-cover" style="min-height: 380px;">
                    <div class="image-gradient-shade flex-center"></div>
                    <div class="video-play-btn-wrapper position-absolute top-50 start-50 translate-middle text-center">
                        <button class="play-btn-pulse" data-bs-toggle="modal" data-bs-target="#clientVideoModal">
                            <i class="fa-solid fa-play text-warning"></i>
                        </button>
                        <h6 class="text-white font-cinzel mt-4 uppercase tracking-widest"><?php echo htmlspecialchars($globalSettings['home_testimonial_video_title'] ?? 'Client Experience - Singhania Family, Estate in Dehradun'); ?></h6>
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
            <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-crown me-2"></i><?php echo htmlspecialchars($globalSettings['home_services_badge'] ?? 'OUR EXPERTISE'); ?></span>
            <h2 class="display-5 font-cinzel text-white fw-bold"><?php echo htmlspecialchars($globalSettings['home_services_title'] ?? 'Exclusive concierge Services'); ?></h2>
            <div class="luxe-separator mx-auto mt-3"></div>
        </div>

        <div class="row g-4 mt-3">
            <div class="col-lg-4 scroll-reveal-fade">
                <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 text-center text-lg-start h-100 hover-y-translate">
                    <div class="service-icon-box mb-4">
                        <i class="fa-solid <?php echo htmlspecialchars($globalSettings['home_service1_icon'] ?? 'fa-shield-halved'); ?> text-warning display-6"></i>
                    </div>
                    <h4 class="font-cinzel text-white fw-bold mb-3"><?php echo htmlspecialchars($globalSettings['home_service1_title'] ?? 'Off-Market Acquisition'); ?></h4>
                    <p class="text-light-muted small lh-lg mb-0">
                        <?php echo htmlspecialchars($globalSettings['home_service1_desc'] ?? 'Gain private entry to architectural masterpieces not published on standard MLS databases. Complete confidentiality.'); ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 scroll-reveal-fade">
                <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 text-center text-lg-start h-100 hover-y-translate">
                    <div class="service-icon-box mb-4">
                        <i class="fa-solid <?php echo htmlspecialchars($globalSettings['home_service2_icon'] ?? 'fa-briefcase'); ?> text-warning display-6"></i>
                    </div>
                    <h4 class="font-cinzel text-white fw-bold mb-3"><?php echo htmlspecialchars($globalSettings['home_service2_title'] ?? 'Asset Structuring'); ?></h4>
                    <p class="text-light-muted small lh-lg mb-0">
                        <?php echo htmlspecialchars($globalSettings['home_service2_desc'] ?? 'Maximize fiscal shielding and generational transfer advantages by structured ownership models matching legal trusts.'); ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-4 scroll-reveal-fade">
                <div class="glass-card-dark p-5 rounded-4 border-secondary border-opacity-15 text-center text-lg-start h-100 hover-y-translate">
                    <div class="service-icon-box mb-4">
                        <i class="fa-solid <?php echo htmlspecialchars($globalSettings['home_service3_icon'] ?? 'fa-chart-line'); ?> text-warning display-6"></i>
                    </div>
                    <h4 class="font-cinzel text-white fw-bold mb-3"><?php echo htmlspecialchars($globalSettings['home_service3_title'] ?? 'Wealth Management'); ?></h4>
                    <p class="text-light-muted small lh-lg mb-0">
                        <?php echo htmlspecialchars($globalSettings['home_service3_desc'] ?? 'Treat real estate properties as financial hedges. Detailed yield analysis, rental automation, and portfolio reports.'); ?>
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
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-circle-question me-2"></i><?php echo htmlspecialchars($globalSettings['home_faq_badge'] ?? 'KNOWLEDGEBASE'); ?></span>
                <h2 class="display-6 font-cinzel text-white fw-bold mb-4"><?php echo htmlspecialchars($globalSettings['home_faq_title'] ?? 'Frequently Asked Questions'); ?></h2>
                
                <div class="accordion accordion-flush luxury-accordion mt-4" id="faqAccordion">
                    <div class="accordion-item bg-transparent border-bottom border-secondary border-opacity-15 py-3">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button bg-transparent text-white font-cinzel collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <?php echo htmlspecialchars($globalSettings['home_faq1_q'] ?? 'What is an off-market luxury property?'); ?>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-light-muted small lh-lg">
                                <?php echo htmlspecialchars($globalSettings['home_faq1_a'] ?? 'Off-market listings are exclusive property records sold privately without general advertising. This maintains complete client privacy and keeps details secure from database scanning systems.'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-transparent border-bottom border-secondary border-opacity-15 py-3">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button bg-transparent text-white font-cinzel collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <?php echo htmlspecialchars($globalSettings['home_faq2_q'] ?? "How can I verify a property's RERA registration?"); ?>
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-light-muted small lh-lg">
                                <?php echo htmlspecialchars($globalSettings['home_faq2_a'] ?? 'Each applicable property is listed with its verified government RERA identifier. You can enter this ID into the national portal database or ask our brokerage concierge for certified documentation.'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item bg-transparent border-bottom border-secondary border-opacity-15 py-3">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button bg-transparent text-white font-cinzel collapsed fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <?php echo htmlspecialchars($globalSettings['home_faq3_q'] ?? 'Do you provide concierge translation & legal services?'); ?>
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-light-muted small lh-lg">
                                <?php echo htmlspecialchars($globalSettings['home_faq3_a'] ?? 'Yes. Through our luxury boutique partners, we support international clients with structured corporate legal structuring, trust transfers, certified translations, and corporate bank routing support.'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Awards Section -->
            <div class="col-lg-5 scroll-reveal-right text-center text-lg-start">
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-award me-2"></i><?php echo htmlspecialchars($globalSettings['home_awards_badge'] ?? 'ACCREDITATION'); ?></span>
                <h2 class="display-6 font-cinzel text-white fw-bold mb-4"><?php echo htmlspecialchars($globalSettings['home_awards_title'] ?? 'Our Awards'); ?></h2>
                
                <div class="row g-4 mt-2">
                    <div class="col-6">
                        <div class="award-luxury-card p-4 rounded-4 glass-card-dark text-center">
                            <i class="fa-solid <?php echo htmlspecialchars($globalSettings['home_award1_icon'] ?? 'fa-medal'); ?> text-warning display-6 mb-3"></i>
                            <h6 class="text-white font-cinzel mb-1 fw-bold"><?php echo htmlspecialchars($globalSettings['home_award1_title'] ?? 'CSS Design Award'); ?></h6>
                            <p class="text-secondary fs-xs mb-0"><?php echo htmlspecialchars($globalSettings['home_award1_text'] ?? 'Best UI/UX Redesign'); ?></p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="award-luxury-card p-4 rounded-4 glass-card-dark text-center">
                            <i class="fa-solid <?php echo htmlspecialchars($globalSettings['home_award2_icon'] ?? 'fa-trophy'); ?> text-warning display-6 mb-3"></i>
                            <h6 class="text-white font-cinzel mb-1 fw-bold"><?php echo htmlspecialchars($globalSettings['home_award2_title'] ?? 'Awwwards Honorable'); ?></h6>
                            <p class="text-secondary fs-xs mb-0"><?php echo htmlspecialchars($globalSettings['home_award2_text'] ?? 'Luxury Digital Portal'); ?></p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="award-luxury-card p-4 rounded-4 glass-card-dark text-center">
                            <i class="fa-solid <?php echo htmlspecialchars($globalSettings['home_award3_icon'] ?? 'fa-ribbon'); ?> text-warning display-6 mb-3"></i>
                            <h6 class="text-white font-cinzel mb-1 fw-bold"><?php echo htmlspecialchars($globalSettings['home_award3_title'] ?? 'Real Estate Forum'); ?></h6>
                            <p class="text-secondary fs-xs mb-0"><?php echo htmlspecialchars($globalSettings['home_award3_text'] ?? 'Best Luxury Agency'); ?></p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="award-luxury-card p-4 rounded-4 glass-card-dark text-center">
                            <i class="fa-solid <?php echo htmlspecialchars($globalSettings['home_award4_icon'] ?? 'fa-crown'); ?> text-warning display-6 mb-3"></i>
                            <h6 class="text-white font-cinzel mb-1 fw-bold"><?php echo htmlspecialchars($globalSettings['home_award4_title'] ?? 'International Prop'); ?></h6>
                            <p class="text-secondary fs-xs mb-0"><?php echo htmlspecialchars($globalSettings['home_award4_text'] ?? 'Outstanding Architecture'); ?></p>
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
            <source src="<?php echo htmlspecialchars($globalSettings['home_cta_video_url'] ?? 'https://assets.mixkit.co/videos/preview/mixkit-modern-apartment-building-in-a-city-40718-large.mp4'); ?>" type="video/mp4">
        </video>
    </div>
    <div class="cta-tint-overlay"></div>

    <div class="container position-relative z-1 py-5 text-center">
        <div class="row justify-content-center py-4">
            <div class="col-lg-8 scroll-reveal-fade">
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-envelope me-2"></i><?php echo htmlspecialchars($globalSettings['home_cta_badge'] ?? 'CONTACT WITH US'); ?></span>
                <h2 class="display-4 font-cinzel text-white fw-bold mb-4"><?php echo htmlspecialchars($globalSettings['home_cta_title'] ?? 'Invest in Your Next Dream Space'); ?></h2>
                <p class="lead text-light-muted fs-5 mb-5 mx-auto" style="max-width: 600px;">
                    <?php echo htmlspecialchars($globalSettings['home_cta_desc'] ?? 'Experience beautifully crafted spaces where elegant design meets comfort and timeless appeal.'); ?>
                </p>
                <a href="<?php echo BASE_URL; ?>contact" class="btn btn-premium px-5 py-3 btn-lg">
                    Schedule Private Tour<i class="fa-solid fa-headset ms-3 text-warning"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Modals for films -->
<div class="modal fade" id="brandVideoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-black border border-secondary border-opacity-25 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header border-bottom border-secondary border-opacity-15 py-3 px-4 d-flex justify-content-between align-items-center bg-dark bg-opacity-75">
                <h5 class="modal-title font-cinzel text-white small fw-bold mb-0"><i class="fa-solid fa-film text-warning me-2"></i>Brand Story Film</h5>
                <button type="button" class="modal-video-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body p-0 bg-black">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?enablejsapi=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="clientVideoModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-black border border-secondary border-opacity-25 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header border-bottom border-secondary border-opacity-15 py-3 px-4 d-flex justify-content-between align-items-center bg-dark bg-opacity-75">
                <h5 class="modal-title font-cinzel text-white small fw-bold mb-0"><i class="fa-solid fa-film text-warning me-2"></i><?php echo htmlspecialchars($globalSettings['home_testimonial_video_title'] ?? 'Video Review - Vance Family Office'); ?></h5>
                <button type="button" class="modal-video-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body p-0 bg-black">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($globalSettings['home_testimonial_video_youtube_id'] ?? 'dQw4w9WgXcQ'); ?>?enablejsapi=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

