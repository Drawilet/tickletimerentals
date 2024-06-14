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
        "tenant_id",
        "rate_schedule",
    ];

    protected $casts = [
        'features' => 'array',
        'rate_schedule' => 'array',
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

    // make a function that determinates if a car is available for a given date range and return a boolean
    public function isAvailable($start_date, $end_date)
    {
        $rents = $this->rents()->where(function ($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date])
                ->orWhereBetween('end_date', [$start_date, $end_date])
                ->orWhere(function ($query) use ($start_date, $end_date) {
                    $query->where('start_date', '<=', $start_date)
                        ->where('end_date', '>=', $end_date);
                });
        })->get();

        return $rents->isEmpty();
    }

    public function getRegions()
    {
        $regions = [];
        foreach ($this->rate_schedule as $region => $rates) {
            $regions[] = explode('-', $region)[1];
        }

        return $regions;
    }
}
