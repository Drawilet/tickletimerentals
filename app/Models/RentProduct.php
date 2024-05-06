<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentProduct extends Model
{
    use HasFactory;

    protected $fillable = ["rent_id", "product_id", "quantity"];
}
