<?php

namespace App\Helpers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageHelper
{
    /**
     * Create thumbnail from uploaded file or image path with max size 70KB
     * 
     * @param string|\Illuminate\Http\UploadedFile $imagePathOrFile Path to image or uploaded file
     * @param int $maxWidth Maximum width
     * @param int $maxHeight Maximum height
     * @param int $quality Initial quality
     * @return string|null Thumbnail path or null on failure
     */
    public static function createThumbnail($imagePathOrFile, $maxWidth = 800, $maxHeight = 600, $quality = 85)
    {
        try {
            $manager = new ImageManager(new Driver());
            
            $fullPath = null;
            $tempImagePath = null;
            $isUploadedFile = false;
            
            // Check if it's an uploaded file object
            if (is_object($imagePathOrFile) && method_exists($imagePathOrFile, 'getRealPath')) {
                // It's an uploaded file
                $fullPath = $imagePathOrFile->getRealPath();
                $isUploadedFile = true;
            } else {
                // It's a path string
                $imagePath = $imagePathOrFile;
                
                // Get full path from public storage
                if (Storage::disk('public')->exists($imagePath)) {
                    $fullPath = Storage::disk('public')->path($imagePath);
                } else {
                    Log::warning("Image not found in public storage: {$imagePath}");
                    return null;
                }
            }
            
            if (!file_exists($fullPath)) {
                Log::warning("Image file not found: {$fullPath}");
                return null;
            }
            
            // Read and resize image
            $image = $manager->read($fullPath);
            
            // Resize with maintain aspect ratio
            $image->scale(width: $maxWidth, height: $maxHeight);
            
            // Encode with quality adjustment to reach ~70KB
            $targetSize = 70 * 1024; // 70 KB in bytes
            $currentQuality = $quality;
            $tempThumbnail = sys_get_temp_dir() . '/thumb_' . uniqid() . '.jpg';
            
            // Try different quality levels to reach target size
            do {
                $image->toJpeg($currentQuality)->save($tempThumbnail);
                $fileSize = filesize($tempThumbnail);
                
                if ($fileSize <= $targetSize || $currentQuality <= 50) {
                    break;
                }
                
                $currentQuality -= 5;
            } while ($fileSize > $targetSize && $currentQuality > 50);
            
            // Generate thumbnail filename
            if ($isUploadedFile) {
                $originalName = $imagePathOrFile->getClientOriginalName();
            } else {
                $originalName = basename($imagePathOrFile);
            }
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
            $thumbnailName = $nameWithoutExt . '_thumb.jpg';
            
            // Upload thumbnail to public storage
            $thumbnailPath = 'vehicles/thumbnails/' . $thumbnailName;
            Storage::disk('public')->put($thumbnailPath, file_get_contents($tempThumbnail));
            
            // Cleanup temp files
            if (file_exists($tempThumbnail)) {
                unlink($tempThumbnail);
            }
            
            if ($tempImagePath && file_exists($tempImagePath)) {
                unlink($tempImagePath);
            }
            
            return $thumbnailPath;
            
        } catch (\Exception $e) {
            Log::error('Failed to create thumbnail: ' . $e->getMessage());
            return null;
        }
    }
}

