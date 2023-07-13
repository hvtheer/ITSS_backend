<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discounted_amount',
        'quantity',
        'created_by',
        'start_date',
        'end_date',
        'deleted'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function productCoupons()
    {
        return $this->hasMany(ProductCoupon::class);
    }

    public function customerCoupons()
    {
        return $this->hasMany(CustomerCoupon::class);
    }
}
