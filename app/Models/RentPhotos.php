<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPhotos extends Model
{
    use HasFactory;

    protected $fillable = ['rent_id', 'url', 'notes', 'damage'];
}
