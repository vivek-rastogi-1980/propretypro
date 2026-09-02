<?php
use App\Helpers\CSRFHelper;

$reraNumber = $property['rera_number'] ?? '';
$constructionStatus = $property['construction_status'] ?? 'Ready To Move';
$availabilityStatus = $property['availability_status'] ?? 'Available';
$googleMapEmbed = $property['google_map_embed'] ?? '';
$threeSixtyTourUrl = $property['three_sixty_tour_url'] ?? '';
$metaTitle = $property['meta_title'] ?? '';
$metaDescription = $property['meta_description'] ?? '';
$metaKeywords = $property['meta_keywords'] ?? '';

$floorPlansList = json_decode($property['floor_plans'], true) ?: [];
$videosList = json_decode($property['videos'], true) ?: [];
?>

<div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom border-secondary border-opacity-10">
    <div>
        <h1 class="display-6 text-white fw-bold font-cinzel mb-1">Edit Property</h1>
        <p class="text-secondary small mb-0">Update listing details, media assets, and status settings</p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>admin/properties" class="btn btn-outline-light px-4 py-2 small fw-bold font-cinzel"><i class="fa-solid fa-arrow-left-long me-2"></i>Back to List</a>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger border-0 small p-3 mb-4 rounded-3 text-white glass-card-dark" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.25) !important;">
        <i class="fa-solid fa-triangle-exclamation me-2 text-danger"></i>Please resolve the errors highlighted below.
    </div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>admin/properties/edit/<?php echo $property['id']; ?>" method="POST" enctype="multipart/form-data">
    <?php echo CSRFHelper::getTokenField(); ?>

    <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15">
        <!-- Tabs Nav -->
        <ul class="nav nav-tabs admin-luxe-tabs mb-4" id="propertyEditTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true"><i class="fa-solid fa-pen-nib me-2"></i>1. General Info</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab" aria-controls="media" aria-selected="false"><i class="fa-solid fa-photo-film me-2"></i>2. Gallery & Media</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="false"><i class="fa-solid fa-circle-info me-2"></i>3. Specs & Amenities</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab" aria-controls="seo" aria-selected="false"><i class="fa-solid fa-globe me-2"></i>4. Maps & SEO</button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="propertyEditTabsContent">
            
            <!-- 1. General Info Tab -->
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Listing Title *</label>
                        <input type="text" name="title" id="property-title-input" class="form-control luxury-input-text" required placeholder="e.g. The Obsidian Penthouse" value="<?php echo htmlspecialchars($property['title']); ?>">
                        <?php if (!empty($errors['title'])): ?>
                            <div class="text-danger small fw-bold mt-1"><?php echo $errors['title']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">SEO Slug (Unique Identifier)</label>
                        <input type="text" name="slug" id="property-slug-input" class="form-control luxury-input-text" required placeholder="e.g. the-obsidian-penthouse" value="<?php echo htmlspecialchars($property['slug']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Location *</label>
                        <input type="text" name="location" class="form-control luxury-input-text" required placeholder="e.g. Beverly Hills, Malibu" value="<?php echo htmlspecialchars($property['location'] ?? ''); ?>">
                        <?php if (!empty($errors['location'])): ?>
                            <div class="text-danger small fw-bold mt-1"><?php echo $errors['location']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Category *</label>
                        <select name="category_id" class="form-select luxury-input" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($property['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Listing Type *</label>
                        <select name="status" class="form-select luxury-input" required>
                            <option value="For Sale" <?php echo ($property['status'] === 'For Sale') ? 'selected' : ''; ?>>For Sale</option>
                            <option value="For Rent" <?php echo ($property['status'] === 'For Rent') ? 'selected' : ''; ?>>For Rent</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Acquisition Price ($) *</label>
                        <input type="number" step="0.01" name="price" class="form-control luxury-input-text" required placeholder="e.g. 2450000" value="<?php echo htmlspecialchars($property['price']); ?>">
                        <?php if (!empty($errors['price'])): ?>
                            <div class="text-danger small fw-bold mt-1"><?php echo $errors['price']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">RERA Registered ID</label>
                        <input type="text" name="rera_number" class="form-control luxury-input-text" placeholder="e.g. RERA-987654" value="<?php echo htmlspecialchars($reraNumber); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Construction Status</label>
                        <select name="construction_status" class="form-select luxury-input">
                            <option value="Ready To Move" <?php echo ($constructionStatus === 'Ready To Move') ? 'selected' : ''; ?>>Ready To Move</option>
                            <option value="Under Construction" <?php echo ($constructionStatus === 'Under Construction') ? 'selected' : ''; ?>>Under Construction</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Availability Status</label>
                        <select name="availability_status" class="form-select luxury-input">
                            <option value="Available" <?php echo ($availabilityStatus === 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Sold" <?php echo ($availabilityStatus === 'Sold') ? 'selected' : ''; ?>>Sold</option>
                            <option value="Upcoming" <?php echo ($availabilityStatus === 'Upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 2. Media & Files Tab -->
            <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                <div class="row g-4">
                    <!-- Current Gallery images -->
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold mb-3">Current Images Gallery (Active: <?php echo count($images); ?>/20)</label>
                        <div class="row g-3">
                            <?php foreach ($images as $img): ?>
                                <div class="col-xl-2 col-md-3 col-sm-4 col-6 admin-gallery-item-wrapper">
                                    <div class="admin-gallery-item rounded-3 overflow-hidden position-relative" style="height: 120px; border: 1px solid rgba(255,255,255,0.15);">
                                        <img src="<?php echo BASE_URL . $img['image_path']; ?>" onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/default_property.png';" class="w-100 h-100 object-fit-cover">
                                        <div class="admin-gallery-actions">
                                            <button type="button" class="btn btn-xs btn-primary admin-set-featured-img" data-property-id="<?php echo $property['id']; ?>" data-image-id="<?php echo $img['id']; ?>" data-action="<?php echo BASE_URL; ?>admin/properties/set-featured-image" title="Set Featured Thumbnail"><i class="fa-solid fa-star"></i></button>
                                            <button type="button" class="btn btn-xs btn-warning admin-set-slider-img" data-property-id="<?php echo $property['id']; ?>" data-image-id="<?php echo $img['id']; ?>" data-action="<?php echo BASE_URL; ?>admin/properties/set-slider-image" title="Set Slider Image"><i class="fa-solid fa-images text-dark"></i></button>
                                            <button type="button" class="btn btn-xs btn-danger admin-delete-gallery-img" data-image-id="<?php echo $img['id']; ?>" data-action="<?php echo BASE_URL; ?>admin/properties/delete-image" title="Remove image"><i class="fa-solid fa-trash-can"></i></button>
                                        </div>
                                        <?php if ($img['is_featured'] == 1): ?>
                                            <span class="admin-gallery-badge bg-primary text-white"><i class="fa-solid fa-star text-warning me-1"></i>Featured</span>
                                        <?php endif; ?>
                                        <?php if ($img['image_path'] === $property['slider_image']): ?>
                                            <span class="admin-gallery-badge bg-warning text-dark" style="top: 10px; left: 10px; right: auto;"><i class="fa-solid fa-images me-1"></i>Slider</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <label class="form-label text-secondary small fw-bold">Upload More Images (Max 20 total limit, 8MB each)</label>
                        <div class="luxury-file-dropzone p-5 rounded-4 text-center border-secondary border-opacity-15 mb-3" style="border: 2px dashed rgba(255,255,255,0.15);">
                            <i class="fa-solid fa-cloud-arrow-up text-warning display-5 mb-3"></i>
                            <h6 class="text-white">Select files to upload and expand the collection</h6>
                            <p class="text-secondary small mb-3">JPG, PNG, WEBP, and HEIC formats supported (Max 8MB each)</p>
                            <input type="file" name="images[]" id="property-images-input" multiple class="form-control" accept="image/*,.heic,.heif">
                        </div>
                        <div id="image-preview-container" class="d-flex flex-wrap gap-2"></div>
                    </div>

                    <!-- PDF Brochure -->
                    <div class="col-md-6 border-end border-secondary border-opacity-10">
                        <label class="form-label text-secondary small fw-bold">PDF Brochure (Max 10MB)</label>
                        <?php if (!empty($property['pdf_brochure'])): ?>
                            <div class="p-3 rounded-3 mb-3 d-flex justify-content-between align-items-center bg-dark bg-opacity-70 border border-secondary border-opacity-10">
                                <a href="<?php echo BASE_URL . $property['pdf_brochure']; ?>" target="_blank" class="text-warning small text-decoration-none fw-bold"><i class="fa-solid fa-file-pdf me-2"></i>View Existing Brochure</a>
                                <div class="form-check">
                                    <input class="form-check-input text-danger" type="checkbox" name="delete_brochure" value="1" id="deleteBrochure">
                                    <label class="form-check-label text-danger small fw-bold cursor-pointer" for="deleteBrochure">Delete PDF</label>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="pdf_brochure" class="form-control luxury-input-text" accept="application/pdf">
                    </div>

                    <!-- Floor Plans -->
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Floor Plans (Images, Max 8MB each)</label>
                        <?php if (!empty($floorPlansList)): ?>
                            <div class="row g-2 mb-3">
                                <?php foreach ($floorPlansList as $fp): ?>
                                    <div class="col-3 position-relative">
                                        <div class="rounded-2 overflow-hidden border border-secondary border-opacity-10" style="height: 60px;">
                                            <img src="<?php echo BASE_URL . $fp; ?>" class="w-100 h-100 object-fit-cover">
                                        </div>
                                        <div class="form-check position-absolute top-0 end-0 m-1 bg-black bg-opacity-50 px-1 rounded">
                                            <input class="form-check-input text-danger m-0" type="checkbox" name="delete_floor_plans[]" value="<?php echo $fp; ?>" title="Delete Floor Plan">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="floor_plans[]" multiple class="form-control luxury-input-text" accept="image/*,.heic,.heif">
                    </div>

                    <!-- Videos repeater -->
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold d-flex justify-content-between align-items-center mb-3">
                            <span>Video Gallery Showcases</span>
                            <button type="button" class="btn btn-sm btn-outline-warning" id="add-video-row-btn"><i class="fa-solid fa-plus me-1"></i>Add Video</button>
                        </label>
                        <div id="video-repeater-container" class="d-flex flex-column gap-3">
                            <?php if (empty($videosList)): ?>
                                <div class="row g-3 align-items-center video-row">
                                    <div class="col-md-3">
                                        <select name="video_types[]" class="form-select luxury-input">
                                            <option value="youtube">YouTube</option>
                                            <option value="vimeo">Vimeo</option>
                                            <option value="local">Local Upload Path</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="video_urls[]" class="form-control luxury-input-text-sm" placeholder="e.g. https://www.youtube.com/watch?v=...">
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-video-row-btn"><i class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($videosList as $vid): ?>
                                    <div class="row g-3 align-items-center video-row">
                                        <div class="col-md-3">
                                            <select name="video_types[]" class="form-select luxury-input">
                                                <option value="youtube" <?php echo ($vid['type'] === 'youtube') ? 'selected' : ''; ?>>YouTube</option>
                                                <option value="vimeo" <?php echo ($vid['type'] === 'vimeo') ? 'selected' : ''; ?>>Vimeo</option>
                                                <option value="local" <?php echo ($vid['type'] === 'local') ? 'selected' : ''; ?>>Local Upload Path</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="video_urls[]" class="form-control luxury-input-text-sm" placeholder="e.g. https://www.youtube.com/watch?v=..." value="<?php echo htmlspecialchars($vid['url']); ?>">
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-video-row-btn"><i class="fa-solid fa-trash-can"></i></button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Specs & Amenities Tab -->
            <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Bedrooms Count</label>
                        <input type="number" name="bedrooms" class="form-control luxury-input-text" placeholder="e.g. 3" value="<?php echo htmlspecialchars($property['bedrooms']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Bathrooms Count</label>
                        <input type="number" name="bathrooms" class="form-control luxury-input-text" placeholder="e.g. 4" value="<?php echo htmlspecialchars($property['bathrooms']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Area Size (SqFt) *</label>
                        <input type="number" step="0.01" name="area" class="form-control luxury-input-text" placeholder="e.g. 4500" required value="<?php echo htmlspecialchars($property['area']); ?>">
                        <?php if (!empty($errors['area'])): ?>
                            <div class="text-danger small fw-bold mt-1"><?php echo $errors['area']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold">Short Snippet Description *</label>
                        <input type="text" name="short_description" class="form-control luxury-input-text" placeholder="Excerpt for cards..." required value="<?php echo htmlspecialchars($property['short_description']); ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold">Full Detailed Description *</label>
                        <textarea name="full_description" rows="8" class="form-control luxury-input-text" required><?php echo htmlspecialchars($property['full_description']); ?></textarea>
                    </div>

                    <!-- Amenities checkboxes -->
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold mb-3">AmenitiesAccoutrements</label>
                        <div class="row g-3">
                            <?php
                            $availableAmenities = [
                                "Swimming Pool", "Gymnasium", "Covered Parking", "24/7 Security",
                                "Elevator", "Air Conditioning", "High Speed Wi-Fi", "Private Garden",
                                "Club House", "Fire Safety", "Wine Vault", "Wellness Spa", "Home Theater"
                            ];
                            $currentAmenities = json_decode($property['amenities'], true) ?: [];
                            foreach ($availableAmenities as $am):
                            ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="form-check amenity-checkbox-wrapper rounded-3 py-2 px-3">
                                        <input class="form-check-input text-warning" type="checkbox" name="amenities[]" value="<?php echo $am; ?>" id="am-check-<?php echo str_replace(' ', '-', strtolower($am)); ?>" <?php echo in_array($am, $currentAmenities) ? 'checked' : ''; ?>>
                                        <label class="form-check-label text-white small fw-bold cursor-pointer" for="am-check-<?php echo str_replace(' ', '-', strtolower($am)); ?>">
                                            <?php echo $am; ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Maps & SEO Tab -->
            <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold">Google Map Embed Code (Iframe tag)</label>
                        <textarea name="google_map_embed" rows="3" class="form-control luxury-input-text" placeholder="<iframe src='...' ...></iframe>"><?php echo htmlspecialchars($googleMapEmbed); ?></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold">360 Virtual Tour URL</label>
                        <input type="text" name="three_sixty_tour_url" class="form-control luxury-input-text" placeholder="e.g. https://my.matterport.com/show/?m=..." value="<?php echo htmlspecialchars($threeSixtyTourUrl); ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold font-cinzel">SEO Title Tag Override</label>
                        <input type="text" name="meta_title" class="form-control luxury-input-text" placeholder="Defaults to Title if blank" value="<?php echo htmlspecialchars($metaTitle); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">SEO Meta Description</label>
                        <textarea name="meta_description" rows="3" class="form-control luxury-input-text" placeholder="Summary for Google indexing..."><?php echo htmlspecialchars($metaDescription); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">SEO Meta Keywords</label>
                        <textarea name="meta_keywords" rows="3" class="form-control luxury-input-text" placeholder="e.g. penthouses, malibu sale"><?php echo htmlspecialchars($metaKeywords); ?></textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer actions -->
        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top border-secondary border-opacity-15">
            <div class="d-flex align-items-center">
                <div class="form-check form-switch me-4">
                    <input class="form-check-input text-warning" type="checkbox" name="is_featured" value="1" id="isFeaturedSwitch" <?php echo ($property['is_featured'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label text-white small fw-bold" for="isFeaturedSwitch"><i class="fa-solid fa-award text-warning me-2"></i>Feature Listing</label>
                </div>
                <div class="form-check form-switch me-4">
                    <input class="form-check-input text-warning" type="checkbox" name="is_published" value="1" id="isPublishedSwitch" <?php echo ($property['is_published'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label text-white small fw-bold" for="isPublishedSwitch"><i class="fa-solid fa-circle-check text-success me-2"></i>Published</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input text-warning" type="checkbox" name="in_slider" value="1" id="inSliderSwitch" <?php echo ($property['in_slider'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label text-white small fw-bold" for="inSliderSwitch"><i class="fa-solid fa-images text-warning me-2"></i>Show in Hero Slideshow</label>
                </div>
            </div>
            <button type="submit" class="btn btn-gold-solid px-5 py-3 font-cinzel uppercase tracking-widest fw-bold">
                Update Property
            </button>
        </div>

    </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Dynamic video row insertion
    const container = document.getElementById('video-repeater-container');
    const addBtn = document.getElementById('add-video-row-btn');

    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'row g-3 align-items-center video-row mt-1';
        row.innerHTML = `
            <div class="col-md-3">
                <select name="video_types[]" class="form-select luxury-input">
                    <option value="youtube">YouTube</option>
                    <option value="vimeo">Vimeo</option>
                    <option value="local">Local Upload Path</option>
                </select>
            </div>
            <div class="col-md-8">
                <input type="text" name="video_urls[]" class="form-control luxury-input-text-sm" placeholder="e.g. https://www.youtube.com/watch?v=...">
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-video-row-btn"><i class="fa-solid fa-trash-can"></i></button>
            </div>
        `;
        container.appendChild(row);
        bindRemoveButtons();
    });

    function bindRemoveButtons() {
        const removeBtns = document.querySelectorAll('.remove-video-row-btn');
        removeBtns.forEach(btn => {
            btn.onclick = function () {
                const row = btn.closest('.video-row');
                row.remove();
            };
        });
    }

    bindRemoveButtons();
});
</script>

