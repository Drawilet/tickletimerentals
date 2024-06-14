<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Base
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'subtotal',
        'total',
    ];

    public function details()
    {
        return $this->hasMany(ExpenseDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
