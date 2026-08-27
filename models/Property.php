<?php
namespace App\Models;

use PDO;

/**
 * Property Model handles CRUD, Search, and Gallery mappings
 */
class Property extends Model {

    /**
     * Get a property by its slug (with category and image data)
     */
    public static function findBySlug(string $slug): ?array {
        self::initDB();
        $stmt = self::$db->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug
            FROM properties p
            JOIN categories c ON p.category_id = c.id
            WHERE p.slug = :slug AND p.is_published = 1
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        $property = $stmt->fetch();
        return $property ?: null;
    }

    /**
     * Get property by ID for CRUD management
     */
    public static function find(int $id): ?array {
        self::initDB();
        $stmt = self::$db->prepare("
            SELECT p.*, c.name as category_name
            FROM properties p
            JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
            LIMIT 1
        ");
        $stmt->execute(['id' => $id]);
        $property = $stmt->fetch();
        return $property ?: null;
    }

    /**
     * Retrieve featured property items for homepage
     */
    public static function getFeatured(int $limit = 6): array {
        self::initDB();
        $stmt = self::$db->prepare("
            SELECT p.*, c.name as category_name, 
                   COALESCE(
                       (SELECT image_path FROM property_images WHERE property_id = p.id AND is_featured = 1 LIMIT 1),
                       (SELECT image_path FROM property_images WHERE property_id = p.id ORDER BY id ASC LIMIT 1)
                   ) as image_path
            FROM properties p
            JOIN categories c ON p.category_id = c.id
            WHERE p.is_featured = 1 AND p.is_published = 1
            ORDER BY p.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retrieve latest property items for homepage
     */
    public static function getLatest(int $limit = 6): array {
        self::initDB();
        $stmt = self::$db->prepare("
            SELECT p.*, c.name as category_name, 
                   COALESCE(
                       (SELECT image_path FROM property_images WHERE property_id = p.id AND is_featured = 1 LIMIT 1),
                       (SELECT image_path FROM property_images WHERE property_id = p.id ORDER BY id ASC LIMIT 1)
                   ) as image_path
            FROM properties p
            JOIN categories c ON p.category_id = c.id
            WHERE p.is_published = 1
            ORDER BY p.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get related properties based on category and excluding current
     */
    public static function getRelated(int $categoryId, int $excludeId, int $limit = 3): array {
        self::initDB();
        $stmt = self::$db->prepare("
            SELECT p.*, c.name as category_name, 
                   COALESCE(
                       (SELECT image_path FROM property_images WHERE property_id = p.id AND is_featured = 1 LIMIT 1),
                       (SELECT image_path FROM property_images WHERE property_id = p.id ORDER BY id ASC LIMIT 1)
                   ) as image_path
            FROM properties p
            JOIN categories c ON p.category_id = c.id
            WHERE p.category_id = :catId AND p.id != :excId AND p.is_published = 1
            ORDER BY p.id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':catId', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':excId', $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Query count builder based on search arguments (for pagination)
     */
    public static function countFiltered(array $filters): int {
        self::initDB();
        list($sql, $params) = self::buildFilteredQuery("COUNT(p.id) as total", $filters);
        
        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }

    /**
     * Fetch paginated results matching specific user queries
     */
    public static function getFiltered(array $filters, int $limit = 9, int $offset = 0): array {
        self::initDB();
        
        $fields = "p.*, c.name as category_name, 
                   COALESCE(
                       (SELECT image_path FROM property_images WHERE property_id = p.id AND is_featured = 1 LIMIT 1),
                       (SELECT image_path FROM property_images WHERE property_id = p.id ORDER BY id ASC LIMIT 1)
                   ) as image_path";
                   
        list($sql, $params) = self::buildFilteredQuery($fields, $filters);
        
        // Append Ordering and Limits
        $sql .= " ORDER BY p.id DESC LIMIT :limit OFFSET :offset";
        
        $stmt = self::$db->prepare($sql);
        
        // Bind dynamic filters
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        
        // Bind pagination limits
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Internal: Construct dynamic filters query safely
     */
    private static function buildFilteredQuery(string $selectFields, array $filters): array {
        $sql = "SELECT {$selectFields} FROM properties p JOIN categories c ON p.category_id = c.id WHERE 1=1";
        $params = [];

        // Apply published only if not explicitly checking from admin
        if (!isset($filters['admin_view'])) {
            $sql .= " AND p.is_published = 1";
        }

        // 1. Text Search / Location Search
        if (!empty($filters['location'])) {
            $sql .= " AND (p.location LIKE :location OR p.title LIKE :location_title)";
            $params[':location'] = '%' . $filters['location'] . '%';
            $params[':location_title'] = '%' . $filters['location'] . '%';
        }

        // 2. Category Filter (can be slug or category_id)
        if (!empty($filters['category'])) {
            if (is_numeric($filters['category'])) {
                $sql .= " AND p.category_id = :category_id";
                $params[':category_id'] = (int)$filters['category'];
            } else {
                $sql .= " AND c.slug = :category_slug";
                $params[':category_slug'] = $filters['category'];
            }
        }

        // 3. Status Filter (For Sale / For Rent)
        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $filters['status'];
        }

        // 4. Budget Range Max
        if (!empty($filters['budget_max'])) {
            $sql .= " AND p.price <= :budget_max";
            $params[':budget_max'] = (float)$filters['budget_max'];
        }

        // 5. Bedrooms
        if (!empty($filters['bedrooms'])) {
            $sql .= " AND p.bedrooms >= :bedrooms";
            $params[':bedrooms'] = (int)$filters['bedrooms'];
        }

        // 6. Bathrooms
        if (!empty($filters['bathrooms'])) {
            $sql .= " AND p.bathrooms >= :bathrooms";
            $params[':bathrooms'] = (int)$filters['bathrooms'];
        }

        // 7. Area Size Min
        if (!empty($filters['area'])) {
            $sql .= " AND p.area >= :area";
            $params[':area'] = (float)$filters['area'];
        }

        // 8. Construction Status (Ready To Move / Under Construction)
        if (!empty($filters['construction_status'])) {
            $sql .= " AND p.construction_status = :construction_status";
            $params[':construction_status'] = $filters['construction_status'];
        }

        // 9. Availability Status (Available / Sold / Upcoming)
        if (!empty($filters['availability_status'])) {
            $sql .= " AND p.availability_status = :availability_status";
            $params[':availability_status'] = $filters['availability_status'];
        }

        // 10. RERA ID Filter
        if (!empty($filters['rera_number'])) {
            $sql .= " AND p.rera_number LIKE :rera_number";
            $params[':rera_number'] = '%' . $filters['rera_number'] . '%';
        }

        // 11. Keywords search (checks title, short description, description, location, RERA)
        if (!empty($filters['keywords'])) {
            $sql .= " AND (p.title LIKE :keywords OR p.short_description LIKE :keywords OR p.full_description LIKE :keywords OR p.location LIKE :keywords OR p.rera_number LIKE :keywords)";
            $params[':keywords'] = '%' . $filters['keywords'] . '%';
        }

        // 12. Featured Only
        if (isset($filters['featured']) && $filters['featured'] !== '') {
            $sql .= " AND p.is_featured = :is_featured";
            $params[':is_featured'] = (int)$filters['featured'];
        }

        return [$sql, $params];
    }
    /**
     * Insert new Property record
     */
    public static function create(array $data): int {
        self::initDB();
        
        $stmt = self::$db->prepare("
            INSERT INTO properties (
                title, slug, category_id, status, price, location, 
                bedrooms, bathrooms, area, short_description, 
                full_description, amenities, is_featured, is_published,
                rera_number, construction_status, availability_status, google_map_embed,
                pdf_brochure, floor_plans, meta_title, meta_description,
                meta_keywords, videos, three_sixty_tour_url, in_slider, slider_image
            ) VALUES (
                :title, :slug, :category_id, :status, :price, :location, 
                :bedrooms, :bathrooms, :area, :short_description, 
                :full_description, :amenities, :is_featured, :is_published,
                :rera_number, :construction_status, :availability_status, :google_map_embed,
                :pdf_brochure, :floor_plans, :meta_title, :meta_description,
                :meta_keywords, :videos, :three_sixty_tour_url, :in_slider, :slider_image
            )
        ");

        $stmt->execute([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'category_id' => $data['category_id'],
            'status' => $data['status'],
            'price' => $data['price'],
            'location' => $data['location'],
            'bedrooms' => $data['bedrooms'],
            'bathrooms' => $data['bathrooms'],
            'area' => $data['area'],
            'short_description' => $data['short_description'],
            'full_description' => $data['full_description'],
            'amenities' => $data['amenities'], // should be JSON string
            'is_featured' => $data['is_featured'] ?? 0,
            'is_published' => $data['is_published'] ?? 1,
            'rera_number' => $data['rera_number'] ?? null,
            'construction_status' => $data['construction_status'] ?? 'Ready To Move',
            'availability_status' => $data['availability_status'] ?? 'Available',
            'google_map_embed' => $data['google_map_embed'] ?? null,
            'pdf_brochure' => $data['pdf_brochure'] ?? null,
            'floor_plans' => $data['floor_plans'] ?? null, // JSON string or file path
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'videos' => $data['videos'] ?? null, // JSON string
            'three_sixty_tour_url' => $data['three_sixty_tour_url'] ?? null,
            'in_slider' => $data['in_slider'] ?? 0,
            'slider_image' => $data['slider_image'] ?? null
        ]);

        return (int)self::$db->lastInsertId();
    }

    /**
     * Update existing property
     */
    public static function update(int $id, array $data): bool {
        self::initDB();

        $stmt = self::$db->prepare("
            UPDATE properties SET
                title = :title,
                slug = :slug,
                category_id = :category_id,
                status = :status,
                price = :price,
                location = :location,
                bedrooms = :bedrooms,
                bathrooms = :bathrooms,
                area = :area,
                short_description = :short_description,
                full_description = :full_description,
                amenities = :amenities,
                is_featured = :is_featured,
                is_published = :is_published,
                rera_number = :rera_number,
                construction_status = :construction_status,
                availability_status = :availability_status,
                google_map_embed = :google_map_embed,
                pdf_brochure = :pdf_brochure,
                floor_plans = :floor_plans,
                meta_title = :meta_title,
                meta_description = :meta_description,
                meta_keywords = :meta_keywords,
                videos = :videos,
                three_sixty_tour_url = :three_sixty_tour_url,
                in_slider = :in_slider,
                slider_image = :slider_image
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'category_id' => $data['category_id'],
            'status' => $data['status'],
            'price' => $data['price'],
            'location' => $data['location'],
            'bedrooms' => $data['bedrooms'],
            'bathrooms' => $data['bathrooms'],
            'area' => $data['area'],
            'short_description' => $data['short_description'],
            'full_description' => $data['full_description'],
            'amenities' => $data['amenities'],
            'is_featured' => $data['is_featured'] ?? 0,
            'is_published' => $data['is_published'] ?? 1,
            'rera_number' => $data['rera_number'] ?? null,
            'construction_status' => $data['construction_status'] ?? 'Ready To Move',
            'availability_status' => $data['availability_status'] ?? 'Available',
            'google_map_embed' => $data['google_map_embed'] ?? null,
            'pdf_brochure' => $data['pdf_brochure'] ?? null,
            'floor_plans' => $data['floor_plans'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'videos' => $data['videos'] ?? null,
            'three_sixty_tour_url' => $data['three_sixty_tour_url'] ?? null,
            'in_slider' => $data['in_slider'] ?? 0,
            'slider_image' => $data['slider_image'] ?? null
        ]);
    }

    /**
     * Retrieve properties to display in the hero slideshow
     */
    public static function getSliderProperties(): array {
        self::initDB();
        $stmt = self::$db->prepare("
            SELECT p.*, c.name as category_name, 
                   COALESCE(
                       p.slider_image,
                       (SELECT image_path FROM property_images WHERE property_id = p.id AND is_featured = 1 LIMIT 1),
                       (SELECT image_path FROM property_images WHERE property_id = p.id ORDER BY id ASC LIMIT 1)
                   ) as image_path
            FROM properties p
            JOIN categories c ON p.category_id = c.id
            WHERE p.in_slider = 1 AND p.is_published = 1
            ORDER BY p.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /**
     * Complete deletion
     */
    public static function delete(int $id): bool {
        self::initDB();
        
        $stmt = self::$db->prepare("DELETE FROM properties WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Add gallery image path mapping
     */
    public static function addImages(int $propertyId, array $images): void {
        self::initDB();
        
        // If there are no images yet, mark the first one as featured
        $hasFeatured = self::hasFeaturedImage($propertyId);

        $stmt = self::$db->prepare("
            INSERT INTO property_images (property_id, image_path, is_featured) 
            VALUES (:property_id, :path, :is_featured)
        ");

        foreach ($images as $index => $path) {
            $isFeatured = (!$hasFeatured && $index === 0) ? 1 : 0;
            if ($isFeatured) {
                $hasFeatured = true;
            }
            $stmt->execute([
                'property_id' => $propertyId,
                'path' => $path['large'], // Store large path as primary database path
                'is_featured' => $isFeatured
            ]);
        }
    }

    /**
     * Check if property already has a featured image set
     */
    public static function hasFeaturedImage(int $propertyId): bool {
        self::initDB();
        $stmt = self::$db->prepare("SELECT COUNT(*) FROM property_images WHERE property_id = :id AND is_featured = 1");
        $stmt->execute(['id' => $propertyId]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Set a specific image as the featured thumbnail
     */
    public static function setFeaturedImage(int $propertyId, int $imageId): bool {
        self::initDB();
        self::$db->beginTransaction();
        try {
            // Unset current featured images
            $stmt1 = self::$db->prepare("UPDATE property_images SET is_featured = 0 WHERE property_id = :property_id");
            $stmt1->execute(['property_id' => $propertyId]);

            // Set new featured image
            $stmt2 = self::$db->prepare("UPDATE property_images SET is_featured = 1 WHERE id = :id AND property_id = :property_id");
            $stmt2->execute(['id' => $imageId, 'property_id' => $propertyId]);

            self::$db->commit();
            return true;
        } catch (\Throwable $e) {
            self::$db->rollBack();
            return false;
        }
    }

    /**
     * Set a specific image path as the slider image
     */
    public static function setSliderImage(int $propertyId, int $imageId): bool {
        self::initDB();
        // Fetch the image path first
        $stmt = self::$db->prepare("SELECT image_path FROM property_images WHERE id = :id AND property_id = :property_id");
        $stmt->execute(['id' => $imageId, 'property_id' => $propertyId]);
        $imagePath = $stmt->fetchColumn();
        
        if (!$imagePath) {
            return false;
        }

        // Update the slider_image path on the property record
        $stmt2 = self::$db->prepare("UPDATE properties SET slider_image = :image_path WHERE id = :id");
        return $stmt2->execute(['image_path' => $imagePath, 'id' => $propertyId]);
    }

    /**
     * Get image gallery list
     */
    public static function getImages(int $propertyId): array {
        self::initDB();
        $stmt = self::$db->prepare("SELECT * FROM property_images WHERE property_id = :id ORDER BY is_featured DESC, id ASC");
        $stmt->execute(['id' => $propertyId]);
        
        $images = $stmt->fetchAll();
        // Since we save both thumbnail and large, let's map them.
        // We know that `uploads/properties/{id}/name_large.jpg` matches `uploads/properties/{id}/name_thumb.jpg`.
        // Let's generate both dynamically from the database record `image_path`
        foreach ($images as $index => $img) {
            $largePath = $img['image_path'];
            $thumbPath = str_replace('_large.jpg', '_thumb.jpg', $largePath);
            $images[$index]['large_url'] = BASE_URL . $largePath;
            $images[$index]['thumb_url'] = BASE_URL . $thumbPath;
        }

        return $images;
    }

    /**
     * Delete single gallery image
     */
    public static function deleteImage(int $imageId): bool {
        self::initDB();
        
        // Find path to unlink actual file
        $stmt = self::$db->prepare("SELECT * FROM property_images WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $imageId]);
        $image = $stmt->fetch();

        if ($image) {
            $largePath = ROOT_DIR . $image['image_path'];
            $thumbPath = str_replace('_large.jpg', '_thumb.jpg', $largePath);
            
            if (file_exists($largePath)) unlink($largePath);
            if (file_exists($thumbPath)) unlink($thumbPath);

            $delStmt = self::$db->prepare("DELETE FROM property_images WHERE id = :id");
            $result = $delStmt->execute(['id' => $imageId]);

            // If the deleted image was featured, set another one as featured if possible
            if ($image['is_featured'] == 1) {
                $nextImgStmt = self::$db->prepare("SELECT id FROM property_images WHERE property_id = :pid LIMIT 1");
                $nextImgStmt->execute(['pid' => $image['property_id']]);
                $nextId = $nextImgStmt->fetchColumn();
                if ($nextId) {
                    $setFeaturedStmt = self::$db->prepare("UPDATE property_images SET is_featured = 1 WHERE id = :id");
                    $setFeaturedStmt->execute(['id' => $nextId]);
                }
            }
            return $result;
        }

        return false;
    }

    /**
     * Check if slug already exists (excluding current ID for updates)
     */
    public static function isSlugExists(string $slug, ?int $excludeId = null): bool {
        self::initDB();
        $sql = "SELECT COUNT(*) FROM properties WHERE slug = :slug";
        $params = ['slug' => $slug];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Duplicate a property and its files/database records
     */
    public static function duplicate(int $id): int {
        self::initDB();
        $property = self::find($id);
        if (!$property) {
            throw new \Exception("Property to duplicate not found.");
        }

        // Generate dynamic title and slug
        $newTitle = "Copy of " . $property['title'];
        $newSlug = $property['slug'] . '-copy';
        $counter = 1;
        while (self::isSlugExists($newSlug)) {
            $newSlug = $property['slug'] . '-copy-' . $counter;
            $counter++;
        }

        // Set state to Draft / Available
        $data = [
            'title' => $newTitle,
            'slug' => $newSlug,
            'category_id' => $property['category_id'],
            'status' => $property['status'],
            'price' => $property['price'],
            'location' => $property['location'],
            'bedrooms' => $property['bedrooms'],
            'bathrooms' => $property['bathrooms'],
            'area' => $property['area'],
            'short_description' => $property['short_description'],
            'full_description' => $property['full_description'],
            'amenities' => $property['amenities'],
            'is_featured' => 0, // Default to not featured
            'is_published' => 0, // Default to draft
            'rera_number' => $property['rera_number'],
            'construction_status' => $property['construction_status'],
            'availability_status' => $property['availability_status'],
            'google_map_embed' => $property['google_map_embed'],
            'pdf_brochure' => null, // Don't copy brochure path yet (or we can duplicate brochure physically)
            'floor_plans' => $property['floor_plans'],
            'meta_title' => $property['meta_title'],
            'meta_description' => $property['meta_description'],
            'meta_keywords' => $property['meta_keywords'],
            'videos' => $property['videos'],
            'three_sixty_tour_url' => $property['three_sixty_tour_url']
        ];

        // Create property record
        $newId = self::create($data);

        // Copy property files physically (images) and records in DB
        $images = self::getImages($id);
        if (!empty($images)) {
            $srcDir = UPLOAD_DIR . $id . '/';
            $destDir = UPLOAD_DIR . $newId . '/';
            if (is_dir($srcDir)) {
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                $stmt = self::$db->prepare("
                    INSERT INTO property_images (property_id, image_path, is_featured) 
                    VALUES (:property_id, :path, :is_featured)
                ");

                foreach ($images as $img) {
                    $oldLarge = ROOT_DIR . $img['image_path'];
                    $oldThumb = str_replace('_large.jpg', '_thumb.jpg', $oldLarge);
                    
                    $baseNameLarge = basename($oldLarge);
                    $baseNameThumb = basename($oldThumb);
                    
                    $newLargeName = str_replace('prop_' . $id, 'prop_' . $newId, $baseNameLarge);
                    $newThumbName = str_replace('prop_' . $id, 'prop_' . $newId, $baseNameThumb);
                    
                    $newLargePath = $destDir . $newLargeName;
                    $newThumbPath = $destDir . $newThumbName;
                    
                    if (file_exists($oldLarge)) copy($oldLarge, $newLargePath);
                    if (file_exists($oldThumb)) copy($oldThumb, $newThumbPath);
                    
                    $stmt->execute([
                        'property_id' => $newId,
                        'path' => 'uploads/properties/' . $newId . '/' . $newLargeName,
                        'is_featured' => $img['is_featured']
                    ]);
                }
            }
        }

        // Copy PDF brochure physically if exists
        if (!empty($property['pdf_brochure'])) {
            $oldBrochure = ROOT_DIR . $property['pdf_brochure'];
            if (file_exists($oldBrochure)) {
                $destDir = UPLOAD_DIR . $newId . '/';
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                $newBrochureName = 'brochure_' . uniqid() . '.pdf';
                $newBrochurePath = $destDir . $newBrochureName;
                copy($oldBrochure, $newBrochurePath);
                
                // Update new property brochure path
                $upStmt = self::$db->prepare("UPDATE properties SET pdf_brochure = :pdf WHERE id = :id");
                $upStmt->execute([
                    'pdf' => 'uploads/properties/' . $newId . '/' . $newBrochureName,
                    'id' => $newId
                ]);
            }
        }

        return $newId;
    }
}

