<?php
/**
 * Single Entry Point
 */

// Load Configurations
require_once __DIR__ . '/config/config.php';

// PSR-4 Autoloading Fallback
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    spl_autoload_register(function ($class) {
        $nsMap = [
            'App\\Controllers\\' => 'controllers/',
            'App\\Models\\' => 'models/',
            'App\\Helpers\\' => 'helpers/',
            'App\\Routes\\' => 'routes/'
        ];

        foreach ($nsMap as $prefix => $dir) {
            if (str_starts_with($class, $prefix)) {
                $relativeClass = substr($class, strlen($prefix));
                $file = ROOT_DIR . $dir . str_replace('\\', '/', $relativeClass) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        }
    });
}

// Secure session initiation
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => SESSION_LIFETIME,
    'path' => BASE_PATH,
    'domain' => $_SERVER['HTTP_HOST'] ?? '',
    'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Regenerate session ID periodically to prevent fixation
if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Instantiate Router
$router = new App\Routes\Router();

// Define Web Routes

// Frontend Routes
$router->get('', 'HomeController@index');
$router->get('about', 'HomeController@about');
$router->get('contact', 'HomeController@contact');
$router->post('contact/submit', 'HomeController@submitEnquiry');

$router->get('properties', 'PropertyController@index');
$router->get('property/{slug}', 'PropertyController@detail');
$router->post('property/{slug}/enquiry', 'PropertyController@submitPropertyEnquiry');

// Admin Auth Routes
$router->get('admin/login', 'AdminController@login');
$router->post('admin/login', 'AdminController@postLogin');
$router->get('admin/logout', 'AdminController@logout');
$router->get('admin/forgot-password', 'AdminController@forgotPassword');
$router->post('admin/forgot-password', 'AdminController@postForgotPassword');

// Admin Panel Protected Routes
$router->get('admin', 'AdminController@dashboard');
$router->get('admin/dashboard', 'AdminController@dashboard');
$router->get('admin/settings', 'AdminController@settings');
$router->post('admin/settings', 'AdminController@saveSettings');
$router->get('admin/pages', 'AdminController@pages');
$router->post('admin/pages', 'AdminController@savePages');
$router->get('admin/enquiries', 'AdminController@enquiries');
$router->get('admin/enquiries/export', 'AdminController@exportEnquiries');
$router->post('admin/enquiries/delete', 'AdminController@deleteEnquiry');
$router->post('admin/enquiries/mark-read', 'AdminController@markRead');
$router->post('admin/enquiries/reply', 'AdminController@replyEnquiry');

// Admin Property CRUD
$router->get('admin/properties', 'AdminPropertyController@index');
$router->get('admin/properties/create', 'AdminPropertyController@create');
$router->post('admin/properties/create', 'AdminPropertyController@store');
$router->get('admin/properties/edit/{id}', 'AdminPropertyController@edit');
$router->post('admin/properties/edit/{id}', 'AdminPropertyController@update');
$router->post('admin/properties/delete/{id}', 'AdminPropertyController@delete');
$router->post('admin/properties/duplicate/{id}', 'AdminPropertyController@duplicate');
$router->post('admin/properties/delete-image', 'AdminPropertyController@deleteImage');
$router->post('admin/properties/set-featured-image', 'AdminPropertyController@setFeaturedImage');
$router->post('admin/properties/set-slider-image', 'AdminPropertyController@setSliderImage');

// Admin Media Library
$router->get('admin/media', 'AdminController@mediaLibrary');
$router->post('admin/media/delete', 'AdminController@deleteMedia');

// Dispatch Route
try {
    $router->dispatch();
} catch (\Throwable $e) {
    // Show full diagnostic error details to help with local deployment
    header("HTTP/1.0 500 Internal Server Error");
    echo '<div style="font-family:sans-serif; text-align:center; padding:50px; background:#F8FAFC; color:#0F172A; min-height:80vh; display:flex; flex-direction:column; align-items:center; justify-content:center;">';
    echo '<div style="max-width:800px; width:100%; background:rgba(255,255,255,0.7); backdrop-filter:blur(10px); border:1px solid rgba(0,0,0,0.08); border-radius:16px; padding:40px; box-shadow:0 10px 30px rgba(0,0,0,0.02); text-align:left;">';
    echo '<h2 style="color:#EF4444; margin-top:0;">500 Internal Server Error</h2>';
    echo '<p style="font-weight:600; color:#334155;">Detailed Exception Message:</p>';
    echo '<div style="background:#F1F5F9; color:#DC2626; padding:15px; border-radius:8px; font-family:monospace; border-left:4px solid #EF4444; margin-bottom:20px; font-size:14px; overflow-x:auto;">' . htmlspecialchars($e->getMessage()) . '</div>';
    echo '<p style="font-weight:600; color:#334155; margin-bottom:5px;">Stack Trace:</p>';
    echo '<pre style="background:#1E293B; color:#E2E8F0; padding:15px; border-radius:8px; font-size:12px; overflow-x:auto; max-height:300px; font-family:monospace;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '<p style="margin-top:20px; font-size:14px; color:#64748B;"><em>Verify that your MySQL server is running, the database has been imported using config/database.sql, and credentials match in config/config.php.</em></p>';
    echo '<div style="text-align:center; margin-top:30px;"><a href="' . BASE_URL . '" style="background:#2563EB; color:white; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600;">Retry Page Load</a></div>';
    echo '</div>';
    echo '</div>';
}
