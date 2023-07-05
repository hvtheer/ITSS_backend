<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'shop_id',
        'coupon_id',
        'subtotal',
        'shipping_fee',
        'total',
        'payment_method',
        'delivery_address',
        'order_status',
        'shipping_method',
        'tracking_number',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
