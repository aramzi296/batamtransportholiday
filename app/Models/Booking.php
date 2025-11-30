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
        'notes',
        'status',
        'confirmed_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_price' => 'decimal:2',
        'total_price' => 'decimal:2',
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

    public static function generateBookingCode()
    {
        do {
            $code = 'BK' . date('Ymd') . mt_rand(1000, 9999);
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }
}