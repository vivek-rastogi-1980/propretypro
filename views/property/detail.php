<?php
use App\Helpers\CSRFHelper;

$price = (float)$property['price'];
$area = (float)$property['area'];
$bedrooms = (int)$property['bedrooms'];
$bathrooms = (int)$property['bathrooms'];
$whatsapp = htmlspecialchars($globalSettings['whatsapp_number'] ?? '');
$phone = htmlspecialchars($globalSettings['company_phone'] ?? '');

$floorPlansList = json_decode($property['floor_plans'], true) ?: [];
$videosList = json_decode($property['videos'], true) ?: [];
?>

<!-- Cinematic Image Gallery Slider (Hero) -->
<section class="property-hero-gallery position-relative bg-black overflow-hidden">
    <div class="swiper detail-gallery-swiper">
        <div class="swiper-wrapper">
            <?php if (empty($images)): ?>
                <div class="swiper-slide">
                    <div class="detail-gallery-slide-wrap" data-full-img="<?php echo BASE_URL; ?>assets/images/default_property.png">
                        <div class="gallery-slide-backdrop" style="background-image: url('<?php echo BASE_URL; ?>assets/images/default_property.png');"></div>
                        <div class="detail-gallery-slide-img" style="background-image: url('<?php echo BASE_URL; ?>assets/images/default_property.png');" data-full-img="<?php echo BASE_URL; ?>assets/images/default_property.png" role="img" aria-label="<?php echo htmlspecialchars($property['title']); ?>">
                            <div class="gallery-slide-overlay"></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($images as $idx => $img): ?>
                    <?php $fullImgPath = BASE_URL . $img['image_path']; ?>
                    <div class="swiper-slide">
                        <div class="detail-gallery-slide-wrap" data-full-img="<?php echo $fullImgPath; ?>">
                            <div class="gallery-slide-backdrop" style="background-image: url('<?php echo $fullImgPath; ?>');"></div>
                            <div class="detail-gallery-slide-img" style="background-image: url('<?php echo $fullImgPath; ?>');" data-full-img="<?php echo $fullImgPath; ?>" role="img" aria-label="<?php echo htmlspecialchars($property['title']); ?> - Photo <?php echo $idx + 1; ?>">
                                <div class="gallery-slide-overlay"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Custom Luxury Navigation Controls -->
        <button type="button" class="property-gallery-nav property-gallery-prev" aria-label="Previous Slide">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button type="button" class="property-gallery-nav property-gallery-next" aria-label="Next Slide">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

        <!-- Floating Utilities: Counter & Fullscreen -->
        <div class="gallery-floating-top d-flex align-items-center justify-content-between">
            <div class="gallery-photo-badge">
                <i class="fa-regular fa-images me-2 text-warning"></i>
                <span class="gallery-current-idx">1</span> / <span class="gallery-total-idx"><?php echo max(count($images), 1); ?></span> Photos
            </div>
            <button type="button" class="gallery-fullscreen-btn" id="btn-open-gallery-modal" aria-label="View Fullscreen Photo" title="View Fullscreen">
                <i class="fa-solid fa-expand me-1"></i> Fullscreen
            </button>
        </div>

        <!-- Pagination Dots -->
        <div class="swiper-pagination detail-gallery-pagination"></div>
    </div>

    <!-- Thumbnail Navigation Strip -->
    <?php if (!empty($images) && count($images) > 1): ?>
        <div class="gallery-thumbs-wrapper py-3 border-top border-secondary border-opacity-10">
            <div class="container">
                <div class="gallery-thumbs-scroll d-flex align-items-center gap-3 justify-content-center flex-wrap">
                    <?php foreach ($images as $idx => $img): ?>
                        <div class="gallery-thumb-item <?php echo $idx === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo $idx; ?>">
                            <img src="<?php echo !empty($img['thumb_url']) ? $img['thumb_url'] : BASE_URL . $img['image_path']; ?>" alt="Thumbnail <?php echo $idx + 1; ?>" class="w-100 h-100 object-fit-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- Property Detail Header Section (Positioned Cleanly Below Slider) -->
<section class="property-detail-header-section py-4 py-md-5 bg-dark-deep border-bottom border-secondary border-opacity-10">
    <div class="container">
        <div class="property-header-card glass-card-dark p-4 p-md-5 rounded-4 border-secondary border-opacity-15 shadow-2xl">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="property-badge-category font-cinzel uppercase"><?php echo htmlspecialchars($property['category_name']); ?></span>
                        <span class="property-badge-available font-cinzel uppercase"><?php echo htmlspecialchars($property['availability_status']); ?></span>
                        <?php if (!empty($property['rera_number'])): ?>
                            <span class="property-badge-rera font-cinzel uppercase"><i class="fa-solid fa-shield-halved me-2"></i>RERA Verified</span>
                        <?php endif; ?>
                    </div>
                    <h1 class="display-6 font-cinzel text-white fw-bold mb-2 property-main-title"><?php echo htmlspecialchars($property['title']); ?></h1>
                    <p class="text-secondary mb-0 fs-6">
                        <i class="fa-solid fa-location-dot me-2 text-warning"></i><span class="text-light-muted"><?php echo htmlspecialchars($property['location']); ?></span>
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end border-lg-start border-secondary border-opacity-15 ps-lg-4">
                    <span class="text-secondary small uppercase tracking-wider d-block mb-1">Acquisition Price</span>
                    <h2 class="text-warning font-cinzel fw-bold mb-0 display-6 property-price-val">₹<?php echo number_format($price); ?></h2>
                    <?php if ($area > 0): ?>
                        <span class="text-secondary small mt-1 d-block">
                            Approx. ₹<?php echo number_format(round($price / $area)); ?> per <?php echo htmlspecialchars($property['area_unit'] ?? 'Sq. Ft.'); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Specifications and details -->
<section class="py-5 bg-black text-white">
    <div class="container py-5">
        <div class="row g-5">
            
            <!-- Main Info Block -->
            <div class="col-12">
                
                <!-- Core Spec Icons -->
                <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 mb-5">
                    <div class="row text-center g-3">
                        <?php if ($bedrooms): ?>
                            <div class="col-4 border-end border-secondary border-opacity-15">
                                <i class="fa-solid fa-bed text-warning fs-3 mb-2"></i>
                                <h6 class="text-secondary small mb-1">Bedrooms</h6>
                                <h5 class="font-cinzel text-white fw-bold mb-0"><?php echo $bedrooms; ?></h5>
                            </div>
                        <?php endif; ?>
                        <div class="col-4 border-end border-secondary border-opacity-15">
                            <i class="fa-solid fa-bath text-warning fs-3 mb-2"></i>
                            <h6 class="text-secondary small mb-1">Bathrooms</h6>
                            <h5 class="font-cinzel text-white fw-bold mb-0"><?php echo $bathrooms; ?></h5>
                        </div>
                        <div class="col-4">
                            <i class="fa-solid fa-maximize text-warning fs-3 mb-2"></i>
                            <h6 class="text-secondary small mb-1">Total Area</h6>
                            <h5 class="font-cinzel text-white fw-bold mb-0"><?php echo number_format($area); ?> <?php echo htmlspecialchars($property['area_unit'] ?? 'Sq. Ft.'); ?></h5>
                        </div>
                    </div>
                </div>

                <!-- Description and Premium Amenities (Side-by-Side) -->
                <div class="row g-4 mb-5 align-items-stretch">
                    <!-- Property Description (Left Side) -->
                    <div class="<?php echo !empty($amenities) ? 'col-lg-7' : 'col-12'; ?>">
                        <div class="glass-card-dark p-4 p-md-5 rounded-4 border-secondary border-opacity-15 h-100 property-description-card">
                            <h4 class="font-cinzel text-white fw-bold mb-4">
                                <i class="fa-solid fa-align-left text-warning me-3"></i>Property Description
                            </h4>
                            <div class="text-light-muted lh-lg fs-6 property-description-text">
                                <?php echo nl2br($property['full_description']); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Premium Amenities (Right Side of Description) - Only appears when at least 1 amenity selected -->
                    <?php if (!empty($amenities)): ?>
                        <div class="col-lg-5">
                            <div class="glass-card-dark p-4 p-md-5 rounded-4 border-secondary border-opacity-15 h-100 property-amenities-card">
                                <h4 class="font-cinzel text-white fw-bold mb-4">
                                    <i class="fa-solid fa-hotel text-warning me-3"></i>Premium Amenities
                                </h4>
                                <div class="row g-3">
                                    <?php foreach ($amenities as $am): ?>
                                        <div class="col-sm-6 col-12">
                                            <div class="amenity-pill-item d-flex align-items-center h-100">
                                                <i class="fa-solid fa-circle-check text-success me-3 flex-shrink-0"></i>
                                                <span class="amenity-title small fw-semibold"><?php echo htmlspecialchars($am); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Floor Plans -->
                <?php if (!empty($floorPlansList)): ?>
                    <div class="mb-5">
                        <h4 class="font-cinzel text-white fw-bold mb-4"><i class="fa-solid fa-compass-drafting text-warning me-3"></i>Floor Plans</h4>
                        <div class="row g-3">
                            <?php foreach ($floorPlansList as $index => $fp): ?>
                                <div class="col-sm-6">
                                    <div class="floor-plan-luxury-card rounded-4 overflow-hidden position-relative glass-card-dark border-secondary border-opacity-10" style="height: 200px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#floorPlanModal<?php echo $index; ?>">
                                        <img src="<?php echo BASE_URL . $fp; ?>" alt="Floor Plan <?php echo $index+1; ?>" class="w-100 h-100 object-fit-cover opacity-75">
                                        <div class="image-gradient-shade flex-center"></div>
                                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                                            <i class="fa-solid fa-magnifying-glass-plus text-warning display-6 mb-2"></i>
                                            <h6 class="text-white font-cinzel uppercase tracking-widest small">Floor Plan <?php echo $index + 1; ?></h6>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Floor Plan Modal -->
                                <div class="modal fade" id="floorPlanModal<?php echo $index; ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-black border border-secondary border-opacity-25 shadow-2xl rounded-4 overflow-hidden">
                                            <div class="modal-header border-bottom border-secondary border-opacity-15 py-3 px-4 d-flex justify-content-between align-items-center bg-dark bg-opacity-75">
                                                <h5 class="modal-title font-cinzel text-white small fw-bold mb-0">Floor Plan <?php echo $index+1; ?></h5>
                                                <button type="button" class="modal-video-close-btn" data-bs-dismiss="modal" aria-label="Close">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body text-center p-0 bg-black">
                                                <img src="<?php echo BASE_URL . $fp; ?>" alt="Floor Plan <?php echo $index+1; ?>" class="img-fluid rounded-bottom">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Video Gallery -->
                <?php if (!empty($videosList)): ?>
                    <div class="mb-5">
                        <h4 class="font-cinzel text-white fw-bold mb-4"><i class="fa-solid fa-film text-warning me-3"></i>Video Showcases</h4>
                        <div class="row g-3">
                            <?php foreach ($videosList as $index => $vid): ?>
                                <div class="col-md-6">
                                    <div class="video-luxury-card rounded-4 overflow-hidden position-relative glass-card-dark border-secondary border-opacity-10" style="height: 180px;">
                                        <div class="position-absolute w-100 h-100 flex-center bg-black bg-opacity-40">
                                            <button class="play-btn-pulse" data-bs-toggle="modal" data-bs-target="#videoModal<?php echo $index; ?>">
                                                <i class="fa-solid fa-play text-warning"></i>
                                            </button>
                                            <span class="position-absolute bottom-0 w-100 text-center text-white py-2 bg-dark bg-opacity-70 small font-cinzel tracking-wider uppercase">Video Preview <?php echo $index+1; ?> (<?php echo htmlspecialchars($vid['type']); ?>)</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Video Player Modal -->
                                <div class="modal fade" id="videoModal<?php echo $index; ?>" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content bg-black border border-secondary border-opacity-25 shadow-2xl rounded-4 overflow-hidden">
                                            <div class="modal-header border-bottom border-secondary border-opacity-15 py-3 px-4 d-flex justify-content-between align-items-center bg-dark bg-opacity-75">
                                                <h5 class="modal-title font-cinzel text-white small fw-bold mb-0"><i class="fa-solid fa-film text-warning me-2"></i>Video Showcase <?php echo $index + 1; ?> (<?php echo htmlspecialchars(ucfirst($vid['type'])); ?>)</h5>
                                                <button type="button" class="modal-video-close-btn" data-bs-dismiss="modal" aria-label="Close">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body p-0 bg-black">
                                                <div class="ratio ratio-16x9">
                                                    <?php if ($vid['type'] === 'youtube'): ?>
                                                        <!-- Embed youtube URL safely -->
                                                        <?php 
                                                        $urlParts = parse_url($vid['url']);
                                                        $videoId = '';
                                                        if (isset($urlParts['query'])) {
                                                            parse_str($urlParts['query'], $queryParts);
                                                            $videoId = $queryParts['v'] ?? '';
                                                        } else {
                                                            $videoId = basename($urlParts['path']);
                                                        }
                                                        ?>
                                                        <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($videoId); ?>?enablejsapi=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    <?php elseif ($vid['type'] === 'vimeo'): ?>
                                                        <?php 
                                                        $videoId = basename(parse_url($vid['url'], PHP_URL_PATH));
                                                        ?>
                                                        <iframe src="https://player.vimeo.com/video/<?php echo htmlspecialchars($videoId); ?>" allowfullscreen></iframe>
                                                    <?php else: ?>
                                                        <video src="<?php echo BASE_URL . $vid['url']; ?>" controls class="w-100 h-100"></video>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 360 Tour Frame -->
                <?php if (!empty($property['three_sixty_tour_url'])): ?>
                    <div class="mb-5">
                        <h4 class="font-cinzel text-white fw-bold mb-4"><i class="fa-solid fa-street-view text-warning me-3"></i>360 Virtual Tour</h4>
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden border border-secondary border-opacity-15 shadow-xl">
                            <iframe src="<?php echo htmlspecialchars($property['three_sixty_tour_url']); ?>" allowfullscreen></iframe>
                        </div>
                    </div>
                <?php endif; ?>

                    <!-- Contact Form -->
                    <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 shadow-lg">
                        <h5 class="font-cinzel text-white fw-bold mb-4">Inquire About Property</h5>
                        <form action="<?php echo BASE_URL; ?>property/<?php echo $property['slug']; ?>/enquiry" method="POST" class="ajax-enquiry-form">
                            <?php echo CSRFHelper::getTokenField(); ?>
                            
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Your Name *</label>
                                <input type="text" name="name" class="form-control luxury-input-text-sm" required placeholder="e.g. Vikramaditya Rathore">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Email Address *</label>
                                <input type="email" name="email" class="form-control luxury-input-text-sm" required placeholder="name@domain.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Phone / WhatsApp Number *</label>
                                <input type="tel" name="phone" class="form-control luxury-input-text-sm" placeholder="e.g. +91 98765 43210">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Message *</label>
                                <textarea name="message" rows="4" class="form-control luxury-input-text-sm" required placeholder="I am interested in scheduling a private site visit for <?php echo htmlspecialchars($property['title']); ?>..."></textarea>
                            </div>
                            <div class="form-response-message my-3" style="display: none;"></div>
                            <button type="submit" class="btn btn-gold-solid w-100 py-3 font-cinzel tracking-wider uppercase small fw-bold">
                                Submit Property Inquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Related Properties -->
<?php if (!empty($relatedProperties)): ?>
    <section class="py-5 bg-dark-deep text-white border-top border-secondary border-opacity-10">
        <div class="container py-5">
            <h3 class="font-cinzel text-white fw-bold mb-5 text-center text-md-start">Related <span class="text-gold-gradient">Properties</span></h3>
            <div class="row g-4">
                <?php foreach ($relatedProperties as $rel): ?>
                    <div class="col-lg-4 col-md-6 scroll-reveal-fade">
                        <div class="property-luxury-card rounded-4 overflow-hidden glass-card-dark position-relative h-100">
                            <span class="property-status-tag"><?php echo htmlspecialchars($rel['status']); ?></span>
                            <div class="property-image-holder" style="height: 200px;">
                                <img src="<?php echo BASE_URL . ($rel['image_path'] ?? 'assets/images/default_property.png'); ?>" onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/default_property.png';" alt="<?php echo htmlspecialchars($rel['title']); ?>" class="w-100 h-100 object-fit-cover">
                                <div class="image-gradient-shade"></div>
                            </div>
                            <div class="property-content-holder p-4">
                                <span class="text-gold-accent uppercase small tracking-widest fw-semibold d-block mb-2"><?php echo htmlspecialchars($rel['category_name']); ?></span>
                                <h4 class="font-cinzel text-white fw-bold mb-3 fs-5"><?php echo htmlspecialchars($rel['title']); ?></h4>
                                <div class="d-flex justify-content-between align-items-center border-top border-secondary border-opacity-15 pt-3 mt-3">
                                    <h6 class="text-warning font-cinzel mb-0 fw-bold">₹<?php echo number_format($rel['price']); ?></h6>
                                    <span class="text-secondary small" style="font-size: 11px;"><i class="fa-solid fa-maximize text-gold-accent me-1"></i><?php echo number_format($rel['area']); ?> <?php echo htmlspecialchars($rel['area_unit'] ?? 'Sq. Ft.'); ?></span>
                                </div>
                                <a href="<?php echo BASE_URL; ?>property/<?php echo $rel['slug']; ?>" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Fullscreen Photo Modal -->
<div class="modal fade" id="galleryPhotoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-black border border-secondary border-opacity-25 rounded-4 overflow-hidden shadow-2xl">
            <div class="modal-header border-bottom border-secondary border-opacity-15 py-3 px-4 d-flex justify-content-between align-items-center bg-dark bg-opacity-75">
                <h6 class="modal-title font-cinzel text-white mb-0" id="galleryModalTitle"><i class="fa-regular fa-image text-warning me-2"></i><?php echo htmlspecialchars($property['title']); ?></h6>
                <button type="button" class="modal-video-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body p-2 text-center bg-black d-flex align-items-center justify-content-center" style="min-height: 480px;">
                <img src="" id="galleryModalImage" alt="<?php echo htmlspecialchars($property['title']); ?>" class="img-fluid rounded-3" style="max-height: 82vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

