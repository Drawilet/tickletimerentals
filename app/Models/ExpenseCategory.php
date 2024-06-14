<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Base
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'iva',
    ];
}
