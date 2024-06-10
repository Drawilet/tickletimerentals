<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'supplier_id',
        'name',
        'sku',
        'description',
        'price',
        "iva",
        'unit',
        'notes',
    ];
}
