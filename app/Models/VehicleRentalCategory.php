<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleRentalCategory extends Model
{
    protected $table = 'vehicle_rental_categories';

    protected $fillable = [
        'vehicle_id',
        'rental_category_id',
        'with_driver',
        'price'
    ];

    protected $casts = [
        'with_driver' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function rentalCategory()
    {
        return $this->belongsTo(RentalCategory::class);
    }
}
