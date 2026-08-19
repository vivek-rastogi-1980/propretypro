<?php
namespace App\Models;

use PDO;
use PDOException;

/**
 * Base Abstract Model with Singleton PDO instance
 */
abstract class Model {
    protected static ?PDO $db = null;

    public function __construct() {
        self::initDB();
    }

    /**
     * Initialize connection with security options
     */
    protected static function initDB(): void {
        if (self::$db === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false, // Disabling emulation enforces real prepared statements (SQLi protection)
                ];

                self::$db = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Return a clean message
                throw new PDOException("Database connection error: " . $e->getMessage(), (int)$e->getCode());
            }
        }
    }
}
