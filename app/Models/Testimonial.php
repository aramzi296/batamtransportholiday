<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'location',
        'rating',
        'message',
        'photo',
        'is_active',
        'is_featured',
        'display_order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'display_order' => 'integer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Scope for active testimonials
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured testimonials
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for ordering testimonials
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                     ->orderBy('created_at', 'desc');
    }

    /**
     * Get stars array for rating display
     */
    public function getStarsAttribute()
    {
        $stars = [];
        for ($i = 1; $i <= 5; $i++) {
            $stars[] = $i <= $this->rating ? 'fas fa-star' : 'far fa-star';
        }
        return $stars;
    }

    /**
     * Get photo URL with fallback
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            // Cek di public storage
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->photo)) {
                return \Illuminate\Support\Facades\Storage::disk('public')->url($this->photo);
            }
        }
        
        // Fallback to avatar placeholder
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=150&background=667eea&color=ffffff&bold=true';
    }

    /**
     * Get short message for preview
     */
    public function getShortMessageAttribute()
    {
        return strlen($this->message) > 100 ? substr($this->message, 0, 100) . '...' : $this->message;
    }

    /**
     * Get rating text
     */
    public function getRatingTextAttribute()
    {
        $ratings = [
            1 => 'Sangat Buruk',
            2 => 'Buruk',
            3 => 'Cukup',
            4 => 'Baik',
            5 => 'Sangat Baik'
        ];

        return $ratings[$this->rating] ?? 'Tidak ada rating';
    }
}