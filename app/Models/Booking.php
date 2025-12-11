<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'vehicle_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'start_date',
        'end_date',
        'total_days',
        'daily_price',
        'total_price',
        'with_driver',
        'notes',
        'status',
        'confirmed_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'with_driver' => 'boolean',
        'confirmed_at' => 'datetime'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(BookingEvent::class)->orderBy('created_at', 'desc');
    }

    /**
     * Log an event for this booking
     */
    public function logEvent(string $eventType, ?string $description = null, ?array $metadata = null, ?int $userId = null): BookingEvent
    {
        return $this->events()->create([
            'event_type' => $eventType,
            'description' => $description,
            'metadata' => $metadata,
            'user_id' => $userId ?? auth()->id(),
        ]);
    }

    public static function generateBookingCode()
    {
        do {
            $code = 'DS' . date('Ymd') . mt_rand(1000, 9999);
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }
}