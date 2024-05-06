<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPayment extends Base
{
    use HasFactory;

    protected $fillable = [
        "rent_id",
        "amount",
        "notes",
    ];

}
