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
<section class="property-hero-gallery position-relative bg-black">
    <div class="swiper detail-gallery-swiper">
        <div class="swiper-wrapper">
            <?php if (empty($images)): ?>
                <div class="swiper-slide">
                    <div class="detail-gallery-slide-img" style="background-image: url('<?php echo BASE_URL; ?>assets/images/default_property.png');"></div>
                </div>
            <?php else: ?>
                <?php foreach ($images as $img): ?>
                    <div class="swiper-slide">
                        <div class="detail-gallery-slide-img" style="background-image: url('<?php echo BASE_URL . $img['image_path']; ?>');"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="swiper-button-next swiper-nav-luxe"></div>
        <div class="swiper-button-prev swiper-nav-luxe"></div>
        <div class="swiper-pagination detail-gallery-pagination"></div>
    </div>
    
    <!-- Floating Quick Info panel -->
    <div class="container position-relative z-2">
        <div class="property-floating-summary glass-card-dark p-4 rounded-4 text-white position-absolute z-3" style="bottom: 30px; left: 15px; right: 15px;">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <span class="badge bg-gold-accent text-dark font-cinzel uppercase py-2 px-3 mb-2 me-2"><?php echo htmlspecialchars($property['category_name']); ?></span>
                    <span class="badge bg-dark bg-opacity-70 text-warning border border-warning border-opacity-30 font-cinzel py-2 px-3 mb-2"><?php echo htmlspecialchars($property['availability_status']); ?></span>
                    <h2 class="font-cinzel fw-bold mb-1 fs-3"><?php echo htmlspecialchars($property['title']); ?></h2>
                    <p class="text-secondary small mb-0"><i class="fa-solid fa-location-dot me-2 text-warning"></i><?php echo htmlspecialchars($property['location']); ?></p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="text-secondary small d-block mb-1">Acquisition Price</span>
                    <h3 class="text-warning font-cinzel fw-bold mb-0">$<?php echo number_format($price); ?></h3>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Specifications and details -->
<section class="py-5 bg-black text-white">
    <div class="container py-5">
        <div class="row g-5">
            
            <!-- Left Info Block -->
            <div class="col-lg-8">
                
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
                            <h5 class="font-cinzel text-white fw-bold mb-0"><?php echo number_format($area); ?> SqFt</h5>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-5">
                    <h4 class="font-cinzel text-white fw-bold mb-4"><i class="fa-solid fa-align-left text-warning me-3"></i>Property Description</h4>
                    <div class="text-light-muted lh-lg fs-6">
                        <?php echo nl2br($property['full_description']); ?>
                    </div>
                </div>

                <!-- Amenities List -->
                <div class="mb-5">
                    <h4 class="font-cinzel text-white fw-bold mb-4"><i class="fa-solid fa-hotel text-warning me-3"></i>Premium Amenities</h4>
                    <div class="row g-3 mt-2">
                        <?php if (!empty($amenities)): ?>
                            <?php foreach ($amenities as $am): ?>
                                <div class="col-sm-4 col-6">
                                    <div class="p-3 rounded-3 glass-card-dark border-secondary border-opacity-10 d-flex align-items-center">
                                        <i class="fa-solid fa-circle-check text-success me-3"></i>
                                        <span class="text-white small fw-semibold"><?php echo htmlspecialchars($am); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-secondary small">Contact agent for full specifications list.</p>
                        <?php endif; ?>
                    </div>
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

                <!-- Google Maps & Nearby Places -->
                <div class="mb-5">
                    <h4 class="font-cinzel text-white fw-bold mb-4"><i class="fa-solid fa-map-location-dot text-warning me-3"></i>Geographic Location</h4>
                    <?php if (!empty($property['google_map_embed'])): ?>
                        <div class="ratio ratio-21x9 rounded-4 overflow-hidden border border-secondary border-opacity-15 shadow-xl mb-4">
                            <?php echo $property['google_map_embed']; ?>
                        </div>
                    <?php endif; ?>
                    
                    <h5 class="font-cinzel text-gold-accent mb-3 mt-4 small uppercase tracking-widest"><i class="fa-solid fa-location-arrow me-2 text-warning"></i>Nearby Establishments</h5>
                    <div class="row g-3">
                        <div class="col-sm-4 col-6">
                            <div class="p-3 rounded-3 glass-card-dark border-secondary border-opacity-10">
                                <h6 class="text-white mb-1"><i class="fa-solid fa-graduation-cap text-warning me-2"></i>Private School</h6>
                                <p class="text-secondary small mb-0">1.2 Miles away</p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="p-3 rounded-3 glass-card-dark border-secondary border-opacity-10">
                                <h6 class="text-white mb-1"><i class="fa-solid fa-umbrella-beach text-warning me-2"></i>Private Cove</h6>
                                <p class="text-secondary small mb-0">0.4 Miles away</p>
                            </div>
                        </div>
                        <div class="col-sm-4 col-6">
                            <div class="p-3 rounded-3 glass-card-dark border-secondary border-opacity-10">
                                <h6 class="text-white mb-1"><i class="fa-solid fa-plane-up text-warning me-2"></i>Regional Heliport</h6>
                                <p class="text-secondary small mb-0">3.5 Miles away</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Sidebar Action Cards -->
            <div class="col-lg-4">
                <div class="sticky-lg-top" style="top: 100px; z-index: 9;">
                    
                    <!-- Call details & brochure downloads -->
                    <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 mb-4 text-center">
                        <h4 class="font-cinzel text-white fw-bold mb-4">Broker Desk</h4>
                        
                        <!-- RERA verified stamp -->
                        <?php if (!empty($property['rera_number'])): ?>
                            <div class="p-3 rounded-3 mb-4 text-center bg-dark bg-opacity-70 border border-success border-opacity-25">
                                <i class="fa-solid fa-shield-halved text-success display-6 mb-2"></i>
                                <h6 class="text-white mb-1 font-cinzel">RERA Verified</h6>
                                <p class="text-secondary small mb-0">Registration ID: <strong><?php echo htmlspecialchars($property['rera_number']); ?></strong></p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Dynamic Buttons -->
                        <div class="d-flex flex-column gap-2 mb-4">
                            <?php if ($whatsapp): ?>
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $whatsapp); ?>?text=I am interested in <?php echo urlencode($property['title']); ?>" target="_blank" class="btn btn-success-luxury py-3 w-100 d-flex align-items-center justify-content-center">
                                    <i class="fa-brands fa-whatsapp me-3"></i>WhatsApp Broker
                                </a>
                            <?php endif; ?>
                            <?php if ($phone): ?>
                                <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phone); ?>" class="btn btn-outline-light py-3 w-100 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-phone me-3"></i>Call Concierge
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($property['pdf_brochure'])): ?>
                                <a href="<?php echo BASE_URL . $property['pdf_brochure']; ?>" target="_blank" class="btn btn-gold-solid py-3 w-100 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-file-pdf me-3"></i>Download Brochure
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15 shadow-lg">
                        <h5 class="font-cinzel text-white fw-bold mb-4">Bespoke Inquiry</h5>
                        <form action="<?php echo BASE_URL; ?>property/<?php echo $property['slug']; ?>/enquiry" method="POST" class="ajax-enquiry-form">
                            <?php echo CSRFHelper::getTokenField(); ?>
                            
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Name</label>
                                <input type="text" name="name" class="form-control luxury-input-text-sm" required placeholder="Marcus Vance">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Email</label>
                                <input type="email" name="email" class="form-control luxury-input-text-sm" required placeholder="name@domain.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Phone</label>
                                <input type="tel" name="phone" class="form-control luxury-input-text-sm" placeholder="+1 (555) 000-0000">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-bold">Bespoke Message</label>
                                <textarea name="message" rows="4" class="form-control luxury-input-text-sm" required placeholder="I am interested in scheduling a viewing for <?php echo htmlspecialchars($property['title']); ?>..."></textarea>
                            </div>
                            <div class="form-response-message my-3" style="display: none;"></div>
                            <button type="submit" class="btn btn-gold-solid w-100 py-3 font-cinzel tracking-wider uppercase small fw-bold">
                                Request Private Tour
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Related Properties -->
<?php if (!empty($relatedProperties)): ?>
    <section class="py-5 bg-dark-deep text-white border-top border-secondary border-opacity-10">
        <div class="container py-5">
            <h3 class="font-cinzel text-white fw-bold mb-5 text-center text-md-start">Related <span class="text-gold-gradient">Masterpieces</span></h3>
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
                                    <h6 class="text-warning font-cinzel mb-0 fw-bold">$<?php echo number_format($rel['price']); ?></h6>
                                    <span class="text-secondary small" style="font-size: 11px;"><i class="fa-solid fa-maximize text-gold-accent me-1"></i><?php echo number_format($rel['area']); ?> SqFt</span>
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

