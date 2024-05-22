<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'phone',
        'email',
        'suspended',
        'background_image',
        'profile_image',
        'theme',
        'plan_id',
        'next_plan_id',
        'subscription_ends_at',
        'balance'
    ];

    protected $casts = [
        'subscription_ends_at' => 'datetime',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
    public function rents()
    {
        return $this->hasMany(Rent::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function cars()
    {
        return $this->hasMany(Car::class);
    }
    public function rentPayments()
    {
        return $this->hasMany(RentPayment::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function nextPlan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function transactions()
    {
        return $this->hasMany(TenantTransaction::class);
    }
}
