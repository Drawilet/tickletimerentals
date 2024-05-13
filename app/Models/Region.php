<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Base
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cities'
    ];

    protected $casts = [
        'cities' => 'array'
    ];
}
