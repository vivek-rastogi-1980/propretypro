<?php
namespace App\Controllers;

use App\Models\Admin;
use App\Models\Property;
use App\Models\Enquiry;
use App\Models\Setting;
use App\Helpers\AuthHelper;
use App\Helpers\ValidationHelper;
use App\Helpers\CSRFHelper;

/**
 * AdminController manages dashboard statistics, authentications, website settings and customer messages
 */
class AdminController extends Controller {

    /**
     * Admin Dashboard landing
     */
    public function dashboard(): void {
        AuthHelper::requireLogin();

        // Fetch stats
        $totalProperties = Property::countFiltered(['admin_view' => true]);
        $featuredProperties = Property::countFiltered(['admin_view' => true, 'featured' => 1]); // Wait, countFiltered featured
        
        // Let's write customized query helper directly or pass parameter
        $featuredCount = Property::countFiltered(['admin_view' => true, 'category' => '', 'status' => '', 'location' => '']);
        // Let's get real counts
        self::initDB();
        $db = self::$db;
        $totalProps = (int)$db->query("SELECT COUNT(*) FROM properties")->fetchColumn();
        $featProps = (int)$db->query("SELECT COUNT(*) FROM properties WHERE is_featured = 1")->fetchColumn();
        $totalEnquiries = (int)$db->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
        $unreadEnquiries = Enquiry::getUnreadCount();

        // Recent listings and messages feeds
        $recentEnquiries = Enquiry::getFiltered([], 5, 0);
        $recentProperties = Property::getFiltered(['admin_view' => true], 5, 0);

        $this->render('admin/dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'stats' => [
                'properties' => $totalProps,
                'featured' => $featProps,
                'enquiries' => $totalEnquiries,
                'unread' => $unreadEnquiries
            ],
            'recentEnquiries' => $recentEnquiries,
            'recentProperties' => $recentProperties
        ], 'admin');
    }

    /**
     * Helper to init DB reference inside controller
     */
    private static ?\PDO $db = null;
    private static function initDB(): void {
        if (self::$db === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            self::$db = new \PDO($dsn, DB_USER, DB_PASS, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
        }
    }

    /**
     * Render Login Panel
     */
    public function login(): void {
        if (AuthHelper::isLoggedIn()) {
            header("Location: " . BASE_URL . "admin/dashboard");
            exit;
        }

        $this->render('admin/login', [
            'pageTitle' => 'Admin Login'
        ], 'none');
    }

    /**
     * Handle Login authentication post
     */
    public function postLogin(): void {
        CSRFHelper::verifyPost();

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $errors = ValidationHelper::validateRequired($_POST, ['username', 'password']);

        if (empty($errors)) {
            $admin = Admin::authenticate($username, $password);
            if ($admin) {
                AuthHelper::login($admin);
                header("Location: " . BASE_URL . "admin/dashboard");
                exit;
            } else {
                $errors['auth'] = "Invalid username/email or password.";
            }
        }

        $this->render('admin/login', [
            'pageTitle' => 'Admin Login',
            'errors' => $errors,
            'old' => $_POST
        ], 'none');
    }

    /**
     * Sign out
     */
    public function logout(): void {
        AuthHelper::logout();
        header("Location: " . BASE_URL . "admin/login");
        exit;
    }

    /**
     * Forgot password page
     */
    public function forgotPassword(): void {
        $this->render('admin/forgot', [
            'pageTitle' => 'Forgot Password'
        ], 'none');
    }

    /**
     * Simulates password reset sequence
     */
    public function postForgotPassword(): void {
        CSRFHelper::verifyPost();
        
        $email = $_POST['email'] ?? '';
        
        $errors = ValidationHelper::validateRequired($_POST, ['email']);
        if (!empty($email) && !ValidationHelper::isValidEmail($email)) {
            $errors['email'] = "Invalid email format.";
        }

        $successMessage = '';
        if (empty($errors)) {
            // Find admin by email
            self::initDB();
            $stmt = self::$db->prepare("SELECT id FROM admins WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $adminId = $stmt->fetchColumn();

            if ($adminId) {
                // Generate a temporary new password for local demonstration purposes
                $tempPassword = 'Reset@' . rand(1000, 9999);
                
                // Directly update the DB (simulation for local dev convenience)
                Admin::updatePassword((int)$adminId, $tempPassword);
                
                $successMessage = "Your password has been successfully reset! Since local SMTP mailing isn't active, here is your temporary login password: <strong>{$tempPassword}</strong>. Please sign in and update it immediately.";
            } else {
                $errors['email'] = "No administrator record matched that email address.";
            }
        }

        $this->render('admin/forgot', [
            'pageTitle' => 'Forgot Password',
            'errors' => $errors,
            'successMessage' => $successMessage
        ], 'none');
    }

    /**
     * Website settings configuration UI
     */
    public function settings(): void {
        AuthHelper::requireLogin();
        
        $settings = Setting::getAll();

        $this->render('admin/settings', [
            'pageTitle' => 'Website Settings',
            'settings' => $settings
        ], 'admin');
    }

    /**
     * Save settings modifications
     */
    public function saveSettings(): void {
        AuthHelper::requireLogin();
        CSRFHelper::verifyPost();

        // Process file uploads for Logo / Favicon
        $settingsData = [
            'company_name' => ValidationHelper::cleanInput($_POST['company_name'] ?? ''),
            'company_description' => ValidationHelper::cleanInput($_POST['company_description'] ?? ''),
            'company_phone' => ValidationHelper::cleanInput($_POST['company_phone'] ?? ''),
            'company_email' => ValidationHelper::cleanInput($_POST['company_email'] ?? ''),
            'whatsapp_number' => ValidationHelper::cleanInput($_POST['whatsapp_number'] ?? ''),
            'office_address' => ValidationHelper::cleanInput($_POST['office_address'] ?? ''),
            'social_facebook' => ValidationHelper::cleanInput($_POST['social_facebook'] ?? ''),
            'social_instagram' => ValidationHelper::cleanInput($_POST['social_instagram'] ?? ''),
            'social_twitter' => ValidationHelper::cleanInput($_POST['social_twitter'] ?? ''),
            'social_linkedin' => ValidationHelper::cleanInput($_POST['social_linkedin'] ?? ''),
            'social_youtube' => ValidationHelper::cleanInput($_POST['social_youtube'] ?? ''),
            'social_pinterest' => ValidationHelper::cleanInput($_POST['social_pinterest'] ?? ''),
            'footer_content' => ValidationHelper::cleanInput($_POST['footer_content'] ?? ''),
            'seo_title' => ValidationHelper::cleanInput($_POST['seo_title'] ?? ''),
            'seo_meta_description' => ValidationHelper::cleanInput($_POST['seo_meta_description'] ?? ''),
            'seo_meta_keywords' => ValidationHelper::cleanInput($_POST['seo_meta_keywords'] ?? ''),
            'google_analytics' => trim($_POST['google_analytics'] ?? ''),
            'meta_pixel' => trim($_POST['meta_pixel'] ?? ''),
            'google_search_console' => trim($_POST['google_search_console'] ?? ''),
            'smtp_host' => ValidationHelper::cleanInput($_POST['smtp_host'] ?? ''),
            'smtp_port' => ValidationHelper::cleanInput($_POST['smtp_port'] ?? ''),
            'smtp_user' => ValidationHelper::cleanInput($_POST['smtp_user'] ?? ''),
            'smtp_pass' => ValidationHelper::cleanInput($_POST['smtp_pass'] ?? ''),
            'smtp_encryption' => ValidationHelper::cleanInput($_POST['smtp_encryption'] ?? ''),
            'smtp_from_email' => ValidationHelper::cleanInput($_POST['smtp_from_email'] ?? ''),
            'smtp_from_name' => ValidationHelper::cleanInput($_POST['smtp_from_name'] ?? ''),
            'seo_schema' => trim($_POST['seo_schema'] ?? ''),
            'seo_og_title' => ValidationHelper::cleanInput($_POST['seo_og_title'] ?? ''),
            'seo_og_description' => ValidationHelper::cleanInput($_POST['seo_og_description'] ?? ''),
            'seo_twitter_card' => ValidationHelper::cleanInput($_POST['seo_twitter_card'] ?? ''),
            'seo_canonical_url' => ValidationHelper::cleanInput($_POST['seo_canonical_url'] ?? ''),
            'seo_robots' => ValidationHelper::cleanInput($_POST['seo_robots'] ?? '')
        ];

        // Handle file uploads for Logo & Favicon
        $errors = [];
        $targetDir = ROOT_DIR . 'uploads/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Logo Upload
        if (!empty($_FILES['company_logo']['name']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
                $logoName = 'logo_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $targetDir . $logoName)) {
                    $settingsData['company_logo'] = 'uploads/' . $logoName;
                }
            } else {
                $errors[] = "Invalid logo file extension.";
            }
        }

        // Favicon Upload
        if (!empty($_FILES['company_favicon']['name']) && $_FILES['company_favicon']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['company_favicon']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'ico', 'x-icon'])) {
                $favName = 'favicon_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['company_favicon']['tmp_name'], $targetDir . $favName)) {
                    $settingsData['company_favicon'] = 'uploads/' . $favName;
                }
            } else {
                $errors[] = "Invalid favicon file extension (only PNG or ICO allowed).";
            }
        }

        if (empty($errors)) {
            $result = Setting::updateAll($settingsData);
            if ($result) {
                $_SESSION['settings_success'] = "Website settings updated successfully.";
            } else {
                $_SESSION['settings_error'] = "Failed to update configurations in the database.";
            }
        } else {
            $_SESSION['settings_error'] = implode('<br>', $errors);
        }

        header("Location: " . BASE_URL . "admin/settings");
        exit;
    }

    /**
     * Pages content configuration UI
     */
    public function pages(): void {
        AuthHelper::requireLogin();
        
        $settings = Setting::getAll();

        $this->render('admin/pages', [
            'pageTitle' => 'Manage Pages Content',
            'settings' => $settings
        ], 'admin');
    }

    /**
     * Save pages content modifications and upload images
     */
    public function savePages(): void {
        AuthHelper::requireLogin();
        CSRFHelper::verifyPost();

        // Get all current settings so we don't overwrite existing paths if no new file is uploaded
        $currentSettings = Setting::getAll();

        $pagesData = [
            // Home page
            'home_overview_badge' => ValidationHelper::cleanInput($_POST['home_overview_badge'] ?? ''),
            'home_overview_title' => ValidationHelper::cleanInput($_POST['home_overview_title'] ?? ''),
            'home_overview_desc_1' => ValidationHelper::cleanInput($_POST['home_overview_desc_1'] ?? ''),
            'home_overview_desc_2' => ValidationHelper::cleanInput($_POST['home_overview_desc_2'] ?? ''),
            'home_overview_quote' => ValidationHelper::cleanInput($_POST['home_overview_quote'] ?? ''),
            'home_overview_stat1_val' => ValidationHelper::cleanInput($_POST['home_overview_stat1_val'] ?? ''),
            'home_overview_stat1_lbl' => ValidationHelper::cleanInput($_POST['home_overview_stat1_lbl'] ?? ''),
            'home_overview_stat2_val' => ValidationHelper::cleanInput($_POST['home_overview_stat2_val'] ?? ''),
            'home_overview_stat2_lbl' => ValidationHelper::cleanInput($_POST['home_overview_stat2_lbl'] ?? ''),
            'home_stat1_num' => ValidationHelper::cleanInput($_POST['home_stat1_num'] ?? ''),
            'home_stat1_lbl' => ValidationHelper::cleanInput($_POST['home_stat1_lbl'] ?? ''),
            'home_stat2_num' => ValidationHelper::cleanInput($_POST['home_stat2_num'] ?? ''),
            'home_stat2_lbl' => ValidationHelper::cleanInput($_POST['home_stat2_lbl'] ?? ''),
            'home_stat3_num' => ValidationHelper::cleanInput($_POST['home_stat3_num'] ?? ''),
            'home_stat3_lbl' => ValidationHelper::cleanInput($_POST['home_stat3_lbl'] ?? ''),
            'home_stat4_num' => ValidationHelper::cleanInput($_POST['home_stat4_num'] ?? ''),
            'home_stat4_lbl' => ValidationHelper::cleanInput($_POST['home_stat4_lbl'] ?? ''),
            'home_testimonials_badge' => ValidationHelper::cleanInput($_POST['home_testimonials_badge'] ?? ''),
            'home_testimonials_title' => ValidationHelper::cleanInput($_POST['home_testimonials_title'] ?? ''),
            'home_testimonial1_text' => ValidationHelper::cleanInput($_POST['home_testimonial1_text'] ?? ''),
            'home_testimonial1_author' => ValidationHelper::cleanInput($_POST['home_testimonial1_author'] ?? ''),
            'home_testimonial1_role' => ValidationHelper::cleanInput($_POST['home_testimonial1_role'] ?? ''),
            'home_testimonial2_text' => ValidationHelper::cleanInput($_POST['home_testimonial2_text'] ?? ''),
            'home_testimonial2_author' => ValidationHelper::cleanInput($_POST['home_testimonial2_author'] ?? ''),
            'home_testimonial2_role' => ValidationHelper::cleanInput($_POST['home_testimonial2_role'] ?? ''),
            'home_testimonial_video_title' => ValidationHelper::cleanInput($_POST['home_testimonial_video_title'] ?? ''),
            'home_testimonial_video_youtube_id' => ValidationHelper::cleanInput($_POST['home_testimonial_video_youtube_id'] ?? ''),
            'home_services_badge' => ValidationHelper::cleanInput($_POST['home_services_badge'] ?? ''),
            'home_services_title' => ValidationHelper::cleanInput($_POST['home_services_title'] ?? ''),
            'home_service1_icon' => ValidationHelper::cleanInput($_POST['home_service1_icon'] ?? ''),
            'home_service1_title' => ValidationHelper::cleanInput($_POST['home_service1_title'] ?? ''),
            'home_service1_desc' => ValidationHelper::cleanInput($_POST['home_service1_desc'] ?? ''),
            'home_service2_icon' => ValidationHelper::cleanInput($_POST['home_service2_icon'] ?? ''),
            'home_service2_title' => ValidationHelper::cleanInput($_POST['home_service2_title'] ?? ''),
            'home_service2_desc' => ValidationHelper::cleanInput($_POST['home_service2_desc'] ?? ''),
            'home_service3_icon' => ValidationHelper::cleanInput($_POST['home_service3_icon'] ?? ''),
            'home_service3_title' => ValidationHelper::cleanInput($_POST['home_service3_title'] ?? ''),
            'home_service3_desc' => ValidationHelper::cleanInput($_POST['home_service3_desc'] ?? ''),
            'home_faq_badge' => ValidationHelper::cleanInput($_POST['home_faq_badge'] ?? ''),
            'home_faq_title' => ValidationHelper::cleanInput($_POST['home_faq_title'] ?? ''),
            'home_faq1_q' => ValidationHelper::cleanInput($_POST['home_faq1_q'] ?? ''),
            'home_faq1_a' => ValidationHelper::cleanInput($_POST['home_faq1_a'] ?? ''),
            'home_faq2_q' => ValidationHelper::cleanInput($_POST['home_faq2_q'] ?? ''),
            'home_faq2_a' => ValidationHelper::cleanInput($_POST['home_faq2_a'] ?? ''),
            'home_faq3_q' => ValidationHelper::cleanInput($_POST['home_faq3_q'] ?? ''),
            'home_faq3_a' => ValidationHelper::cleanInput($_POST['home_faq3_a'] ?? ''),
            'home_awards_badge' => ValidationHelper::cleanInput($_POST['home_awards_badge'] ?? ''),
            'home_awards_title' => ValidationHelper::cleanInput($_POST['home_awards_title'] ?? ''),
            'home_award1_icon' => ValidationHelper::cleanInput($_POST['home_award1_icon'] ?? ''),
            'home_award1_title' => ValidationHelper::cleanInput($_POST['home_award1_title'] ?? ''),
            'home_award1_text' => ValidationHelper::cleanInput($_POST['home_award1_text'] ?? ''),
            'home_award2_icon' => ValidationHelper::cleanInput($_POST['home_award2_icon'] ?? ''),
            'home_award2_title' => ValidationHelper::cleanInput($_POST['home_award2_title'] ?? ''),
            'home_award2_text' => ValidationHelper::cleanInput($_POST['home_award2_text'] ?? ''),
            'home_award3_icon' => ValidationHelper::cleanInput($_POST['home_award3_icon'] ?? ''),
            'home_award3_title' => ValidationHelper::cleanInput($_POST['home_award3_title'] ?? ''),
            'home_award3_text' => ValidationHelper::cleanInput($_POST['home_award3_text'] ?? ''),
            'home_award4_icon' => ValidationHelper::cleanInput($_POST['home_award4_icon'] ?? ''),
            'home_award4_title' => ValidationHelper::cleanInput($_POST['home_award4_title'] ?? ''),
            'home_award4_text' => ValidationHelper::cleanInput($_POST['home_award4_text'] ?? ''),
            'home_cta_badge' => ValidationHelper::cleanInput($_POST['home_cta_badge'] ?? ''),
            'home_cta_title' => ValidationHelper::cleanInput($_POST['home_cta_title'] ?? ''),
            'home_cta_desc' => ValidationHelper::cleanInput($_POST['home_cta_desc'] ?? ''),
            'home_cta_video_url' => ValidationHelper::cleanInput($_POST['home_cta_video_url'] ?? ''),

            // About page
            'about_hero_title' => ValidationHelper::cleanInput($_POST['about_hero_title'] ?? ''),
            'about_hero_desc' => ValidationHelper::cleanInput($_POST['about_hero_desc'] ?? ''),
            'about_identity_badge' => ValidationHelper::cleanInput($_POST['about_identity_badge'] ?? ''),
            'about_identity_title' => ValidationHelper::cleanInput($_POST['about_identity_title'] ?? ''),
            'about_identity_desc1' => ValidationHelper::cleanInput($_POST['about_identity_desc1'] ?? ''),
            'about_identity_desc2' => ValidationHelper::cleanInput($_POST['about_identity_desc2'] ?? ''),
            'about_identity_card1_icon' => ValidationHelper::cleanInput($_POST['about_identity_card1_icon'] ?? ''),
            'about_identity_card1_title' => ValidationHelper::cleanInput($_POST['about_identity_card1_title'] ?? ''),
            'about_identity_card1_text' => ValidationHelper::cleanInput($_POST['about_identity_card1_text'] ?? ''),
            'about_identity_card2_icon' => ValidationHelper::cleanInput($_POST['about_identity_card2_icon'] ?? ''),
            'about_identity_card2_title' => ValidationHelper::cleanInput($_POST['about_identity_card2_title'] ?? ''),
            'about_identity_card2_text' => ValidationHelper::cleanInput($_POST['about_identity_card2_text'] ?? ''),
            'about_leadership_badge' => ValidationHelper::cleanInput($_POST['about_leadership_badge'] ?? ''),
            'about_leadership_title' => ValidationHelper::cleanInput($_POST['about_leadership_title'] ?? ''),
            'about_team1_name' => ValidationHelper::cleanInput($_POST['about_team1_name'] ?? ''),
            'about_team1_role' => ValidationHelper::cleanInput($_POST['about_team1_role'] ?? ''),
            'about_team2_name' => ValidationHelper::cleanInput($_POST['about_team2_name'] ?? ''),
            'about_team2_role' => ValidationHelper::cleanInput($_POST['about_team2_role'] ?? ''),
            'about_team3_name' => ValidationHelper::cleanInput($_POST['about_team3_name'] ?? ''),
            'about_team3_role' => ValidationHelper::cleanInput($_POST['about_team3_role'] ?? ''),

            // Contact page
            'contact_hero_badge' => ValidationHelper::cleanInput($_POST['contact_hero_badge'] ?? ''),
            'contact_hero_title' => ValidationHelper::cleanInput($_POST['contact_hero_title'] ?? ''),
            'contact_hero_desc' => ValidationHelper::cleanInput($_POST['contact_hero_desc'] ?? ''),
            'contact_channels_badge' => ValidationHelper::cleanInput($_POST['contact_channels_badge'] ?? ''),
            'contact_channels_title' => ValidationHelper::cleanInput($_POST['contact_channels_title'] ?? ''),
            'contact_channels_desc' => ValidationHelper::cleanInput($_POST['contact_channels_desc'] ?? ''),
            'contact_business_hours' => ValidationHelper::cleanInput($_POST['contact_business_hours'] ?? ''),
            'contact_form_badge' => ValidationHelper::cleanInput($_POST['contact_form_badge'] ?? ''),
            'contact_form_title' => ValidationHelper::cleanInput($_POST['contact_form_title'] ?? '')
        ];

        // Process File Uploads for Page Images
        $errors = [];
        $targetDir = ROOT_DIR . 'uploads/pages/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $imageFields = [
            'home_overview_image',
            'home_testimonial_video_image',
            'about_hero_image',
            'about_team1_image',
            'about_team2_image',
            'about_team3_image',
            'contact_hero_image'
        ];

        foreach ($imageFields as $field) {
            if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
                    $fileName = $field . '_' . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetDir . $fileName)) {
                        $pagesData[$field] = 'uploads/pages/' . $fileName;
                    } else {
                        $errors[] = "Failed to move uploaded file for {$field}.";
                    }
                } else {
                    $errors[] = "Invalid file extension for field " . str_replace('_', ' ', $field) . ". Only PNG, JPG, JPEG, SVG and WEBP are allowed.";
                }
            } else {
                // Keep the current setting if no new file is uploaded
                if (isset($currentSettings[$field])) {
                    $pagesData[$field] = $currentSettings[$field];
                } else {
                    $pagesData[$field] = '';
                }
            }
        }

        if (empty($errors)) {
            $result = Setting::updateAll($pagesData);
            if ($result) {
                $_SESSION['pages_success'] = "Pages content updated successfully.";
            } else {
                $_SESSION['pages_error'] = "Failed to update page contents in the database.";
            }
        } else {
            $_SESSION['pages_error'] = implode('<br>', $errors);
        }

        header("Location: " . BASE_URL . "admin/pages");
        exit;
    }

    /**
     * Enquiries list panel
     */
    public function enquiries(): void {
        AuthHelper::requireLogin();

        $search = $_GET['search'] ?? '';
        $filters = ['search' => ValidationHelper::cleanInput($search)];

        // Pagination
        $page = (int)($_GET['page'] ?? 1);
        if ($page < 1) $page = 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $totalEnquiries = Enquiry::countFiltered($filters);
        $enquiries = Enquiry::getFiltered($filters, $limit, $offset);

        $totalPages = ceil($totalEnquiries / $limit);
        if ($totalPages < 1) $totalPages = 1;

        $this->render('admin/enquiries', [
            'pageTitle' => 'Customer Enquiries',
            'enquiries' => $enquiries,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalEnquiries' => $totalEnquiries
        ], 'admin');
    }

    /**
     * Mark an enquiry read via AJAX POST
     */
    public function markRead(): void {
        AuthHelper::requireLogin();
        CSRFHelper::verifyPost();

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $result = Enquiry::markAsRead($id);
            if ($result) {
                $this->json(['success' => true, 'message' => 'Enquiry marked as read.']);
            }
        }
        $this->json(['success' => false, 'message' => 'Failed to update enquiry status.'], 400);
    }

    /**
     * Send email reply to an enquiry via AJAX POST
     */
    public function replyEnquiry(): void {
        AuthHelper::requireLogin();
        CSRFHelper::verifyPost();

        $id = (int)($_POST['id'] ?? 0);
        $subject = ValidationHelper::cleanInput($_POST['subject'] ?? '');
        $message = ValidationHelper::cleanInput($_POST['message'] ?? '');

        if ($id <= 0 || empty($subject) || empty($message)) {
            $this->json(['success' => false, 'message' => 'Invalid parameters. Please enter a subject and message.'], 400);
        }

        // Get enquiry details
        self::initDB();
        $stmt = self::$db->prepare("SELECT name, email FROM enquiries WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $enquiry = $stmt->fetch();

        if (!$enquiry) {
            $this->json(['success' => false, 'message' => 'Enquiry not found.'], 404);
        }

        // Retrieve settings for SMTP/From email info
        $settings = Setting::getAll();

        // Send email
        $sent = \App\Helpers\MailHelper::send($enquiry['email'], $subject, $message, $settings);

        if ($sent) {
            // Also automatically mark as read
            Enquiry::markAsRead($id);
            $this->json(['success' => true, 'message' => 'Your reply has been successfully sent to the client!']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to send reply email.'], 500);
        }
    }

    /**
     * Delete an enquiry via AJAX POST
     */
    public function deleteEnquiry(): void {
        AuthHelper::requireLogin();
        CSRFHelper::verifyPost();

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $result = Enquiry::delete($id);
            if ($result) {
                $this->json(['success' => true, 'message' => 'Enquiry deleted successfully.']);
            }
        }
        $this->json(['success' => false, 'message' => 'Failed to delete enquiry.'], 400);
    }

    /**
     * Export enquiries to CSV download
     */
    public function exportEnquiries(): void {
        AuthHelper::requireLogin();
        
        $filters = ['search' => ValidationHelper::cleanInput($_GET['search'] ?? '')];
        $enquiries = Enquiry::getFiltered($filters, 10000, 0); // retrieve up to 10k items

        // Set headers for download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=enquiries_' . date('Y-m-d_H-i-s') . '.csv');

        $output = fopen('php://output', 'w');
        
        // Add column headers
        fputcsv($output, ['ID', 'Property Title', 'Name', 'Email', 'Phone', 'Message', 'Status', 'Submitted At']);

        foreach ($enquiries as $enquiry) {
            fputcsv($output, [
                $enquiry['id'],
                $enquiry['property_title'] ?? 'General Contact',
                $enquiry['name'],
                $enquiry['email'],
                $enquiry['phone'] ?? '',
                $enquiry['message'],
                $enquiry['is_read'] ? 'Read' : 'Unread',
                $enquiry['created_at']
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Media Library view page
     */
    public function mediaLibrary(): void {
        AuthHelper::requireLogin();

        $search = $_GET['search'] ?? '';
        $mediaFiles = [];
        $uploadRoot = ROOT_DIR . 'uploads';

        if (is_dir($uploadRoot)) {
            $directory = new \RecursiveDirectoryIterator($uploadRoot);
            $iterator = new \RecursiveIteratorIterator($directory);
            foreach ($iterator as $info) {
                if ($info->isFile()) {
                    $fileName = $info->getFilename();
                    // Skip hidden files
                    if (str_starts_with($fileName, '.')) {
                        continue;
                    }
                    
                    $filePath = str_replace('\\', '/', $info->getRealPath());
                    $relativePath = str_replace(str_replace('\\', '/', ROOT_DIR), '', $filePath);
                    
                    // Filter by search keyword if present
                    if (!empty($search) && !str_contains(strtolower($fileName), strtolower($search))) {
                        continue;
                    }

                    $ext = strtolower($info->getExtension());
                    $type = 'other';
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                        $type = 'image';
                    } elseif ($ext === 'pdf') {
                        $type = 'pdf';
                    } elseif (in_array($ext, ['mp4', 'mov', 'webm', 'ogg'])) {
                        $type = 'video';
                    }

                    $mediaFiles[] = [
                        'name' => $fileName,
                        'relative_path' => $relativePath,
                        'url' => BASE_URL . $relativePath,
                        'size' => $info->getSize(),
                        'type' => $type,
                        'date' => date('Y-m-d H:i:s', $info->getMTime())
                    ];
                }
            }
        }

        // Sort media files: newest first
        usort($mediaFiles, function ($a, $b) {
            return strcmp($b['date'], $a['date']);
        });

        $this->render('admin/media', [
            'pageTitle' => 'Media Library',
            'mediaFiles' => $mediaFiles,
            'search' => $search
        ], 'admin');
    }

    /**
     * AJAX endpoint: Delete media file
     */
    public function deleteMedia(): void {
        AuthHelper::requireLogin();
        CSRFHelper::verifyPost();

        $filePath = $_POST['file_path'] ?? '';
        $cleanPath = trim(str_replace(['..', '\\'], ['', '/'], $filePath), '/');

        // Security check: Only allow deleting files inside the uploads/ folder
        if (!str_starts_with($cleanPath, 'uploads/')) {
            $this->json(['success' => false, 'message' => 'Unauthorized path.'], 403);
        }

        $fullPath = ROOT_DIR . $cleanPath;
        if (file_exists($fullPath) && is_file($fullPath)) {
            if (unlink($fullPath)) {
                // If it is a property image, let's also remove its row from property_images database table!
                self::initDB();
                $stmt = self::$db->prepare("DELETE FROM property_images WHERE image_path = :path");
                $stmt->execute(['path' => $cleanPath]);

                $this->json(['success' => true, 'message' => 'File deleted successfully.']);
            }
        }

        $this->json(['success' => false, 'message' => 'File not found or failed to delete.'], 400);
    }
}

