<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'member_id',
        'description',
        'price_per_day',
        'price_per_day_no_driver',
        'machine_cc',
        'brand_id',
        'brand', // Keep for backward compatibility
        'model',
        'year',
        'color',
        'fuel_type',
        'transmission',
        'seats',
        'plate_number',
        'features',
        'images',
        'is_available',
        'queue_number'
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
        'price_per_day' => 'decimal:2',
        'price_per_day_no_driver' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(VehicleCategory::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(VehicleBrand::class, 'brand_id');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function vehicleImages()
    {
        return $this->hasMany(VehicleImage::class)->orderBy('order');
    }

    public function featuredImage()
    {
        return $this->hasOne(VehicleImage::class)->where('is_featured', true);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function availability()
    {
        return $this->hasMany(VehicleAvailability::class);
    }

    public function offDays()
    {
        return $this->hasMany(VehicleOffDay::class);
    }

    public function rentalCategories()
    {
        return $this->hasMany(VehicleRentalCategory::class);
    }

    public function isAvailableOnDate($date)
    {
        // Check if vehicle is generally available
        if (!$this->is_available) {
            return false;
        }

        // Check if there's a specific availability rule for this date
        $availability = $this->availability()->whereDate('date', $date)->first();
        if ($availability) {
            return $availability->is_available;
        }

        // Check if there's an existing booking for this date
        $booking = $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($date) {
                $query->whereDate('start_date', '<=', $date)
                      ->whereDate('end_date', '>=', $date);
            })
            ->exists();

        return !$booking;
    }

    public function getMainImageAttribute()
    {
        // Prioritaskan menggunakan vehicleImages (relasi)
        if ($this->relationLoaded('vehicleImages') || $this->relationLoaded('featuredImage')) {
            $featuredImage = $this->vehicleImages->where('is_featured', true)->first();
            $firstImage = $featuredImage ?? $this->vehicleImages->first();
            
            if ($firstImage && $firstImage->image_url) {
                return $firstImage->image_url;
            }
        }
        
        // Fallback ke array images (untuk backward compatibility)
        $images = $this->images ?? [];
        if (count($images) > 0) {
            $imagePath = $images[0];
            // Jika path dimulai dengan 'images/', gunakan asset()
            if (strpos($imagePath, 'images/') === 0) {
                return asset($imagePath);
            }
            // Jika path dimulai dengan 'storage/', gunakan asset()
            if (strpos($imagePath, 'storage/') === 0) {
                return asset($imagePath);
            }
            // Jika path dimulai dengan 'vehicles/', cek S3 atau gunakan asset
            if (strpos($imagePath, 'vehicles/') === 0) {
                try {
                    if (Storage::disk('s3')->exists($imagePath)) {
                        return Storage::disk('s3')->url($imagePath);
                    }
                } catch (\Exception $e) {
                    // Fallback jika S3 error
                }
            }
            return asset('storage/' . $imagePath);
        }
        
        // Default image
        return asset('/images/vehicles/default.jpg');
    }
    
    public function getUnavailableDatesInRange($startDate, $endDate)
    {
        $unavailableDates = [];
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        
        while ($currentDate <= $endDate) {
            if (!$this->isAvailableOnDate($currentDate->format('Y-m-d'))) {
                $availability = $this->availability()->whereDate('date', $currentDate->format('Y-m-d'))->first();
                $reason = $availability && $availability->reason ? $availability->reason : 'Tidak tersedia';
                
                $unavailableDates[] = [
                    'date' => $currentDate->format('Y-m-d'),
                    'formatted_date' => $currentDate->format('d/m/Y'),
                    'day_name' => $currentDate->format('l'),
                    'reason' => $reason
                ];
            }
            $currentDate->addDay();
        }
        
        return $unavailableDates;
    }

    /**
     * Get brand name - prefer relation, fallback to column
     */
    public function getBrandNameAttribute()
    {
        // If brand relation is loaded and is a VehicleBrand instance
        if ($this->relationLoaded('brand') && $this->brand instanceof VehicleBrand) {
            return $this->brand->name;
        }
        
        // If brand_id exists, try to get from relation
        if ($this->brand_id) {
            $brand = $this->brand;
            if ($brand instanceof VehicleBrand) {
                return $brand->name;
            }
        }
        
        // Fallback to brand column (string) for backward compatibility
        return $this->getOriginal('brand') ?? 'N/A';
    }
}