<?php
use App\Helpers\CSRFHelper;

// Helper to escape values safely
function val(array $settings, string $key, string $default = ''): string {
    return htmlspecialchars($settings[$key] ?? $default);
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h1 class="fw-bold mb-1">Manage Pages Content</h1>
        <p class="text-muted mb-0">Update text nodes and upload custom images for the Home, About, and Contact pages.</p>
    </div>
</div>

<!-- Alert messages -->
<?php if (isset($_SESSION['pages_success'])): ?>
    <div class="alert alert-success border-0 glass-card p-3 mb-4 animated-fade-in" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2) !important;">
        <i class="bi bi-check-circle-fill me-2 text-success"></i> <?php echo $_SESSION['pages_success']; unset($_SESSION['pages_success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['pages_error'])): ?>
    <div class="alert alert-danger border-0 glass-card p-3 mb-4" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2) !important;">
        <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> <?php echo $_SESSION['pages_error']; unset($_SESSION['pages_error']); ?>
    </div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>admin/pages" method="POST" enctype="multipart/form-data">
    <?php echo CSRFHelper::getField(); ?>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4 admin-luxury-tabs border border-secondary border-opacity-10 p-2 rounded-4 bg-dark-deep" id="pagesTab" role="tablist" style="max-width: fit-content;">
        <li class="nav-item" role="presentation">
            <button class="nav-link active px-4 py-2 rounded-3 fw-bold" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab"><i class="fa-solid fa-house me-2"></i>Home Page</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-2 rounded-3 fw-bold" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab"><i class="fa-solid fa-circle-info me-2"></i>About Page</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-4 py-2 rounded-3 fw-bold" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab"><i class="fa-solid fa-paper-plane me-2"></i>Contact Page</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="pagesTabContent">
        
        <!-- HOME PAGE TAB -->
        <div class="tab-pane fade show active" id="home" role="tabpanel">
            <div class="row g-4">
                
                <!-- Overview Section -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-feather-pointed me-2"></i>Company Overview Section</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Overview Badge Label</label>
                                <input type="text" name="home_overview_badge" class="form-control" value="<?php echo val($settings, 'home_overview_badge', 'ABOUT VIGTEZ REALTY'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Overview Main Title</label>
                                <input type="text" name="home_overview_title" class="form-control" value="<?php echo val($settings, 'home_overview_title', 'Shaping Masterpieces of Luxury Living'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Overview Description paragraph 1</label>
                                <textarea name="home_overview_desc_1" rows="3" class="form-control"><?php echo val($settings, 'home_overview_desc_1'); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Overview Description paragraph 2</label>
                                <textarea name="home_overview_desc_2" rows="3" class="form-control"><?php echo val($settings, 'home_overview_desc_2'); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Upload Overview Image (Max 8MB, JPG/PNG/WEBP/HEIC)</label>
                                <input type="file" name="home_overview_image" class="form-control mb-2" accept="image/*,.heic,.heif">
                                <?php if (!empty($settings['home_overview_image'])): ?>
                                    <div class="p-2 border border-secondary border-opacity-15 rounded bg-dark mb-2" style="max-width: 250px;">
                                        <img src="<?php echo BASE_URL . $settings['home_overview_image']; ?>" alt="Overview Image" class="img-fluid rounded">
                                    </div>
                                <?php else: ?>
                                    <div class="text-xs text-muted mb-2"><i class="fa-solid fa-info-circle me-1"></i>Fallback image currently active</div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Floating Badge Quote Text</label>
                                <textarea name="home_overview_quote" rows="3" class="form-control"><?php echo val($settings, 'home_overview_quote'); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Stat 1 Value</label>
                                <input type="text" name="home_overview_stat1_val" class="form-control" value="<?php echo val($settings, 'home_overview_stat1_val', '$4.2B+'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Stat 1 Label</label>
                                <input type="text" name="home_overview_stat1_lbl" class="form-control" value="<?php echo val($settings, 'home_overview_stat1_lbl', 'Total Sales Volume'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Stat 2 Value</label>
                                <input type="text" name="home_overview_stat2_val" class="form-control" value="<?php echo val($settings, 'home_overview_stat2_val', '98.4%'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Stat 2 Label</label>
                                <input type="text" name="home_overview_stat2_lbl" class="form-control" value="<?php echo val($settings, 'home_overview_stat2_lbl', 'Client Retention Rate'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Numerical Statistics Counter Section -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-chart-simple me-2"></i>Statistics Counters</h4>
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label small fw-bold text-muted">Stat 1 Count (Number)</label>
                                <input type="text" name="home_stat1_num" class="form-control" value="<?php echo val($settings, 'home_stat1_num', '4.2'); ?>">
                                <label class="form-label small mt-2 text-secondary">Stat 1 Text Label</label>
                                <input type="text" name="home_stat1_lbl" class="form-control" value="<?php echo val($settings, 'home_stat1_lbl', 'Sales Volume (Billion)'); ?>">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label small fw-bold text-muted">Stat 2 Count (Number)</label>
                                <input type="text" name="home_stat2_num" class="form-control" value="<?php echo val($settings, 'home_stat2_num', '12'); ?>">
                                <label class="form-label small mt-2 text-secondary">Stat 2 Text Label</label>
                                <input type="text" name="home_stat2_lbl" class="form-control" value="<?php echo val($settings, 'home_stat2_lbl', 'Global Cities Active'); ?>">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label small fw-bold text-muted">Stat 3 Count (Number)</label>
                                <input type="text" name="home_stat3_num" class="form-control" value="<?php echo val($settings, 'home_stat3_num', '500'); ?>">
                                <label class="form-label small mt-2 text-secondary">Stat 3 Text Label</label>
                                <input type="text" name="home_stat3_lbl" class="form-control" value="<?php echo val($settings, 'home_stat3_lbl', 'Transactions Closed'); ?>">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label small fw-bold text-muted">Stat 4 Count (Number)</label>
                                <input type="text" name="home_stat4_num" class="form-control" value="<?php echo val($settings, 'home_stat4_num', '35'); ?>">
                                <label class="form-label small mt-2 text-secondary">Stat 4 Text Label</label>
                                <input type="text" name="home_stat4_lbl" class="form-control" value="<?php echo val($settings, 'home_stat4_lbl', 'Industry Awards'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonials Section -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-comments me-2"></i>Testimonials</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Badge Label</label>
                                <input type="text" name="home_testimonials_badge" class="form-control" value="<?php echo val($settings, 'home_testimonials_badge', 'ENDORSEMENTS'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Main Title</label>
                                <input type="text" name="home_testimonials_title" class="form-control" value="<?php echo val($settings, 'home_testimonials_title', 'Testimonials from our Clients'); ?>">
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <!-- Testimonial 1 -->
                            <div class="col-lg-6">
                                <h5 class="fw-bold text-warning mb-3">Testimonial 1</h5>
                                <div class="mb-2">
                                    <label class="form-label small text-muted">Quote Text</label>
                                    <textarea name="home_testimonial1_text" rows="3" class="form-control"><?php echo val($settings, 'home_testimonial1_text'); ?></textarea>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Author Name</label>
                                        <input type="text" name="home_testimonial1_author" class="form-control" value="<?php echo val($settings, 'home_testimonial1_author'); ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Author Role/Title</label>
                                        <input type="text" name="home_testimonial1_role" class="form-control" value="<?php echo val($settings, 'home_testimonial1_role'); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Testimonial 2 -->
                            <div class="col-lg-6">
                                <h5 class="fw-bold text-warning mb-3">Testimonial 2</h5>
                                <div class="mb-2">
                                    <label class="form-label small text-muted">Quote Text</label>
                                    <textarea name="home_testimonial2_text" rows="3" class="form-control"><?php echo val($settings, 'home_testimonial2_text'); ?></textarea>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Author Name</label>
                                        <input type="text" name="home_testimonial2_author" class="form-control" value="<?php echo val($settings, 'home_testimonial2_author'); ?>">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small text-muted">Author Role/Title</label>
                                        <input type="text" name="home_testimonial2_role" class="form-control" value="<?php echo val($settings, 'home_testimonial2_role'); ?>">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <!-- Testimonial Video Media -->
                            <div class="col-12">
                                <h5 class="fw-bold text-warning mb-3">Featured Video Review</h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Video Thumbnail Image (Max 8MB, JPG/PNG/WEBP/HEIC)</label>
                                <input type="file" name="home_testimonial_video_image" class="form-control mb-2" accept="image/*,.heic,.heif">
                                <?php if (!empty($settings['home_testimonial_video_image'])): ?>
                                    <div class="p-2 border border-secondary border-opacity-15 rounded bg-dark" style="max-width: 250px;">
                                        <img src="<?php echo BASE_URL . $settings['home_testimonial_video_image']; ?>" alt="Video Thumbnail" class="img-fluid rounded">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label small fw-bold text-muted">Video Subtitle / Caption</label>
                                <input type="text" name="home_testimonial_video_title" class="form-control" value="<?php echo val($settings, 'home_testimonial_video_title', 'Video Review - Vance Family Office'); ?>">
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <label class="form-label small fw-bold text-muted">YouTube Video ID (embed)</label>
                                <input type="text" name="home_testimonial_video_youtube_id" class="form-control" value="<?php echo val($settings, 'home_testimonial_video_youtube_id', 'dQw4w9WgXcQ'); ?>">
                                <div class="form-text small text-secondary">The code at the end of the URL, e.g. <code>dQw4w9WgXcQ</code></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Section -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-crown me-2"></i>Concierge Services Section</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Services Section Badge</label>
                                <input type="text" name="home_services_badge" class="form-control" value="<?php echo val($settings, 'home_services_badge', 'OUR EXPERTISE'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Services Section Main Title</label>
                                <input type="text" name="home_services_title" class="form-control" value="<?php echo val($settings, 'home_services_title', 'Exclusive concierge Services'); ?>">
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <!-- Service 1 -->
                            <div class="col-lg-4">
                                <h6 class="fw-bold text-warning mb-2">Service item 1</h6>
                                <div class="mb-2">
                                    <label class="form-label small text-muted">FontAwesome Icon Class</label>
                                    <input type="text" name="home_service1_icon" class="form-control" value="<?php echo val($settings, 'home_service1_icon', 'fa-shield-halved'); ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small text-muted">Title</label>
                                    <input type="text" name="home_service1_title" class="form-control" value="<?php echo val($settings, 'home_service1_title', 'Off-Market Acquisition'); ?>">
                                </div>
                                <div>
                                    <label class="form-label small text-muted">Description</label>
                                    <textarea name="home_service1_desc" rows="3" class="form-control"><?php echo val($settings, 'home_service1_desc'); ?></textarea>
                                </div>
                            </div>

                            <!-- Service 2 -->
                            <div class="col-lg-4">
                                <h6 class="fw-bold text-warning mb-2">Service item 2</h6>
                                <div class="mb-2">
                                    <label class="form-label small text-muted">FontAwesome Icon Class</label>
                                    <input type="text" name="home_service2_icon" class="form-control" value="<?php echo val($settings, 'home_service2_icon', 'fa-briefcase'); ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small text-muted">Title</label>
                                    <input type="text" name="home_service2_title" class="form-control" value="<?php echo val($settings, 'home_service2_title', 'Asset Structuring'); ?>">
                                </div>
                                <div>
                                    <label class="form-label small text-muted">Description</label>
                                    <textarea name="home_service2_desc" rows="3" class="form-control"><?php echo val($settings, 'home_service2_desc'); ?></textarea>
                                </div>
                            </div>

                            <!-- Service 3 -->
                            <div class="col-lg-4">
                                <h6 class="fw-bold text-warning mb-2">Service item 3</h6>
                                <div class="mb-2">
                                    <label class="form-label small text-muted">FontAwesome Icon Class</label>
                                    <input type="text" name="home_service3_icon" class="form-control" value="<?php echo val($settings, 'home_service3_icon', 'fa-chart-line'); ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small text-muted">Title</label>
                                    <input type="text" name="home_service3_title" class="form-control" value="<?php echo val($settings, 'home_service3_title', 'Wealth Management'); ?>">
                                </div>
                                <div>
                                    <label class="form-label small text-muted">Description</label>
                                    <textarea name="home_service3_desc" rows="3" class="form-control"><?php echo val($settings, 'home_service3_desc'); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-circle-question me-2"></i>Frequently Asked Questions (FAQ)</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">FAQ Section Badge</label>
                                <input type="text" name="home_faq_badge" class="form-control" value="<?php echo val($settings, 'home_faq_badge', 'KNOWLEDGEBASE'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">FAQ Section Title</label>
                                <input type="text" name="home_faq_title" class="form-control" value="<?php echo val($settings, 'home_faq_title', 'Frequently Asked Questions'); ?>">
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <!-- FAQ 1 -->
                            <div class="col-12 mb-3">
                                <h6 class="fw-bold text-warning">FAQ Item 1</h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Question</label>
                                        <input type="text" name="home_faq1_q" class="form-control" value="<?php echo val($settings, 'home_faq1_q', 'What is an off-market luxury property?'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Answer</label>
                                        <textarea name="home_faq1_a" rows="2" class="form-control"><?php echo val($settings, 'home_faq1_a'); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 2 -->
                            <div class="col-12 mb-3">
                                <h6 class="fw-bold text-warning">FAQ Item 2</h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Question</label>
                                        <input type="text" name="home_faq2_q" class="form-control" value="<?php echo val($settings, 'home_faq2_q', 'How can I verify a property\'s RERA registration?'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Answer</label>
                                        <textarea name="home_faq2_a" rows="2" class="form-control"><?php echo val($settings, 'home_faq2_a'); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ 3 -->
                            <div class="col-12">
                                <h6 class="fw-bold text-warning">FAQ Item 3</h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Question</label>
                                        <input type="text" name="home_faq3_q" class="form-control" value="<?php echo val($settings, 'home_faq3_q', 'Do you provide concierge translation & legal services?'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-muted">Answer</label>
                                        <textarea name="home_faq3_a" rows="2" class="form-control"><?php echo val($settings, 'home_faq3_a'); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Awards Section -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-award me-2"></i>Awards & Accreditation</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Awards Badge</label>
                                <input type="text" name="home_awards_badge" class="form-control" value="<?php echo val($settings, 'home_awards_badge', 'ACCREDITATION'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Awards Title</label>
                                <input type="text" name="home_awards_title" class="form-control" value="<?php echo val($settings, 'home_awards_title', 'Our Awards'); ?>">
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <!-- Award 1 -->
                            <div class="col-md-3 col-sm-6">
                                <h6 class="fw-bold text-warning small mb-2">Award 1</h6>
                                <label class="form-label text-xs text-muted">Icon Class</label>
                                <input type="text" name="home_award1_icon" class="form-control mb-1" value="<?php echo val($settings, 'home_award1_icon', 'fa-medal'); ?>">
                                <label class="form-label text-xs text-muted">Title</label>
                                <input type="text" name="home_award1_title" class="form-control mb-1" value="<?php echo val($settings, 'home_award1_title', 'CSS Design Award'); ?>">
                                <label class="form-label text-xs text-muted">Sub-details</label>
                                <input type="text" name="home_award1_text" class="form-control" value="<?php echo val($settings, 'home_award1_text', 'Best UI/UX Redesign'); ?>">
                            </div>

                            <!-- Award 2 -->
                            <div class="col-md-3 col-sm-6">
                                <h6 class="fw-bold text-warning small mb-2">Award 2</h6>
                                <label class="form-label text-xs text-muted">Icon Class</label>
                                <input type="text" name="home_award2_icon" class="form-control mb-1" value="<?php echo val($settings, 'home_award2_icon', 'fa-trophy'); ?>">
                                <label class="form-label text-xs text-muted">Title</label>
                                <input type="text" name="home_award2_title" class="form-control mb-1" value="<?php echo val($settings, 'home_award2_title', 'Awwwards Honorable'); ?>">
                                <label class="form-label text-xs text-muted">Sub-details</label>
                                <input type="text" name="home_award2_text" class="form-control" value="<?php echo val($settings, 'home_award2_text', 'Luxury Digital Portal'); ?>">
                            </div>

                            <!-- Award 3 -->
                            <div class="col-md-3 col-sm-6">
                                <h6 class="fw-bold text-warning small mb-2">Award 3</h6>
                                <label class="form-label text-xs text-muted">Icon Class</label>
                                <input type="text" name="home_award3_icon" class="form-control mb-1" value="<?php echo val($settings, 'home_award3_icon', 'fa-ribbon'); ?>">
                                <label class="form-label text-xs text-muted">Title</label>
                                <input type="text" name="home_award3_title" class="form-control mb-1" value="<?php echo val($settings, 'home_award3_title', 'Real Estate Forum'); ?>">
                                <label class="form-label text-xs text-muted">Sub-details</label>
                                <input type="text" name="home_award3_text" class="form-control" value="<?php echo val($settings, 'home_award3_text', 'Best Luxury Agency'); ?>">
                            </div>

                            <!-- Award 4 -->
                            <div class="col-md-3 col-sm-6">
                                <h6 class="fw-bold text-warning small mb-2">Award 4</h6>
                                <label class="form-label text-xs text-muted">Icon Class</label>
                                <input type="text" name="home_award4_icon" class="form-control mb-1" value="<?php echo val($settings, 'home_award4_icon', 'fa-crown'); ?>">
                                <label class="form-label text-xs text-muted">Title</label>
                                <input type="text" name="home_award4_title" class="form-control mb-1" value="<?php echo val($settings, 'home_award4_title', 'International Prop'); ?>">
                                <label class="form-label text-xs text-muted">Sub-details</label>
                                <input type="text" name="home_award4_text" class="form-control" value="<?php echo val($settings, 'home_award4_text', 'Outstanding Architecture'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Section -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-envelope me-2"></i>Call to Action (CTA) Section</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">CTA Badge</label>
                                <input type="text" name="home_cta_badge" class="form-control" value="<?php echo val($settings, 'home_cta_badge', 'CONTACT WITH US'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">CTA Title</label>
                                <input type="text" name="home_cta_title" class="form-control" value="<?php echo val($settings, 'home_cta_title', 'Invest in Your Next Dream Space'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">CTA Description Text</label>
                                <textarea name="home_cta_desc" rows="3" class="form-control"><?php echo val($settings, 'home_cta_desc'); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">CTA Background Video MP4 URL</label>
                                <input type="text" name="home_cta_video_url" class="form-control" value="<?php echo val($settings, 'home_cta_video_url', 'https://assets.mixkit.co/videos/preview/mixkit-modern-apartment-building-in-a-city-40718-large.mp4'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ABOUT PAGE TAB -->
        <div class="tab-pane fade" id="about" role="tabpanel">
            <div class="row g-4">
                
                <!-- Inner Hero -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-image me-2"></i>About Us Hero Section</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Hero Background Image (Max 8MB, JPG/PNG/WEBP/HEIC)</label>
                                <input type="file" name="about_hero_image" class="form-control mb-2" accept="image/*,.heic,.heif">
                                <?php if (!empty($settings['about_hero_image'])): ?>
                                    <div class="p-2 border border-secondary border-opacity-15 rounded bg-dark mb-2" style="max-width: 250px;">
                                        <img src="<?php echo BASE_URL . $settings['about_hero_image']; ?>" alt="About Hero" class="img-fluid rounded">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Hero Page Title</label>
                                <input type="text" name="about_hero_title" class="form-control" value="<?php echo val($settings, 'about_hero_title', 'About Vigtez Realty'); ?>">
                                
                                <label class="form-label small fw-bold text-muted mt-3">Hero Subtitle / Tagline</label>
                                <textarea name="about_hero_desc" rows="3" class="form-control"><?php echo val($settings, 'about_hero_desc'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mission & Identity -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-bullseye me-2"></i>Mission & Identity Details</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Section Badge</label>
                                <input type="text" name="about_identity_badge" class="form-control" value="<?php echo val($settings, 'about_identity_badge', 'OUR IDENTITY'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Section Title</label>
                                <input type="text" name="about_identity_title" class="form-control" value="<?php echo val($settings, 'about_identity_title', 'Vigtez Realty Pvt. Ltd Works of Art'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Mission Description 1</label>
                                <textarea name="about_identity_desc1" rows="3" class="form-control"><?php echo val($settings, 'about_identity_desc1'); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Mission Description 2</label>
                                <textarea name="about_identity_desc2" rows="3" class="form-control"><?php echo val($settings, 'about_identity_desc2'); ?></textarea>
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <!-- Feature Card 1 -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning mb-2">Feature Block 1</h6>
                                <label class="form-label text-xs text-muted">FontAwesome Icon</label>
                                <input type="text" name="about_identity_card1_icon" class="form-control mb-1" value="<?php echo val($settings, 'about_identity_card1_icon', 'fa-handshake-angle'); ?>">
                                <label class="form-label text-xs text-muted">Title</label>
                                <input type="text" name="about_identity_card1_title" class="form-control mb-1" value="<?php echo val($settings, 'about_identity_card1_title', 'Discretion'); ?>">
                                <label class="form-label text-xs text-muted">Short Text</label>
                                <textarea name="about_identity_card1_text" rows="2" class="form-control"><?php echo val($settings, 'about_identity_card1_text'); ?></textarea>
                            </div>

                            <!-- Feature Card 2 -->
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning mb-2">Feature Block 2</h6>
                                <label class="form-label text-xs text-muted">FontAwesome Icon</label>
                                <input type="text" name="about_identity_card2_icon" class="form-control mb-1" value="<?php echo val($settings, 'about_identity_card2_icon', 'fa-compass-drafting'); ?>">
                                <label class="form-label text-xs text-muted">Title</label>
                                <input type="text" name="about_identity_card2_title" class="form-control mb-1" value="<?php echo val($settings, 'about_identity_card2_title', 'Design Focus'); ?>">
                                <label class="form-label text-xs text-muted">Short Text</label>
                                <textarea name="about_identity_card2_text" rows="2" class="form-control"><?php echo val($settings, 'about_identity_card2_text'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Executive Team -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-users me-2"></i>Executive Leadership Team</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Leadership Badge</label>
                                <input type="text" name="about_leadership_badge" class="form-control" value="<?php echo val($settings, 'about_leadership_badge', 'THE CONCIERGE'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Leadership Main Title</label>
                                <input type="text" name="about_leadership_title" class="form-control" value="<?php echo val($settings, 'about_leadership_title', 'Executive Leadership'); ?>">
                            </div>

                            <hr class="my-4 border-secondary border-opacity-10">

                            <!-- Member 1 -->
                            <div class="col-lg-4">
                                <h6 class="fw-bold text-warning mb-2">Member 1</h6>
                                <div class="mb-2">
                                    <label class="form-label text-xs text-muted">Name</label>
                                    <input type="text" name="about_team1_name" class="form-control" value="<?php echo val($settings, 'about_team1_name', 'Charles Sterling'); ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-xs text-muted">Role</label>
                                    <input type="text" name="about_team1_role" class="form-control" value="<?php echo val($settings, 'about_team1_role', 'Founder & Chief Advisor'); ?>">
                                </div>
                                <div>
                                    <label class="form-label text-xs text-muted">Profile Image (Max 8MB)</label>
                                    <input type="file" name="about_team1_image" class="form-control mb-1" accept="image/*,.heic,.heif">
                                    <?php if (!empty($settings['about_team1_image'])): ?>
                                        <img src="<?php echo BASE_URL . $settings['about_team1_image']; ?>" class="img-thumbnail rounded mb-2" style="max-height: 80px;">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Member 2 -->
                            <div class="col-lg-4">
                                <h6 class="fw-bold text-warning mb-2">Member 2</h6>
                                <div class="mb-2">
                                    <label class="form-label text-xs text-muted">Name</label>
                                    <input type="text" name="about_team2_name" class="form-control" value="<?php echo val($settings, 'about_team2_name', 'Alexandra Vance'); ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-xs text-muted">Role</label>
                                    <input type="text" name="about_team2_role" class="form-control" value="<?php echo val($settings, 'about_team2_role', 'Managing Partner (Beverly Hills)'); ?>">
                                </div>
                                <div>
                                    <label class="form-label text-xs text-muted">Profile Image (Max 8MB)</label>
                                    <input type="file" name="about_team2_image" class="form-control mb-1" accept="image/*,.heic,.heif">
                                    <?php if (!empty($settings['about_team2_image'])): ?>
                                        <img src="<?php echo BASE_URL . $settings['about_team2_image']; ?>" class="img-thumbnail rounded mb-2" style="max-height: 80px;">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Member 3 -->
                            <div class="col-lg-4">
                                <h6 class="fw-bold text-warning mb-2">Member 3</h6>
                                <div class="mb-2">
                                    <label class="form-label text-xs text-muted">Name</label>
                                    <input type="text" name="about_team3_name" class="form-control" value="<?php echo val($settings, 'about_team3_name', 'Julien Beaumont'); ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label text-xs text-muted">Role</label>
                                    <input type="text" name="about_team3_role" class="form-control" value="<?php echo val($settings, 'about_team3_role', 'Head of Wealth & Asset Advisory'); ?>">
                                </div>
                                <div>
                                    <label class="form-label text-xs text-muted">Profile Image (Max 8MB)</label>
                                    <input type="file" name="about_team3_image" class="form-control mb-1" accept="image/*,.heic,.heif">
                                    <?php if (!empty($settings['about_team3_image'])): ?>
                                        <img src="<?php echo BASE_URL . $settings['about_team3_image']; ?>" class="img-thumbnail rounded mb-2" style="max-height: 80px;">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- CONTACT PAGE TAB -->
        <div class="tab-pane fade" id="contact" role="tabpanel">
            <div class="row g-4">
                
                <!-- Inner Hero -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-image me-2"></i>Contact Page Hero</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Hero Background Image (Max 8MB, JPG/PNG/WEBP/HEIC)</label>
                                <input type="file" name="contact_hero_image" class="form-control mb-2" accept="image/*,.heic,.heif">
                                <?php if (!empty($settings['contact_hero_image'])): ?>
                                    <div class="p-2 border border-secondary border-opacity-15 rounded bg-dark mb-2" style="max-width: 250px;">
                                        <img src="<?php echo BASE_URL . $settings['contact_hero_image']; ?>" alt="Contact Hero" class="img-fluid rounded">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Hero Badge Label</label>
                                <input type="text" name="contact_hero_badge" class="form-control mb-2" value="<?php echo val($settings, 'contact_hero_badge', 'CONCIERGE DESK'); ?>">

                                <label class="form-label small fw-bold text-muted">Hero Main Title</label>
                                <input type="text" name="contact_hero_title" class="form-control mb-2" value="<?php echo val($settings, 'contact_hero_title', 'Connect with Vigtez Reality'); ?>">

                                <label class="form-label small fw-bold text-muted">Hero Description Text</label>
                                <textarea name="contact_hero_desc" rows="2" class="form-control"><?php echo val($settings, 'contact_hero_desc'); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Channels / Direct Lines -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-tty me-2"></i>Direct Channels & Inquiries</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Channels Badge Label</label>
                                <input type="text" name="contact_channels_badge" class="form-control" value="<?php echo val($settings, 'contact_channels_badge', 'OUR CHANNELS'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Channels Title</label>
                                <input type="text" name="contact_channels_title" class="form-control" value="<?php echo val($settings, 'contact_channels_title', 'Acquisition Inquiries'); ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Channels Section Description Paragraph</label>
                                <textarea name="contact_channels_desc" rows="3" class="form-control"><?php echo val($settings, 'contact_channels_desc'); ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Business / Office Hours Text</label>
                                <textarea name="contact_business_hours" rows="3" class="form-control"><?php echo val($settings, 'contact_business_hours'); ?></textarea>
                                <div class="form-text small text-secondary">HTML tags are allowed, e.g. <code>&lt;br&gt;</code> to break lines.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Title -->
                <div class="col-12">
                    <div class="card border-0 glass-card p-4">
                        <h4 class="fw-bold mb-4 text-gradient"><i class="fa-solid fa-table-list me-2"></i>Inquiry Form Headers</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Form Badge Label</label>
                                <input type="text" name="contact_form_badge" class="form-control" value="<?php echo val($settings, 'contact_form_badge', 'SUBMIT ENQUIRY'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Form Main Title</label>
                                <input type="text" name="contact_form_title" class="form-control" value="<?php echo val($settings, 'contact_form_title', 'Schedule a Private Viewing'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Submit bar -->
    <div class="row mt-4 mb-5">
        <div class="col-12">
            <div class="card border-0 glass-card p-3 shadow-sm d-flex flex-row justify-content-end align-items-center bg-dark-deep border-secondary border-opacity-10 rounded-4">
                <button type="submit" class="btn btn-premium py-2 px-5 rounded-3 fw-bold"><i class="fa-solid fa-save me-2"></i>Save Pages Content</button>
            </div>
        </div>
    </div>
</form>
