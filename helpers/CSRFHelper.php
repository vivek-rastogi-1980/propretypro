<?php
namespace App\Helpers;

/**
 * Cross-Site Request Forgery (CSRF) Prevention Helper
 */
class CSRFHelper {
    
    /**
     * Start session if needed
     */
    private static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Generate or fetch CSRF token
     */
    public static function generateToken(): string {
        self::initSession();
        if (empty($_SESSION[CSRF_TOKEN_KEY])) {
            $_SESSION[CSRF_TOKEN_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_KEY];
    }

    /**
     * Validate token from POST/GET parameter
     */
    public static function validateToken(?string $token): bool {
        self::initSession();
        if (!$token || empty($_SESSION[CSRF_TOKEN_KEY])) {
            return false;
        }
        return hash_equals($_SESSION[CSRF_TOKEN_KEY], $token);
    }

    public static function getField(): string {
        $token = self::generateToken();
        return '<input type="hidden" name="' . CSRF_TOKEN_KEY . '" value="' . htmlspecialchars($token) . '">';
    }

    /**
     * Get HTML input field for form inclusion (Alias for getField)
     */
    public static function getTokenField(): string {
        return self::getField();
    }


    /**
     * Automatic POST verification, throws exception if invalid
     */
    public static function verifyPost(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST[CSRF_TOKEN_KEY] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!self::validateToken($token)) {
                header("HTTP/1.0 403 Forbidden");
                echo '<h1>403 Forbidden</h1><p>CSRF verification failed. Request blocked.</p>';
                exit;
            }
        }
    }
}
