<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Base
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "color",
        "plate_number",
        "brand",
        "model",
        "year",
        "fuel_type",
        "transmission",
        "engine",
        "seats",
        "doors",
        "features",
        "prices",
        "tenant_id"
    ];

    protected $casts = [
        'features' => 'array',
        'prices' => 'array',
    ];

    public function photos()
    {
        return $this->hasMany(CarPhoto::class);
    }

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
