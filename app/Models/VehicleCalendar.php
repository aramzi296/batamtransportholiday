<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleCalendar extends Model
{
    protected $fillable = [
        'vehicle_id',
        'date',
        'blocked_by', // 'booking' or 'admin'
        'reason', // optional, for admin block
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
