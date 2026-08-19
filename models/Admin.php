<?php
namespace App\Models;

use PDO;

/**
 * Admin Model
 */
class Admin extends Model {

    /**
     * Authenticate Admin credentials
     */
    public static function authenticate(string $usernameOrEmail, string $password): ?array {
        self::initDB();
        
        $stmt = self::$db->prepare("
            SELECT * FROM admins 
            WHERE username = :user OR email = :email 
            LIMIT 1
        ");
        
        $stmt->execute([
            'user' => $usernameOrEmail,
            'email' => $usernameOrEmail
        ]);

        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            // Remove password from memory array
            unset($admin['password']);
            return $admin;
        }

        return null;
    }

    /**
     * Set reset password token
     */
    public static function setResetToken(string $email, string $token): bool {
        self::initDB();
        
        $expire = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        
        $stmt = self::$db->prepare("
            UPDATE admins 
            SET reset_token = :token, reset_token_expire = :expire 
            WHERE email = :email
        ");
        
        return $stmt->execute([
            'token' => $token,
            'expire' => $expire,
            'email' => $email
        ]);
    }

    /**
     * Verify token validity
     */
    public static function verifyResetToken(string $token): ?array {
        self::initDB();
        
        $stmt = self::$db->prepare("
            SELECT * FROM admins 
            WHERE reset_token = :token 
              AND reset_token_expire > NOW() 
            LIMIT 1
        ");
        
        $stmt->execute(['token' => $token]);
        $admin = $stmt->fetch();
        
        return $admin ?: null;
    }

    /**
     * Update Admin Password
     */
    public static function updatePassword(int $id, string $newPassword): bool {
        self::initDB();
        
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        
        $stmt = self::$db->prepare("
            UPDATE admins 
            SET password = :pass, reset_token = NULL, reset_token_expire = NULL 
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'pass' => $hash,
            'id' => $id
        ]);
    }
}
