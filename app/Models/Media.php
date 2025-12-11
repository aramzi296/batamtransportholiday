<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'filename',
        'original_filename',
        'file_path',
        'mime_type',
        'file_size',
        'description',
        'thumbnail_path',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Get the full URL of the media file
     */
    public function getUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        // Get URL from S3
        if (Storage::disk('s3')->exists($this->file_path)) {
            return Storage::disk('s3')->url($this->file_path);
        }

        return null;
    }

    /**
     * Get the thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path && Storage::disk('s3')->exists($this->thumbnail_path)) {
            return Storage::disk('s3')->url($this->thumbnail_path);
        }

        // Fallback to main image if thumbnail doesn't exist
        return $this->url;
    }

    /**
     * Check if file is an image
     */
    public function getIsImageAttribute()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
