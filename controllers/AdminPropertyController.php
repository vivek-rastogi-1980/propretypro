<?php
namespace App\Controllers;

use App\Models\Property;
use App\Models\Category;
use App\Helpers\AuthHelper;
use App\Helpers\ValidationHelper;
use App\Helpers\UploadHelper;
use App\Helpers\CSRFHelper;

/**
 * AdminPropertyController manages property CRUD, slugs, and multi-image galleries
 */
class AdminPropertyController extends Controller {

    public function __construct() {
        AuthHelper::requireLogin();
    }

    /**
     * Property list display
     */
    public function index(): void {
        $search = $_GET['search'] ?? '';
        
        $filters = [
            'location' => ValidationHelper::cleanInput($search),
            'admin_view' => true
        ];

        // Pagination
        $page = (int)($_GET['page'] ?? 1);
        if ($page < 1) $page = 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $totalProperties = Property::countFiltered($filters);
        $properties = Property::getFiltered($filters, $limit, $offset);

        $totalPages = ceil($totalProperties / $limit);
        if ($totalPages < 1) $totalPages = 1;

        $this->render('admin/property/index', [
            'pageTitle' => 'Manage Properties',
            'properties' => $properties,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProperties' => $totalProperties
        ], 'admin');
    }

    /**
     * Add Property form page
     */
    public function create(): void {
        $categories = Category::getAll();
        $this->render('admin/property/create', [
            'pageTitle' => 'Add Property',
            'categories' => $categories
        ], 'admin');
    }

    /**
     * Store new property
     */
    public function store(): void {
        CSRFHelper::verifyPost();

        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $status = $_POST['status'] ?? 'For Sale';
        $price = (float)($_POST['price'] ?? 0);
        $location = $_POST['location'] ?? '';
        $bedrooms = (int)($_POST['bedrooms'] ?? 0);
        $bathrooms = (int)($_POST['bathrooms'] ?? 0);
        $area = (float)($_POST['area'] ?? 0);
        $shortDescription = $_POST['short_description'] ?? '';
        $fullDescription = $_POST['full_description'] ?? '';
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        $inSlider = isset($_POST['in_slider']) ? 1 : 0;

        // New fields
        $reraNumber = $_POST['rera_number'] ?? '';
        $constructionStatus = $_POST['construction_status'] ?? 'Ready To Move';
        $availabilityStatus = $_POST['availability_status'] ?? 'Available';
        $googleMapEmbed = $_POST['google_map_embed'] ?? '';
        $threeSixtyTourUrl = $_POST['three_sixty_tour_url'] ?? '';
        $metaTitle = $_POST['meta_title'] ?? '';
        $metaDescription = $_POST['meta_description'] ?? '';
        $metaKeywords = $_POST['meta_keywords'] ?? '';

        // Process Videos List
        $videoTypes = $_POST['video_types'] ?? [];
        $videoUrls = $_POST['video_urls'] ?? [];
        $videoList = [];
        for ($i = 0; $i < count($videoUrls); $i++) {
            if (!empty($videoUrls[$i])) {
                $videoList[] = [
                    'type' => ValidationHelper::cleanInput($videoTypes[$i] ?? 'youtube'),
                    'url' => ValidationHelper::cleanInput($videoUrls[$i])
                ];
            }
        }
        $videosJson = !empty($videoList) ? json_encode($videoList) : null;
        
        // Amenities checkboxes
        $selectedAmenities = $_POST['amenities'] ?? [];
        $amenitiesJson = json_encode($selectedAmenities);

        $data = [
            'title' => ValidationHelper::cleanInput($title),
            'slug' => ValidationHelper::cleanInput($slug),
            'category_id' => $categoryId,
            'status' => ValidationHelper::cleanInput($status),
            'price' => $price,
            'location' => ValidationHelper::cleanInput($location),
            'bedrooms' => $bedrooms,
            'bathrooms' => $bathrooms,
            'area' => $area,
            'short_description' => ValidationHelper::cleanInput($shortDescription),
            'full_description' => trim($fullDescription), // Allow HTML
            'amenities' => $amenitiesJson,
            'is_featured' => $isFeatured,
            'is_published' => $isPublished,
            'in_slider' => $inSlider,
            'slider_image' => null,
            'rera_number' => ValidationHelper::cleanInput($reraNumber),
            'construction_status' => ValidationHelper::cleanInput($constructionStatus),
            'availability_status' => ValidationHelper::cleanInput($availabilityStatus),
            'google_map_embed' => trim($googleMapEmbed), // Allow raw iframe tag
            'pdf_brochure' => null, // filled after creation
            'floor_plans' => null, // filled after creation
            'meta_title' => ValidationHelper::cleanInput($metaTitle),
            'meta_description' => ValidationHelper::cleanInput($metaDescription),
            'meta_keywords' => ValidationHelper::cleanInput($metaKeywords),
            'videos' => $videosJson,
            'three_sixty_tour_url' => ValidationHelper::cleanInput($threeSixtyTourUrl)
        ];

        // Validations
        $errors = ValidationHelper::validateRequired($data, [
            'title', 'category_id', 'status', 'price', 'location', 'area', 'short_description', 'full_description'
        ]);

        if ($price <= 0) {
            $errors['price'] = "Price must be a positive number.";
        }
        if ($area <= 0) {
            $errors['area'] = "Area must be a positive number.";
        }

        // Generate Slug
        if (empty($data['slug'])) {
            $data['slug'] = ValidationHelper::slugify($data['title']);
        } else {
            $data['slug'] = ValidationHelper::slugify($data['slug']);
        }

        // Check if slug is unique
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Property::isSlugExists($data['slug'])) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        if (!empty($errors)) {
            $categories = Category::getAll();
            $this->render('admin/property/create', [
                'pageTitle' => 'Add Property',
                'categories' => $categories,
                'errors' => $errors,
                'old' => $_POST
            ], 'admin');
            return;
        }

        // Create property record
        try {
            $propertyId = Property::create($data);
            if ($propertyId > 0) {
                $updateData = [];

                // Upload PDF Brochure if provided
                if (!empty($_FILES['pdf_brochure']['name']) && $_FILES['pdf_brochure']['error'] === UPLOAD_ERR_OK) {
                    try {
                        $pdfPath = UploadHelper::uploadBrochure($_FILES['pdf_brochure'], $propertyId);
                        if ($pdfPath) {
                            $updateData['pdf_brochure'] = $pdfPath;
                        }
                    } catch (\Throwable $e) {
                        $_SESSION['property_warning'] = "Property created, but brochure failed to upload: " . $e->getMessage();
                    }
                }

                // Upload Floor Plan images if provided
                $floorPlansPaths = [];
                if (!empty($_FILES['floor_plans']['name'][0])) {
                    $files = $_FILES['floor_plans'];
                    $count = count($files['name']);
                    for ($i = 0; $i < $count; $i++) {
                        if ($files['error'][$i] === UPLOAD_ERR_OK) {
                            try {
                                $singleFile = [
                                    'name' => $files['name'][$i],
                                    'type' => $files['type'][$i],
                                    'tmp_name' => $files['tmp_name'][$i],
                                    'error' => $files['error'][$i],
                                    'size' => $files['size'][$i],
                                ];
                                $fpPath = UploadHelper::uploadFloorPlan($singleFile, $propertyId);
                                if ($fpPath) {
                                    $floorPlansPaths[] = $fpPath;
                                }
                            } catch (\Throwable $e) {
                                $_SESSION['property_warning'] = "Property created, but some floor plans failed to upload: " . $e->getMessage();
                            }
                        }
                    }
                }

                if (!empty($floorPlansPaths)) {
                    $updateData['floor_plans'] = json_encode($floorPlansPaths);
                }

                // Update brochure and floor plan references
                if (!empty($updateData)) {
                    $dbProperty = Property::find($propertyId);
                    $mergedData = array_merge($dbProperty, $updateData);
                    Property::update($propertyId, $mergedData);
                }

                // Upload property images (if selected)
                if (!empty($_FILES['images']['name'][0])) {
                    $uploadResult = UploadHelper::uploadPropertyImages($_FILES['images'], $propertyId);
                    if (!empty($uploadResult['success'])) {
                        Property::addImages($propertyId, $uploadResult['success']);
                    }
                    if (!empty($uploadResult['errors'])) {
                        $_SESSION['property_warning'] = "Property created, but some gallery images failed to upload:<br>" . implode('<br>', $uploadResult['errors']);
                    }
                }

                $_SESSION['property_success'] = "Property listing created successfully.";
                header("Location: " . BASE_URL . "admin/properties");
                exit;
            }
        } catch (\Throwable $e) {
            $errors['db'] = "Failed to create listing: " . $e->getMessage();
            $categories = Category::getAll();
            $this->render('admin/property/create', [
                'pageTitle' => 'Add Property',
                'categories' => $categories,
                'errors' => $errors,
                'old' => $_POST
            ], 'admin');
        }
    }

    /**
     * Edit Property form page
     */
    public function edit(int $id): void {
        $property = Property::find($id);
        if (!$property) {
            $_SESSION['property_error'] = "Property not found.";
            header("Location: " . BASE_URL . "admin/properties");
            exit;
        }

        $categories = Category::getAll();
        $images = Property::getImages($id);
        $amenities = json_decode($property['amenities'], true) ?: [];

        $this->render('admin/property/edit', [
            'pageTitle' => 'Edit Property | ' . $property['title'],
            'property' => $property,
            'categories' => $categories,
            'images' => $images,
            'amenities' => $amenities
        ], 'admin');
    }

    /**
     * Update existing property
     */
    public function update(int $id): void {
        CSRFHelper::verifyPost();

        $property = Property::find($id);
        if (!$property) {
            $_SESSION['property_error'] = "Property not found.";
            header("Location: " . BASE_URL . "admin/properties");
            exit;
        }

        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $status = $_POST['status'] ?? 'For Sale';
        $price = (float)($_POST['price'] ?? 0);
        $location = $_POST['location'] ?? '';
        $bedrooms = (int)($_POST['bedrooms'] ?? 0);
        $bathrooms = (int)($_POST['bathrooms'] ?? 0);
        $area = (float)($_POST['area'] ?? 0);
        $shortDescription = $_POST['short_description'] ?? '';
        $fullDescription = $_POST['full_description'] ?? '';
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        $inSlider = isset($_POST['in_slider']) ? 1 : 0;

        // New fields
        $reraNumber = $_POST['rera_number'] ?? '';
        $constructionStatus = $_POST['construction_status'] ?? 'Ready To Move';
        $availabilityStatus = $_POST['availability_status'] ?? 'Available';
        $googleMapEmbed = $_POST['google_map_embed'] ?? '';
        $threeSixtyTourUrl = $_POST['three_sixty_tour_url'] ?? '';
        $metaTitle = $_POST['meta_title'] ?? '';
        $metaDescription = $_POST['meta_description'] ?? '';
        $metaKeywords = $_POST['meta_keywords'] ?? '';

        // Process Videos List
        $videoTypes = $_POST['video_types'] ?? [];
        $videoUrls = $_POST['video_urls'] ?? [];
        $videoList = [];
        for ($i = 0; $i < count($videoUrls); $i++) {
            if (!empty($videoUrls[$i])) {
                $videoList[] = [
                    'type' => ValidationHelper::cleanInput($videoTypes[$i] ?? 'youtube'),
                    'url' => ValidationHelper::cleanInput($videoUrls[$i])
                ];
            }
        }
        $videosJson = !empty($videoList) ? json_encode($videoList) : null;

        // Process brochure PDF
        $pdfPath = $property['pdf_brochure']; // default to existing
        if (!empty($_FILES['pdf_brochure']['name']) && $_FILES['pdf_brochure']['error'] === UPLOAD_ERR_OK) {
            try {
                // Delete old brochure if exists
                if (!empty($pdfPath) && file_exists(ROOT_DIR . $pdfPath)) {
                    unlink(ROOT_DIR . $pdfPath);
                }
                $pdfPath = UploadHelper::uploadBrochure($_FILES['pdf_brochure'], $id);
            } catch (\Throwable $e) {
                $_SESSION['property_warning'] = "Brochure upload failed: " . $e->getMessage();
            }
        } elseif (isset($_POST['delete_brochure']) && $_POST['delete_brochure'] == '1') {
            if (!empty($pdfPath) && file_exists(ROOT_DIR . $pdfPath)) {
                unlink(ROOT_DIR . $pdfPath);
            }
            $pdfPath = null;
        }

        // Process floor plans
        $floorPlansList = json_decode($property['floor_plans'], true) ?: [];
        // Delete specific floor plan images
        if (isset($_POST['delete_floor_plans'])) {
            foreach ($_POST['delete_floor_plans'] as $delFp) {
                $delFpClean = ValidationHelper::cleanInput($delFp);
                if (file_exists(ROOT_DIR . $delFpClean)) {
                    unlink(ROOT_DIR . $delFpClean);
                }
                $floorPlansList = array_diff($floorPlansList, [$delFpClean]);
            }
            $floorPlansList = array_values($floorPlansList);
        }

        // Upload new floor plans
        if (!empty($_FILES['floor_plans']['name'][0])) {
            $files = $_FILES['floor_plans'];
            $count = count($files['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    try {
                        $singleFile = [
                            'name' => $files['name'][$i],
                            'type' => $files['type'][$i],
                            'tmp_name' => $files['tmp_name'][$i],
                            'error' => $files['error'][$i],
                            'size' => $files['size'][$i],
                        ];
                        $fpPath = UploadHelper::uploadFloorPlan($singleFile, $id);
                        if ($fpPath) {
                            $floorPlansList[] = $fpPath;
                        }
                    } catch (\Throwable $e) {
                        $_SESSION['property_warning'] = "Some floor plans failed to upload: " . $e->getMessage();
                    }
                }
            }
        }
        $floorPlansJson = !empty($floorPlansList) ? json_encode($floorPlansList) : null;

        $selectedAmenities = $_POST['amenities'] ?? [];
        $amenitiesJson = json_encode($selectedAmenities);

        $data = [
            'title' => ValidationHelper::cleanInput($title),
            'slug' => ValidationHelper::cleanInput($slug),
            'category_id' => $categoryId,
            'status' => ValidationHelper::cleanInput($status),
            'price' => $price,
            'location' => ValidationHelper::cleanInput($location),
            'bedrooms' => $bedrooms,
            'bathrooms' => $bathrooms,
            'area' => $area,
            'short_description' => ValidationHelper::cleanInput($shortDescription),
            'full_description' => trim($fullDescription),
            'amenities' => $amenitiesJson,
            'is_featured' => $isFeatured,
            'is_published' => $isPublished,
            'in_slider' => $inSlider,
            'slider_image' => $property['slider_image'] ?? null,
            'rera_number' => ValidationHelper::cleanInput($reraNumber),
            'construction_status' => ValidationHelper::cleanInput($constructionStatus),
            'availability_status' => ValidationHelper::cleanInput($availabilityStatus),
            'google_map_embed' => trim($googleMapEmbed),
            'pdf_brochure' => $pdfPath,
            'floor_plans' => $floorPlansJson,
            'meta_title' => ValidationHelper::cleanInput($metaTitle),
            'meta_description' => ValidationHelper::cleanInput($metaDescription),
            'meta_keywords' => ValidationHelper::cleanInput($metaKeywords),
            'videos' => $videosJson,
            'three_sixty_tour_url' => ValidationHelper::cleanInput($threeSixtyTourUrl)
        ];

        // Validations
        $errors = ValidationHelper::validateRequired($data, [
            'title', 'category_id', 'status', 'price', 'location', 'area', 'short_description', 'full_description'
        ]);

        if ($price <= 0) {
            $errors['price'] = "Price must be a positive number.";
        }
        if ($area <= 0) {
            $errors['area'] = "Area must be a positive number.";
        }

        // Generate/Validate unique slug
        if (empty($data['slug'])) {
            $data['slug'] = ValidationHelper::slugify($data['title']);
        } else {
            $data['slug'] = ValidationHelper::slugify($data['slug']);
        }

        $originalSlug = $data['slug'];
        $counter = 1;
        while (Property::isSlugExists($data['slug'], $id)) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        if (!empty($errors)) {
            $categories = Category::getAll();
            $images = Property::getImages($id);
            $amenities = json_decode($property['amenities'], true) ?: [];

            $this->render('admin/property/edit', [
                'pageTitle' => 'Edit Property | ' . $property['title'],
                'property' => array_merge($property, $data),
                'categories' => $categories,
                'images' => $images,
                'amenities' => $amenities,
                'errors' => $errors
            ], 'admin');
            return;
        }

        try {
            $result = Property::update($id, $data);
            if ($result) {
                // Upload property images (if selected)
                if (!empty($_FILES['images']['name'][0])) {
                    // Check gallery size
                    $currentImages = Property::getImages($id);
                    $availableSlots = MAX_IMAGE_COUNT - count($currentImages);
                    
                    if ($availableSlots <= 0) {
                        $_SESSION['property_warning'] = "Cannot upload new images. The maximum limit of 20 images has been reached. Please delete old ones first.";
                    } else {
                        $uploadResult = UploadHelper::uploadPropertyImages($_FILES['images'], $id);
                        if (!empty($uploadResult['success'])) {
                            // Limit new uploads to available slots
                            $newUploads = array_slice($uploadResult['success'], 0, $availableSlots);
                            Property::addImages($id, $newUploads);
                            
                            if (count($uploadResult['success']) > $availableSlots) {
                                $_SESSION['property_warning'] = "Only the first {$availableSlots} images were uploaded because the limit of 20 images was reached.";
                            }
                        }
                        if (!empty($uploadResult['errors'])) {
                            $_SESSION['property_warning'] = "Property updated, but some images failed to upload:<br>" . implode('<br>', $uploadResult['errors']);
                        }
                    }
                }
                
                $_SESSION['property_success'] = "Property listing updated successfully.";
                header("Location: " . BASE_URL . "admin/properties");
                exit;
            }
        } catch (\Throwable $e) {
            $errors['db'] = "Failed to update listing: " . $e->getMessage();
            $categories = Category::getAll();
            $images = Property::getImages($id);
            $amenities = json_decode($property['amenities'], true) ?: [];

            $this->render('admin/property/edit', [
                'pageTitle' => 'Edit Property | ' . $property['title'],
                'property' => array_merge($property, $data),
                'categories' => $categories,
                'images' => $images,
                'amenities' => $amenities,
                'errors' => $errors
            ], 'admin');
        }
    }

    /**
     * Delete Property listing and clean up directory uploads
     */
    public function delete(int $id): void {
        CSRFHelper::verifyPost();
        
        $property = Property::find($id);
        if ($property) {
            // Delete actual images directory
            $dirPath = UPLOAD_DIR . $id;
            UploadHelper::deleteDirectory($dirPath);

            // Delete database records
            Property::delete($id);
            $_SESSION['property_success'] = "Property and its gallery were deleted successfully.";
        } else {
            $_SESSION['property_error'] = "Property not found.";
        }

        header("Location: " . BASE_URL . "admin/properties");
        exit;
    }

    /**
     * AJAX endpoint: Delete gallery image
     */
    public function deleteImage(): void {
        CSRFHelper::verifyPost();
        
        $imageId = (int)($_POST['image_id'] ?? 0);
        if ($imageId > 0) {
            $result = Property::deleteImage($imageId);
            if ($result) {
                $this->json(['success' => true, 'message' => 'Image removed from gallery.']);
            }
        }

        $this->json(['success' => false, 'message' => 'Failed to delete image.'], 400);
    }

    /**
     * AJAX endpoint: Set featured image
     */
    public function setFeaturedImage(): void {
        CSRFHelper::verifyPost();

        $propertyId = (int)($_POST['property_id'] ?? 0);
        $imageId = (int)($_POST['image_id'] ?? 0);

        if ($propertyId > 0 && $imageId > 0) {
            $result = Property::setFeaturedImage($propertyId, $imageId);
            if ($result) {
                $this->json(['success' => true, 'message' => 'Featured image updated.']);
            }
        }

        $this->json(['success' => false, 'message' => 'Failed to update featured image.'], 400);
    }

    /**
     * AJAX endpoint: Set slider image
     */
    public function setSliderImage(): void {
        CSRFHelper::verifyPost();

        $propertyId = (int)($_POST['property_id'] ?? 0);
        $imageId = (int)($_POST['image_id'] ?? 0);

        if ($propertyId > 0 && $imageId > 0) {
            $result = Property::setSliderImage($propertyId, $imageId);
            if ($result) {
                $this->json(['success' => true, 'message' => 'Slider image updated successfully.']);
            }
        }

        $this->json(['success' => false, 'message' => 'Failed to update slider image.'], 400);
    }

    /**
     * Duplicate property listing action
     */
    public function duplicate(int $id): void {
        CSRFHelper::verifyPost();
        
        try {
            $newId = Property::duplicate($id);
            if ($newId > 0) {
                $_SESSION['property_success'] = "Property listing duplicated successfully as draft.";
            } else {
                $_SESSION['property_error'] = "Failed to duplicate listing.";
            }
        } catch (\Throwable $e) {
            $_SESSION['property_error'] = "Error duplicating listing: " . $e->getMessage();
        }

        header("Location: " . BASE_URL . "admin/properties");
        exit;
    }
}

