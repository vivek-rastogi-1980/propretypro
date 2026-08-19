<?php
namespace App\Models;

/**
 * Category Model
 */
class Category extends Model {

    /**
     * Get all categories
     */
    public static function getAll(): array {
        self::initDB();
        $stmt = self::$db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Find category by ID
     */
    public static function find(int $id): ?array {
        self::initDB();
        $stmt = self::$db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();
        return $category ?: null;
    }
}
