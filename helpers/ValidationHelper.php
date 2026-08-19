<?php
namespace App\Helpers;

/**
 * Request Validation, XSS Protection and Input Sanitization Helper
 */
class ValidationHelper {
    
    /**
     * Sanitize string for output, protecting against XSS
     */
    public static function sanitize(mixed $data): mixed {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
            return $data;
        }

        if (is_string($data)) {
            return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }

        return $data;
    }

    /**
     * Strips all tags for database insert where raw HTML is unacceptable
     */
    public static function cleanInput(string $data): string {
        return trim(strip_tags($data));
    }

    /**
     * Validate email format
     */
    public static function isValidEmail(string $email): bool {
        return (bool) filter_var(trim($email), FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check if fields are empty
     */
    public static function validateRequired(array $data, array $requiredFields): array {
        $errors = [];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
                // Convert snake_case to readable word
                $fieldName = ucwords(str_replace('_', ' ', $field));
                $errors[$field] = "{$fieldName} is required.";
            }
        }
        return $errors;
    }

    /**
     * Generate URL-friendly SEO slug
     */
    public static function slugify(string $text): string {
        // Replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // Trim
        $text = trim($text, '-');

        // Remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // Lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
