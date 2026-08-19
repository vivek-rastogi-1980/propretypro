<?php
namespace App\Models;

use PDO;

/**
 * Enquiry Model handles visitor contact/property enquiries
 */
class Enquiry extends Model {

    /**
     * Submit an enquiry
     */
    public static function create(array $data): bool {
        self::initDB();
        $stmt = self::$db->prepare("
            INSERT INTO enquiries (property_id, name, email, phone, message) 
            VALUES (:property_id, :name, :email, :phone, :message)
        ");
        return $stmt->execute([
            'property_id' => $data['property_id'] ?: null,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?: null,
            'message' => $data['message']
        ]);
    }

    /**
     * Fetch list of enquiries (with optional property title JOIN)
     */
    public static function getFiltered(array $filters, int $limit = 10, int $offset = 0): array {
        self::initDB();
        
        $sql = "
            SELECT e.*, p.title as property_title, p.slug as property_slug 
            FROM enquiries e
            LEFT JOIN properties p ON e.property_id = p.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (e.name LIKE :search OR e.email LIKE :search OR e.phone LIKE :search OR e.message LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY e.id DESC LIMIT :limit OFFSET :offset";

        $stmt = self::$db->prepare($sql);
        
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Total enquiries count matching search
     */
    public static function countFiltered(array $filters): int {
        self::initDB();

        $sql = "SELECT COUNT(*) FROM enquiries e WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (e.name LIKE :search OR e.email LIKE :search OR e.phone LIKE :search OR e.message LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Mark an enquiry as read
     */
    public static function markAsRead(int $id): bool {
        self::initDB();
        $stmt = self::$db->prepare("UPDATE enquiries SET is_read = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Delete an enquiry
     */
    public static function delete(int $id): bool {
        self::initDB();
        $stmt = self::$db->prepare("DELETE FROM enquiries WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Count unread enquiries for admin dashboard notification badge
     */
    public static function getUnreadCount(): int {
        self::initDB();
        return (int)self::$db->query("SELECT COUNT(*) FROM enquiries WHERE is_read = 0")->fetchColumn();
    }
}
