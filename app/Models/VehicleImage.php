<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VehicleImage extends Model
{
    protected $fillable = [
        'vehicle_id',
        'image_path',
        'thumbnail_path',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }
        
        // Normalize path untuk cek di S3
        $s3Path = $this->image_path;
        
        // Jika path dimulai dengan 'images/vehicles/', hapus prefix 'images/' untuk S3
        if (strpos($s3Path, 'images/vehicles/') === 0) {
            $s3Path = str_replace('images/vehicles/', 'vehicles/', $s3Path);
        }
        
        // Cek apakah ini path S3 (dimulai dengan 'vehicles/', 'profiles/', atau 'testimonials/')
        $isS3Path = preg_match('/^(vehicles|profiles|testimonials)\//', $s3Path);
        
        if ($isS3Path) {
            try {
                // Cek existence di S3 dengan path yang sudah dinormalisasi
                if (Storage::disk('s3')->exists($s3Path)) {
                    return Storage::disk('s3')->url($s3Path);
                }
            } catch (\Exception $e) {
                // Jika error (misalnya S3 belum dikonfigurasi), fallback ke local
            }
        }
        
        // Fallback untuk path lama (local storage)
        if (strpos($this->image_path, 'images/') === 0) {
            return asset($this->image_path);
        }
        
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail_path) {
            // Fallback ke image_url jika thumbnail belum ada
            return $this->image_url;
        }
        
        // Normalize path untuk cek di S3
        $s3Path = $this->thumbnail_path;
        
        if (strpos($s3Path, 'images/vehicles/thumbnails/') === 0) {
            $s3Path = str_replace('images/vehicles/thumbnails/', 'vehicles/thumbnails/', $s3Path);
        }
        
        $isS3Path = preg_match('/^(vehicles|profiles|testimonials)\//', $s3Path);
        
        if ($isS3Path) {
            try {
                if (Storage::disk('s3')->exists($s3Path)) {
                    return Storage::disk('s3')->url($s3Path);
                }
            } catch (\Exception $e) {
                // Fallback ke local
            }
        }
        
        if (strpos($this->thumbnail_path, 'images/') === 0) {
            return asset($this->thumbnail_path);
        }
        
        return asset('storage/' . $this->thumbnail_path);
    }
}
