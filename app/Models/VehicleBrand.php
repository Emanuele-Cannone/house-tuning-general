<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'brand',
        'name'
    ];


    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
