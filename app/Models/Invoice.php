<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_coupon_id',
        'total_amount',
        'total_amount_decreased',
        'total_amount_payable',
        'payment_method',
        'paid',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function userCoupon()
    {
        return $this->belongsTo(UserCoupon::class);
    }
}
