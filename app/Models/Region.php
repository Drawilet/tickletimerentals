<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Base
{
    use HasFactory;

    protected $fillable = [
        'name',
        'locations',
        'rate_schedule',
        'daily_rate',
    ];

    protected $casts = [
        'locations' => 'array',
        'rate_schedule' => 'array'
    ];

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }
}