<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleAvailability extends Model
{
    use HasFactory;

    protected $table = 'vehicle_availability';

    protected $fillable = [
        'vehicle_id',
        'date',
        'is_available',
        'reason'
    ];

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}