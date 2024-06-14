<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Base
{
    use HasFactory;
    protected $fillable = ["name", "rate", "code"];
}
