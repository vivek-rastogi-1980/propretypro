<?php
namespace App\Helpers;

/**
 * Authentication and Security Helper
 */
class AuthHelper {
    
    /**
     * Start user session if not already started
     */
    private static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Check if admin is currently authenticated
     */
    public static function isLoggedIn(): bool {
        self::initSession();
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    /**
     * Enforce login restriction
     */
    public static function requireLogin(): void {
        if (!self::isLoggedIn()) {
            // Store redirect URL if needed
            header("Location: " . BASE_URL . "admin/login");
            exit;
        }
    }

    /**
     * Log the admin in and store details
     */
    public static function login(array $adminData): void {
        self::initSession();
        // Prevent session fixation
        session_regenerate_id(true);
        
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $adminData['id'];
        $_SESSION['admin_username'] = $adminData['username'];
        $_SESSION['admin_email'] = $adminData['email'];
        $_SESSION['last_activity'] = time();
    }

    /**
     * Log the admin out
     */
    public static function logout(): void {
        self::initSession();
        
        // Unset session variables
        $_SESSION = [];

        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), 
                '', 
                time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();
    }
}
