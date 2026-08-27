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

                // Auto-migrate: Add missing slider columns to properties table if needed
                try {
                    $check = self::$db->query("SHOW COLUMNS FROM `properties` LIKE 'slider_image'");
                    if ($check && !$check->fetch()) {
                        self::$db->exec("ALTER TABLE `properties` ADD COLUMN `in_slider` TINYINT(1) NOT NULL DEFAULT 0, ADD COLUMN `slider_image` VARCHAR(255) NULL");
                    }
                } catch (\Throwable $migrationError) {
                    // Ignore migration check if table doesn't exist yet
                }
            } catch (PDOException $e) {
                // Return a clean message
                throw new PDOException("Database connection error: " . $e->getMessage(), (int)$e->getCode());
            }
        }
    }
}
