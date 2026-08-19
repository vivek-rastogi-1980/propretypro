<?php
namespace App\Models;

/**
 * Website Configuration Settings Model
 */
class Setting extends Model {

    /**
     * Fetch all configuration parameters as key-value pairs
     */
    public static function getAll(): array {
        self::initDB();
        
        $stmt = self::$db->query("SELECT `key`, `value` FROM settings");
        $results = $stmt->fetchAll();

        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = $row['value'];
        }

        return $settings;
    }

    /**
     * Get a single configuration key value
     */
    public static function get(string $key, ?string $default = null): ?string {
        self::initDB();
        
        $stmt = self::$db->prepare("SELECT `value` FROM settings WHERE `key` = :key LIMIT 1");
        $stmt->execute(['key' => $key]);
        $value = $stmt->fetchColumn();
        
        return $value !== false ? $value : $default;
    }

    /**
     * Update multiple setting key-value pairs
     */
    public static function updateAll(array $settingsArray): bool {
        self::initDB();
        
        self::$db->beginTransaction();
        try {
            $stmt = self::$db->prepare("
                INSERT INTO settings (`key`, `value`) 
                VALUES (:key, :value) 
                ON DUPLICATE KEY UPDATE `value` = :update_value
            ");

            foreach ($settingsArray as $key => $value) {
                $stmt->execute([
                    'key' => $key,
                    'value' => $value,
                    'update_value' => $value
                ]);
            }

            self::$db->commit();
            return true;
        } catch (\Throwable $e) {
            self::$db->rollBack();
            return false;
        }
    }
}
