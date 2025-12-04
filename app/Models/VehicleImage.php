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
        
        // Handle path lama dari S3 (vehicles/xxx.png, profiles/xxx.png, testimonials/xxx.png)
        // Path ini sekarang harus di storage/app/public
        if (preg_match('/^(vehicles|profiles|testimonials)\//', $this->image_path)) {
            // Cek apakah file ada di public storage
            if (Storage::disk('public')->exists($this->image_path)) {
                return Storage::disk('public')->url($this->image_path);
            }
            // Jika tidak ada, tetap return URL (mungkin file belum di-migrate)
            return asset('storage/' . $this->image_path);
        }
        
        // Jika path sudah dimulai dengan 'storage/', langsung gunakan asset
        if (strpos($this->image_path, 'storage/') === 0) {
            return asset($this->image_path);
        }
        
        // Fallback untuk path lama (local storage di public/images)
        if (strpos($this->image_path, 'images/') === 0) {
            return asset($this->image_path);
        }
        
        // Default: gunakan asset dengan prefix storage (untuk file di storage/app/public)
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail_path) {
            // Fallback ke image_url jika thumbnail belum ada
            return $this->image_url;
        }
        
        // Handle path lama dari S3 (vehicles/thumbnails/xxx.png)
        if (preg_match('/^(vehicles|profiles|testimonials)\//', $this->thumbnail_path)) {
            // Cek apakah file ada di public storage
            if (Storage::disk('public')->exists($this->thumbnail_path)) {
                return Storage::disk('public')->url($this->thumbnail_path);
            }
            // Jika file tidak ada, fallback ke image_url
            return $this->image_url;
        }
        
        // Jika path sudah dimulai dengan 'storage/', cek apakah file ada
        if (strpos($this->thumbnail_path, 'storage/') === 0) {
            $relativePath = str_replace('storage/', '', $this->thumbnail_path);
            if (Storage::disk('public')->exists($relativePath)) {
                return asset($this->thumbnail_path);
            }
            // Jika file tidak ada, fallback ke image_url
            return $this->image_url;
        }
        
        // Fallback untuk path lama (local storage di public/images)
        if (strpos($this->thumbnail_path, 'images/') === 0) {
            if (file_exists(public_path($this->thumbnail_path))) {
                return asset($this->thumbnail_path);
            }
            // Jika file tidak ada, fallback ke image_url
            return $this->image_url;
        }
        
        // Default: cek apakah file ada di public storage
        if (Storage::disk('public')->exists($this->thumbnail_path)) {
            return asset('storage/' . $this->thumbnail_path);
        }
        
        // Jika file tidak ada, fallback ke image_url
        return $this->image_url;
    }
}
