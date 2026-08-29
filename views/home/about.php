<!-- Cinematic Header -->
<section class="luxury-inner-hero position-relative d-flex align-items-center overflow-hidden">
    <div class="inner-hero-bg" style="background-image: url('<?php echo !empty($globalSettings['about_hero_image']) ? BASE_URL . $globalSettings['about_hero_image'] : 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80'; ?>');"></div>
    <div class="video-overlay-tint"></div>
    
    <div class="container position-relative z-1 text-center py-5">
        <h1 class="display-3 font-cinzel theme-blue-whiteOne fw-bold" style="color:white"><?php echo htmlspecialchars($globalSettings['about_hero_title'] ?? 'About Vigtez Realty'); ?></h1>
        <p class="lead text-light-muted mx-auto" style="max-width: 600px;"><?php echo htmlspecialchars($globalSettings['about_hero_desc'] ?? 'A legacy of beautiful designs, with personal attention and complete client privacy.'); ?></p>
    </div>
</section>

<!-- Mission & Identity -->
<section class="py-5 bg-black text-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 scroll-reveal-left">
                <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-hotel me-2"></i><?php echo htmlspecialchars($globalSettings['about_identity_badge'] ?? 'OUR IDENTITY'); ?></span>
                <h2 class="display-6 font-cinzel text-white fw-bold mb-4"><?php echo htmlspecialchars($globalSettings['about_identity_title'] ?? 'Vigtez Realty Pvt. Ltd Works of Art'); ?></h2>
                <p class="text-light-muted fs-6 lh-lg mb-4">
                    <?php echo nl2br(htmlspecialchars($globalSettings['about_identity_desc1'] ?? 'Vigtez Realty Pvt. Ltd., incorporated in 2026, is a Uttarakhand-based real estate company focused on premium land, villas, and second-home projects. We aim to deliver trusted, transparent, and value-driven real estate opportunities.')); ?>
                </p>
                <p class="text-light-muted fs-6 lh-lg mb-4">
                    <?php echo nl2br(htmlspecialchars($globalSettings['about_identity_desc2'] ?? 'Today, we manage a private inventory spanning 5 cities, supporting family offices, and private clients in acquiring ocean bluffs, historical estates, and sustainable automated penthouses.')); ?>
                </p>
            </div>
            <div class="col-lg-6 scroll-reveal-right">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="glass-card-dark p-4 rounded-4 text-center border-secondary border-opacity-15 h-100">
                            <i class="fa-solid <?php echo htmlspecialchars($globalSettings['about_identity_card1_icon'] ?? 'fa-handshake-angle'); ?> text-warning display-6 mb-3"></i>
                            <h5 class="text-white font-cinzel fw-bold mb-2"><?php echo htmlspecialchars($globalSettings['about_identity_card1_title'] ?? 'Discretion'); ?></h5>
                            <p class="text-secondary small mb-0"><?php echo htmlspecialchars($globalSettings['about_identity_card1_text'] ?? 'We never publish off-market transactional prices or client identities. Absolute privacy.'); ?></p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="glass-card-dark p-4 rounded-4 text-center border-secondary border-opacity-15 h-100">
                            <i class="fa-solid <?php echo htmlspecialchars($globalSettings['about_identity_card2_icon'] ?? 'fa-compass-drafting'); ?> text-warning display-6 mb-3"></i>
                            <h5 class="text-white font-cinzel fw-bold mb-2"><?php echo htmlspecialchars($globalSettings['about_identity_card2_title'] ?? 'Design Focus'); ?></h5>
                            <p class="text-secondary small mb-0"><?php echo htmlspecialchars($globalSettings['about_identity_card2_text'] ?? 'We prioritize structural design, smart home automation, and high-end marble details.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Luxury Team Grid -->
<section class="py-5 bg-dark-deep">
    <div class="container py-5">
        <div class="text-center mb-5 scroll-reveal-fade">
            <span class="text-gold-accent uppercase tracking-widest fw-bold d-block mb-3 fs-xs"><i class="fa-solid fa-users me-2"></i><?php echo htmlspecialchars($globalSettings['about_leadership_badge'] ?? 'THE CONCIERGE'); ?></span>
            <h2 class="display-5 font-cinzel text-white fw-bold"><?php echo htmlspecialchars($globalSettings['about_leadership_title'] ?? 'Executive Leadership'); ?></h2>
            <div class="luxe-separator mx-auto mt-3"></div>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-lg-4 col-md-6 scroll-reveal-fade">
                <div class="team-luxury-card rounded-4 overflow-hidden glass-card-dark position-relative">
                    <div class="team-img-wrapper" style="height: 380px;">
                        <img src="<?php echo !empty($globalSettings['about_team1_image']) ? BASE_URL . $globalSettings['about_team1_image'] : 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80'; ?>" alt="Executive Broker" class="w-100 h-100 object-fit-cover">
                    </div>
                    <div class="team-content p-4 text-center text-white">
                        <h4 class="font-cinzel fw-bold mb-1"><?php echo htmlspecialchars($globalSettings['about_team1_name'] ?? 'Charles Sterling'); ?></h4>
                        <p class="text-gold-accent small uppercase tracking-wider mb-3"><?php echo htmlspecialchars($globalSettings['about_team1_role'] ?? 'Founder & Chief Advisor'); ?></p>
                        <div class="team-social-links">
                            <a href="#" class="text-secondary mx-2"><i class="fa-brands fa-linkedin"></i></a>
                            <a href="#" class="text-secondary mx-2"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 scroll-reveal-fade">
                <div class="team-luxury-card rounded-4 overflow-hidden glass-card-dark position-relative">
                    <div class="team-img-wrapper" style="height: 380px;">
                        <img src="<?php echo !empty($globalSettings['about_team2_image']) ? BASE_URL . $globalSettings['about_team2_image'] : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=800&q=80'; ?>" alt="Executive Broker" class="w-100 h-100 object-fit-cover">
                    </div>
                    <div class="team-content p-4 text-center text-white">
                        <h4 class="font-cinzel fw-bold mb-1"><?php echo htmlspecialchars($globalSettings['about_team2_name'] ?? 'Alexandra Vance'); ?></h4>
                        <p class="text-gold-accent small uppercase tracking-wider mb-3"><?php echo htmlspecialchars($globalSettings['about_team2_role'] ?? 'Managing Partner (Beverly Hills)'); ?></p>
                        <div class="team-social-links">
                            <a href="#" class="text-secondary mx-2"><i class="fa-brands fa-linkedin"></i></a>
                            <a href="#" class="text-secondary mx-2"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 scroll-reveal-fade">
                <div class="team-luxury-card rounded-4 overflow-hidden glass-card-dark position-relative">
                    <div class="team-img-wrapper" style="height: 380px;">
                        <img src="<?php echo !empty($globalSettings['about_team3_image']) ? BASE_URL . $globalSettings['about_team3_image'] : 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=800&q=80'; ?>" alt="Executive Broker" class="w-100 h-100 object-fit-cover">
                    </div>
                    <div class="team-content p-4 text-center text-white">
                        <h4 class="font-cinzel fw-bold mb-1"><?php echo htmlspecialchars($globalSettings['about_team3_name'] ?? 'Julien Beaumont'); ?></h4>
                        <p class="text-gold-accent small uppercase tracking-wider mb-3"><?php echo htmlspecialchars($globalSettings['about_team3_role'] ?? 'Head of Wealth & Asset Advisory'); ?></p>
                        <div class="team-social-links">
                            <a href="#" class="text-secondary mx-2"><i class="fa-brands fa-linkedin"></i></a>
                            <a href="#" class="text-secondary mx-2"><i class="fa-solid fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
