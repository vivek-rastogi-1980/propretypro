<?php
namespace App\Controllers;

use App\Models\Property;
use App\Models\Category;
use App\Models\Enquiry;
use App\Helpers\ValidationHelper;
use App\Helpers\CSRFHelper;

/**
 * HomeController manages Front Pages (Home, About, Contact) and enquiries submissions
 */
class HomeController extends Controller {

    /**
     * Homepage
     */
    public function index(): void {
        $slider = Property::getSliderProperties();
        $featured = Property::getFeatured(6);
        $latest = Property::getLatest(6);
        $categories = Category::getAll();

        $this->render('home/index', [
            'sliderProperties' => $slider,
            'featuredProperties' => $featured,
            'latestProperties' => $latest,
            'categories' => $categories
        ]);
    }

    /**
     * About Us Page
     */
    public function about(): void {
        $this->render('home/about', [
            'pageTitle' => 'About Us'
        ]);
    }

    /**
     * Contact Us Page
     */
    public function contact(): void {
        $this->render('home/contact', [
            'pageTitle' => 'Contact Us'
        ]);
    }

    /**
     * Handle contact and general queries submissions (AJAX)
     */
    public function submitEnquiry(): void {
        // Enforce CSRF verification
        CSRFHelper::verifyPost();

        // Retrieve raw post inputs
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $message = $_POST['message'] ?? '';

        $formData = [
            'name' => ValidationHelper::cleanInput($name),
            'email' => ValidationHelper::cleanInput($email),
            'phone' => ValidationHelper::cleanInput($phone),
            'message' => ValidationHelper::cleanInput($message),
            'property_id' => null
        ];

        // Validations
        $errors = ValidationHelper::validateRequired($formData, ['name', 'email', 'message']);
        
        if (!empty($formData['email']) && !ValidationHelper::isValidEmail($formData['email'])) {
            $errors['email'] = "Please provide a valid email address.";
        }

        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
        }

        // Insert Enquiry
        try {
            $result = Enquiry::create($formData);
            if ($result) {
                $this->json(['success' => true, 'message' => 'Thank you! Your enquiry has been received successfully. We will contact you soon.']);
            } else {
                $this->json(['success' => false, 'message' => 'Something went wrong. Please try again later.'], 500);
            }
        } catch (\Throwable $e) {
            $this->json(['success' => false, 'message' => 'Internal error occurred while submitting enquiry.'], 500);
        }
    }
}
