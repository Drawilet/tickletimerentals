<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rent extends Base
{
    use HasFactory;
    protected $fillable = [
        "name",
        "car_id",
        "customer_id",
        "start_date",
        "end_date",
        "total",
        "notes"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function products()
    {
        return $this->hasMany(RentProduct::class);
    }

    public function payments()
    {
        return $this->hasMany(RentPayment::class);
    }
    public function Tax()
    {
        return $this->hasMany(Tax::class);
    }
}
