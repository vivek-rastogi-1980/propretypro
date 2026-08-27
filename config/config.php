<?php
/**
 * Application Configurations
 */

// Database configuration


define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'u288997229_propertypro');
define('DB_USER', 'u288997229_propertypro');
define('DB_PASS', 'Vivek@1980!');

/*
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'real_estate_db');
define('DB_USER', 'root');
define('DB_PASS', '');
*/
// Base URL Auto-Detection
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$baseDir = str_replace('\\', '/', dirname($scriptName));

// Ensure base dir trailing slash
if ($baseDir !== '/') {
    $baseDir = rtrim($baseDir, '/') . '/';
} else {
    $baseDir = '/';
}

define('BASE_URL', $protocol . $domainName . $baseDir);
define('BASE_PATH', $baseDir);
define('ROOT_DIR', dirname(__DIR__) . '/');

// Security & Session Configs
define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_KEY', 'csrf_token');

// Default SEO configurations (used if settings database is empty)
define('DEFAULT_SEO_TITLE', 'LuxeHaven Estates | Premium Real Estate Portal');
define('DEFAULT_SEO_DESC', 'Discover premium luxury apartments, villas, commercial office spaces, and land plots for sale and rent.');
define('DEFAULT_SEO_KEYWORDS', 'real estate, buy apartment, luxury villas, rent office space, independent house, land plot');

// Writable uploads path
define('UPLOAD_DIR', ROOT_DIR . 'uploads/properties/');
define('UPLOAD_URL', BASE_URL . 'uploads/properties/');
define('MAX_IMAGE_COUNT', 20);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB per image
