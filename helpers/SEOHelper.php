<?php
namespace App\Helpers;

use App\Models\Setting;

/**
 * Dynamic SEO Meta Tag Generation Helper
 */
class SEOHelper {
    private static ?array $cachedSettings = null;

    /**
     * Lazy load settings table configurations
     */
    private static function init(): void {
        if (self::$cachedSettings === null) {
            try {
                self::$cachedSettings = Setting::getAll();
            } catch (\Throwable $e) {
                self::$cachedSettings = [];
            }
        }
    }

    /**
     * Get HTML formatted dynamic Title
     */
    public static function getTitle(?string $customTitle = null): string {
        self::init();
        $companyName = self::$cachedSettings['company_name'] ?? 'LuxeHaven Estates';
        $siteTitle = self::$cachedSettings['seo_title'] ?? DEFAULT_SEO_TITLE;

        if ($customTitle) {
            return htmlspecialchars($customTitle . ' | ' . $companyName);
        }
        return htmlspecialchars($siteTitle);
    }

    /**
     * Get Meta Description
     */
    public static function getDescription(?string $customDesc = null): string {
        self::init();
        $desc = $customDesc ?: (self::$cachedSettings['seo_meta_description'] ?? DEFAULT_SEO_DESC);
        // Clean double quotes and limit characters for neatness
        $desc = str_replace('"', '&quot;', strip_tags($desc));
        if (strlen($desc) > 160) {
            $desc = substr($desc, 0, 157) . '...';
        }
        return htmlspecialchars($desc);
    }

    /**
     * Get Meta Keywords
     */
    public static function getKeywords(?string $customKeywords = null): string {
        self::init();
        $keywords = $customKeywords ?: (self::$cachedSettings['seo_meta_keywords'] ?? DEFAULT_SEO_KEYWORDS);
        return htmlspecialchars(strip_tags($keywords));
    }

    /**
     * Get Open Graph Image URL
     */
    public static function getOgImage(?string $customImage = null): string {
        self::init();
        if ($customImage) {
            return htmlspecialchars($customImage);
        }
        $ogImage = self::$cachedSettings['seo_og_image'] ?? '';
        if ($ogImage) {
            return htmlspecialchars(BASE_URL . $ogImage);
        }
        // Fallback placeholder image URL or logo
        $logo = self::$cachedSettings['company_logo'] ?? '';
        if ($logo) {
            return htmlspecialchars(BASE_URL . $logo);
        }
        return htmlspecialchars(BASE_URL . 'assets/images/default-share.jpg');
    }
}
