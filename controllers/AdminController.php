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

