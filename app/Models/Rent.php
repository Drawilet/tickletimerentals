<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rent extends Base
{
    use HasFactory;
    protected $fillable = ["name", "car_id", "customer_id", "date", "start_time", "end_time", "price", "notes"];

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
}
