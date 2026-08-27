<?php
use App\Helpers\CSRFHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom border-secondary border-opacity-10">
    <div>
        <h1 class="display-6 text-white fw-bold font-cinzel mb-1">Add Property</h1>
        <p class="text-secondary small mb-0">Create a luxury architectural masterpiece listing</p>
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

<form action="<?php echo BASE_URL; ?>admin/properties/create" method="POST" enctype="multipart/form-data">
    <?php echo CSRFHelper::getTokenField(); ?>

    <div class="glass-card-dark p-4 rounded-4 border-secondary border-opacity-15">
        <!-- Tabs Nav -->
        <ul class="nav nav-tabs admin-luxe-tabs mb-4" id="propertyCreateTabs" role="tablist">
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
        <div class="tab-content" id="propertyCreateTabsContent">
            
            <!-- 1. General Info Tab -->
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Listing Title *</label>
                        <input type="text" name="title" id="property-title-input" class="form-control luxury-input-text" required placeholder="e.g. The Obsidian Penthouse" value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>">
                        <?php if (!empty($errors['title'])): ?>
                            <div class="text-danger small fw-bold mt-1"><?php echo $errors['title']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">SEO Slug (Auto-generated if empty)</label>
                        <input type="text" name="slug" id="property-slug-input" class="form-control luxury-input-text" placeholder="e.g. the-obsidian-penthouse" value="<?php echo htmlspecialchars($old['slug'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Location *</label>
                        <input type="text" name="location" class="form-control luxury-input-text" required placeholder="e.g. Beverly Hills, Malibu" value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>">
                        <?php if (!empty($errors['location'])): ?>
                            <div class="text-danger small fw-bold mt-1"><?php echo $errors['location']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Category *</label>
                        <select name="category_id" class="form-select luxury-input" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($old['category_id']) && $old['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Listing Type *</label>
                        <select name="status" class="form-select luxury-input" required>
                            <option value="For Sale" <?php echo (isset($old['status']) && $old['status'] === 'For Sale') ? 'selected' : ''; ?>>For Sale</option>
                            <option value="For Rent" <?php echo (isset($old['status']) && $old['status'] === 'For Rent') ? 'selected' : ''; ?>>For Rent</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Acquisition Price ($) *</label>
                        <input type="number" step="0.01" name="price" class="form-control luxury-input-text" required placeholder="e.g. 2450000" value="<?php echo htmlspecialchars($old['price'] ?? ''); ?>">
                        <?php if (!empty($errors['price'])): ?>
                            <div class="text-danger small fw-bold mt-1"><?php echo $errors['price']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">RERA Registered ID</label>
                        <input type="text" name="rera_number" class="form-control luxury-input-text" placeholder="e.g. RERA-987654" value="<?php echo htmlspecialchars($old['rera_number'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Construction Status</label>
                        <select name="construction_status" class="form-select luxury-input">
                            <option value="Ready To Move" <?php echo (isset($old['construction_status']) && $old['construction_status'] === 'Ready To Move') ? 'selected' : ''; ?>>Ready To Move</option>
                            <option value="Under Construction" <?php echo (isset($old['construction_status']) && $old['construction_status'] === 'Under Construction') ? 'selected' : ''; ?>>Under Construction</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Availability Status</label>
                        <select name="availability_status" class="form-select luxury-input">
                            <option value="Available" <?php echo (isset($old['availability_status']) && $old['availability_status'] === 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Sold" <?php echo (isset($old['availability_status']) && $old['availability_status'] === 'Sold') ? 'selected' : ''; ?>>Sold</option>
                            <option value="Upcoming" <?php echo (isset($old['availability_status']) && $old['availability_status'] === 'Upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 2. Media & Files Tab -->
            <div class="tab-pane fade" id="media" role="tabpanel" aria-labelledby="media-tab">
                <div class="row g-4">
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold">Upload Gallery Images (Up to 20 files, Max 5MB each)</label>
                        <div class="luxury-file-dropzone p-5 rounded-4 text-center border-secondary border-opacity-15 mb-3" style="border: 2px dashed rgba(255,255,255,0.15);">
                            <i class="fa-solid fa-images text-warning display-5 mb-3"></i>
                            <h6 class="text-white">Drag & drop images here or click to select</h6>
                            <p class="text-secondary small mb-3">Only JPG, PNG, and WEBP formats supported</p>
                            <input type="file" name="images[]" id="property-images-input" multiple class="form-control" style="cursor: pointer;" accept="image/*">
                        </div>
                        <div id="image-preview-container" class="d-flex flex-wrap gap-2"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Download Brochure (PDF only, Max 10MB)</label>
                        <input type="file" name="pdf_brochure" class="form-control luxury-input-text" accept="application/pdf">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Upload Floor Plan Images (Multiple allowed)</label>
                        <input type="file" name="floor_plans[]" multiple class="form-control luxury-input-text" accept="image/*">
                    </div>

                    <!-- Dynamic Videos Repeater -->
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold d-flex justify-content-between align-items-center mb-3">
                            <span>Property Video Gallery</span>
                            <button type="button" class="btn btn-sm btn-outline-warning" id="add-video-row-btn"><i class="fa-solid fa-plus me-1"></i>Add Video</button>
                        </label>
                        <div id="video-repeater-container" class="d-flex flex-column gap-3">
                            <!-- Template video row -->
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Specs & Amenities Tab -->
            <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Bedrooms Count</label>
                        <input type="number" name="bedrooms" class="form-control luxury-input-text" placeholder="e.g. 3" value="<?php echo htmlspecialchars($old['bedrooms'] ?? '0'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Bathrooms Count</label>
                        <input type="number" name="bathrooms" class="form-control luxury-input-text" placeholder="e.g. 4" value="<?php echo htmlspecialchars($old['bathrooms'] ?? '0'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Area Size (SqFt) *</label>
                        <input type="number" step="0.01" name="area" class="form-control luxury-input-text" placeholder="e.g. 4500" required value="<?php echo htmlspecialchars($old['area'] ?? ''); ?>">
                        <?php if (!empty($errors['area'])): ?>
                            <div class="text-danger small fw-bold mt-1"><?php echo $errors['area']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold">Short Snippet Description *</label>
                        <input type="text" name="short_description" class="form-control luxury-input-text" placeholder="Concise excerpt for listing cards..." required value="<?php echo htmlspecialchars($old['short_description'] ?? ''); ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold">Full Detailed Description *</label>
                        <textarea name="full_description" rows="8" class="form-control luxury-input-text" required placeholder="Outline specifications, finishes, architect credits..."><?php echo htmlspecialchars($old['full_description'] ?? ''); ?></textarea>
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
                            $oldAmenities = $_POST['amenities'] ?? [];
                            foreach ($availableAmenities as $am):
                            ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="form-check amenity-checkbox-wrapper rounded-3 py-2 px-3">
                                        <input class="form-check-input text-warning" type="checkbox" name="amenities[]" value="<?php echo $am; ?>" id="am-check-<?php echo str_replace(' ', '-', strtolower($am)); ?>" <?php echo in_array($am, $oldAmenities) ? 'checked' : ''; ?>>
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
                        <label class="form-label text-secondary small fw-bold">Google Map Embed Code (Paste Iframe tag)</label>
                        <textarea name="google_map_embed" rows="3" class="form-control luxury-input-text" placeholder="<iframe src='https://google.com/maps/embed...' ...></iframe>"><?php echo htmlspecialchars($old['google_map_embed'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold">360 Virtual Tour URL</label>
                        <input type="text" name="three_sixty_tour_url" class="form-control luxury-input-text" placeholder="e.g. https://my.matterport.com/show/?m=..." value="<?php echo htmlspecialchars($old['three_sixty_tour_url'] ?? ''); ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold">SEO Title Tag Override</label>
                        <input type="text" name="meta_title" class="form-control luxury-input-text" placeholder="Defaults to Listing Title if empty" value="<?php echo htmlspecialchars($old['meta_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">SEO Meta Description</label>
                        <textarea name="meta_description" rows="3" class="form-control luxury-input-text" placeholder="Summary of listing matching search engine indexes..."><?php echo htmlspecialchars($old['meta_description'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">SEO Meta Keywords</label>
                        <textarea name="meta_keywords" rows="3" class="form-control luxury-input-text" placeholder="e.g. penthouse, luxury apartment, beverly hills sale"><?php echo htmlspecialchars($old['meta_keywords'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer panel actions -->
        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top border-secondary border-opacity-15">
            <div class="d-flex align-items-center">
                <div class="form-check form-switch me-4">
                    <input class="form-check-input text-warning" type="checkbox" name="is_featured" value="1" id="isFeaturedSwitch" <?php echo (isset($old['is_featured']) && $old['is_featured'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label text-white small fw-bold" for="isFeaturedSwitch"><i class="fa-solid fa-award text-warning me-2"></i>Feature Listing</label>
                </div>
                <div class="form-check form-switch me-4">
                    <input class="form-check-input text-warning" type="checkbox" name="is_published" value="1" id="isPublishedSwitch" <?php echo (!isset($old) || (isset($old['is_published']) && $old['is_published'] == 1)) ? 'checked' : ''; ?>>
                    <label class="form-check-label text-white small fw-bold" for="isPublishedSwitch"><i class="fa-solid fa-circle-check text-success me-2"></i>Publish Immediately</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input text-warning" type="checkbox" name="in_slider" value="1" id="inSliderSwitch" <?php echo (isset($old['in_slider']) && $old['in_slider'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label text-white small fw-bold" for="inSliderSwitch"><i class="fa-solid fa-images text-warning me-2"></i>Show in Hero Slideshow</label>
                </div>
            </div>
            <button type="submit" class="btn btn-gold-solid px-5 py-3 font-cinzel uppercase tracking-widest fw-bold">
                Save Property
            </button>
        </div>

    </div>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Handle adding dynamic video rows
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

