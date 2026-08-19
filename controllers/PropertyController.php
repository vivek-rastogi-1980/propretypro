<?php
namespace App\Controllers;

use App\Models\Property;
use App\Models\Category;
use App\Models\Enquiry;
use App\Helpers\ValidationHelper;
use App\Helpers\CSRFHelper;

/**
 * PropertyController manages public property catalogs, filter parameters, and listings detail views
 */
class PropertyController extends Controller {

    /**
     * Properties listings with filters and pagination
     */
    public function index(): void {
        $location = $_GET['location'] ?? '';
        $category = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? '';
        $budgetMax = $_GET['budget_max'] ?? '';
        $bedrooms = $_GET['bedrooms'] ?? '';
        $bathrooms = $_GET['bathrooms'] ?? '';
        $area = $_GET['area'] ?? '';
        $constructionStatus = $_GET['construction_status'] ?? '';
        $reraNumber = $_GET['rera_number'] ?? '';
        $keywords = $_GET['keywords'] ?? '';

        $filters = [
            'location' => ValidationHelper::cleanInput($location),
            'category' => ValidationHelper::cleanInput($category),
            'status' => ValidationHelper::cleanInput($status),
            'budget_max' => ValidationHelper::cleanInput($budgetMax),
            'bedrooms' => ValidationHelper::cleanInput($bedrooms),
            'bathrooms' => ValidationHelper::cleanInput($bathrooms),
            'area' => ValidationHelper::cleanInput($area),
            'construction_status' => ValidationHelper::cleanInput($constructionStatus),
            'rera_number' => ValidationHelper::cleanInput($reraNumber),
            'keywords' => ValidationHelper::cleanInput($keywords)
        ];


        // Pagination calculations
        $page = (int)($_GET['page'] ?? 1);
        if ($page < 1) $page = 1;
        $limit = 9;
        $offset = ($page - 1) * $limit;

        $totalProperties = Property::countFiltered($filters);
        $properties = Property::getFiltered($filters, $limit, $offset);
        
        $totalPages = ceil($totalProperties / $limit);
        if ($totalPages < 1) $totalPages = 1;

        $categories = Category::getAll();

        $this->render('property/list', [
            'pageTitle' => 'Property Listings',
            'properties' => $properties,
            'categories' => $categories,
            'filters' => $filters,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProperties' => $totalProperties
        ]);
    }

    /**
     * Property detailed page view
     */
    public function detail(string $slug): void {
        $property = Property::findBySlug($slug);
        
        if (!$property) {
            // Show custom 404 page for listings
            header("HTTP/1.0 404 Not Found");
            $this->render('property/404', ['pageTitle' => 'Listing Not Found'], 'frontend');
            return;
        }

        // Fetch gallery images
        $images = Property::getImages($property['id']);
        
        // Fetch related properties (exclude current, limit 3)
        $related = Property::getRelated($property['category_id'], $property['id'], 3);

        // Decode amenities JSON
        $amenities = [];
        if (!empty($property['amenities'])) {
            $amenities = json_decode($property['amenities'], true) ?: [];
        }

        $this->render('property/detail', [
            'pageTitle' => $property['title'],
            'property' => $property,
            'images' => $images,
            'relatedProperties' => $related,
            'amenities' => $amenities
        ]);
    }

    /**
     * Submit an enquiry specific to a property listing (AJAX)
     */
    public function submitPropertyEnquiry(string $slug): void {
        CSRFHelper::verifyPost();

        $property = Property::findBySlug($slug);
        if (!$property) {
            $this->json(['success' => false, 'message' => 'Property not found.'], 404);
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $message = $_POST['message'] ?? '';

        $formData = [
            'name' => ValidationHelper::cleanInput($name),
            'email' => ValidationHelper::cleanInput($email),
            'phone' => ValidationHelper::cleanInput($phone),
            'message' => ValidationHelper::cleanInput($message),
            'property_id' => $property['id']
        ];

        // Field Validation
        $errors = ValidationHelper::validateRequired($formData, ['name', 'email', 'message']);
        
        if (!empty($formData['email']) && !ValidationHelper::isValidEmail($formData['email'])) {
            $errors['email'] = "Please provide a valid email address.";
        }

        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
        }

        try {
            $result = Enquiry::create($formData);
            if ($result) {
                $this->json(['success' => true, 'message' => 'Your inquiry about this property has been received. We will contact you soon.']);
            } else {
                $this->json(['success' => false, 'message' => 'Submission failed. Please try again later.'], 500);
            }
        } catch (\Throwable $e) {
            $this->json(['success' => false, 'message' => 'Server error while storing property enquiry.'], 500);
        }
    }
}
