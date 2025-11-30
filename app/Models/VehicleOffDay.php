<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleOffDay extends Model
{
    protected $fillable = [
        'vehicle_id',
        'start_date',
        'end_date',
        'reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
