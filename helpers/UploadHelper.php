<?php
namespace App\Helpers;

/**
 * Image Upload and Compression Helper (GD Library)
 */
class UploadHelper {
    private static array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/heic',
        'image/heif',
        'image/x-heic',
        'image/heic-sequence',
        'image/heif-sequence',
        'image/octet-stream',
        'application/octet-stream'
    ];

    private static array $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'webp', 'heic', 'heif'
    ];

    /**
     * Upload and compress up to 20 images for a property
     * 
     * @param array $filesArray $_FILES['images'] layout
     * @param int $propertyId The property ID for directory naming
     * @return array Array of uploaded files paths relative to root, or errors
     */
    public static function uploadPropertyImages(array $filesArray, int $propertyId): array {
        $uploadedPaths = [];
        $errors = [];

        // Ensure property target directory exists
        $targetDir = UPLOAD_DIR . $propertyId . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Standardize $_FILES structure for multiple file uploads
        $files = self::reorderFilesArray($filesArray);

        if (count($files) > MAX_IMAGE_COUNT) {
            return ['errors' => ['You can upload a maximum of ' . MAX_IMAGE_COUNT . ' images.']];
        }

        foreach ($files as $index => $file) {
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = "Image #" . ($index + 1) . " upload failed with error code: " . $file['error'];
                continue;
            }

            // Size check
            if ($file['size'] > MAX_FILE_SIZE) {
                $errors[] = "Image \"" . htmlspecialchars($file['name']) . "\" exceeds the 8MB size limit.";
                continue;
            }

            // Validate extension
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, self::$allowedExtensions)) {
                $errors[] = "Image \"" . htmlspecialchars($file['name']) . "\" has an invalid extension. Only JPG, PNG, WEBP, and HEIC allowed.";
                continue;
            }

            // Validate MIME type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mime, self::$allowedMimeTypes)) {
                $errors[] = "Image \"" . htmlspecialchars($file['name']) . "\" has an invalid file type.";
                continue;
            }

            // Generate unique name
            $fileName = uniqid('prop_' . $propertyId . '_', true);
            $isHeic = in_array($ext, ['heic', 'heif']);
            $largeName = $fileName . '_large.jpg';
            $thumbName = $fileName . '_thumb.jpg';

            $largePath = $targetDir . $largeName;
            $thumbPath = $targetDir . $thumbName;

            // Compress and save (Full HD 1920px at high quality for luxury presentation)
            $savedLarge = self::compressImage($file['tmp_name'], $largePath, 1920, 90, $ext);
            $savedThumb = self::compressImage($file['tmp_name'], $thumbPath, 600, 80, $ext);

            if ($savedLarge && $savedThumb) {
                // Store paths relative to web root: uploads/properties/{id}/filename
                $uploadedPaths[] = [
                    'large' => 'uploads/properties/' . $propertyId . '/' . $largeName,
                    'thumb' => 'uploads/properties/' . $propertyId . '/' . $thumbName
                ];
            } else {
                $errors[] = "Failed to process and compress image \"" . htmlspecialchars($file['name']) . "\".";
            }
        }

        return [
            'success' => $uploadedPaths,
            'errors' => $errors
        ];
    }

    /**
     * Compress and resize image using GD library (with Imagick/fallback support)
     */
    private static function compressImage(string $sourcePath, string $destinationPath, int $maxWidth, int $quality, string $ext = ''): bool {
        $sourceImage = null;
        $imageType = null;
        $origWidth = 0;
        $origHeight = 0;

        // 1. Try Imagick if available (handles HEIC natively if ImageMagick is compiled with libheif)
        if (class_exists('\Imagick') && in_array(strtolower($ext), ['heic', 'heif'])) {
            try {
                $imagick = new \Imagick($sourcePath);
                $imagick->setImageFormat('jpeg');
                $blob = $imagick->getImageBlob();
                $sourceImage = @imagecreatefromstring($blob);
                if ($sourceImage) {
                    $origWidth = imagesx($sourceImage);
                    $origHeight = imagesy($sourceImage);
                    $imageType = IMAGETYPE_JPEG;
                }
                $imagick->clear();
                $imagick->destroy();
            } catch (\Throwable $e) {
                $sourceImage = null;
            }
        }

        // 2. Standard GD image loading
        if (!$sourceImage) {
            $imageInfo = @getimagesize($sourcePath);
            if ($imageInfo !== false) {
                list($origWidth, $origHeight, $imageType) = $imageInfo;
                switch ($imageType) {
                    case IMAGETYPE_JPEG:
                        $sourceImage = @imagecreatefromjpeg($sourcePath);
                        break;
                    case IMAGETYPE_PNG:
                        $sourceImage = @imagecreatefrompng($sourcePath);
                        if ($sourceImage) {
                            imagealphablending($sourceImage, true);
                            imagesavealpha($sourceImage, true);
                        }
                        break;
                    case IMAGETYPE_WEBP:
                        if (function_exists('imagecreatefromwebp')) {
                            $sourceImage = @imagecreatefromwebp($sourcePath);
                        }
                        break;
                    case IMAGETYPE_AVIF:
                        if (function_exists('imagecreatefromavif')) {
                            $sourceImage = @imagecreatefromavif($sourcePath);
                        }
                        break;
                }
            }
        }

        // 3. Fallback: try imagecreatefromstring
        if (!$sourceImage) {
            $content = @file_get_contents($sourcePath);
            if ($content !== false) {
                $sourceImage = @imagecreatefromstring($content);
                if ($sourceImage) {
                    $origWidth = imagesx($sourceImage);
                    $origHeight = imagesy($sourceImage);
                    $imageType = IMAGETYPE_JPEG;
                }
            }
        }

        // 4. Fallback for raw HEIC when GD cannot decode: direct safe copy to avoid upload rejection
        if (!$sourceImage || $origWidth <= 0 || $origHeight <= 0) {
            if (in_array(strtolower($ext), ['heic', 'heif'])) {
                return @copy($sourcePath, $destinationPath);
            }
            return false;
        }

        // Calculate responsive dimensions
        if ($origWidth > $maxWidth) {
            $ratio = $origWidth / $origHeight;
            $newWidth = $maxWidth;
            $newHeight = (int)($maxWidth / $ratio);
        } else {
            $newWidth = $origWidth;
            $newHeight = $origHeight;
        }

        // Create new image container
        $destinationImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNGs when saving as JPEG (we fill with white)
        if ($imageType === IMAGETYPE_PNG) {
            $white = imagecolorallocate($destinationImage, 255, 255, 255);
            imagefill($destinationImage, 0, 0, $white);
        }

        // Resize
        imagecopyresampled(
            $destinationImage, 
            $sourceImage, 
            0, 0, 0, 0, 
            $newWidth, $newHeight, 
            $origWidth, $origHeight
        );

        // Save as compressed JPEG
        $result = imagejpeg($destinationImage, $destinationPath, $quality);

        // Clean memory
        imagedestroy($sourceImage);
        imagedestroy($destinationImage);

        return $result;
    }

    /**
     * Reorder $_FILES multiple array to list of files
     */
    private static function reorderFilesArray(array $filesArray): array {
        $reordered = [];
        if (!isset($filesArray['name']) || !is_array($filesArray['name'])) {
            return [$filesArray];
        }

        $count = count($filesArray['name']);
        for ($i = 0; $i < $count; $i++) {
            $reordered[] = [
                'name' => $filesArray['name'][$i],
                'type' => $filesArray['type'][$i],
                'tmp_name' => $filesArray['tmp_name'][$i],
                'error' => $filesArray['error'][$i],
                'size' => $filesArray['size'][$i],
            ];
        }
        return $reordered;
    }

    /**
     * Upload a PDF brochure for a property
     */
    public static function uploadBrochure(array $file, int $propertyId): ?string {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("File upload failed with error code: " . $file['error']);
        }

        // Size check: limit to 10MB for brochures
        if ($file['size'] > 10 * 1024 * 1024) {
            throw new \Exception("Brochure file size exceeds 10MB limit.");
        }

        // Validate extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            throw new \Exception("Invalid file extension. Only PDF allowed for brochures.");
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if ($mime !== 'application/pdf') {
            throw new \Exception("Invalid file type. Only PDF allowed.");
        }

        // Ensure property target directory exists
        $targetDir = UPLOAD_DIR . $propertyId . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = 'brochure_' . uniqid() . '.pdf';
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'uploads/properties/' . $propertyId . '/' . $fileName;
        }

        throw new \Exception("Failed to save brochure file.");
    }

    /**
     * Upload and resize a floor plan image
     */
    public static function uploadFloorPlan(array $file, int $propertyId): ?string {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception("Floor plan upload failed.");
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            throw new \Exception("Floor plan image exceeds 8MB size limit.");
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::$allowedExtensions)) {
            throw new \Exception("Only JPG, PNG, WEBP, and HEIC allowed for floor plans.");
        }

        // Ensure property target directory exists
        $targetDir = UPLOAD_DIR . $propertyId . '/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = 'floorplan_' . uniqid() . '.jpg';
        $targetPath = $targetDir . $fileName;

        if (self::compressImage($file['tmp_name'], $targetPath, 1200, 85, $ext)) {
            return 'uploads/properties/' . $propertyId . '/' . $fileName;
        }

        throw new \Exception("Failed to process floor plan image.");
    }

    /**
     * Safely delete a directory and all files inside
     */
    public static function deleteDirectory(string $dirPath): bool {
        if (!is_dir($dirPath)) {
            return false;
        }
        $files = array_diff(scandir($dirPath), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dirPath/$file")) ? self::deleteDirectory("$dirPath/$file") : unlink("$dirPath/$file");
        }
        return rmdir($dirPath);
    }
}
