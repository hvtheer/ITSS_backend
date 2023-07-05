<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shipping_date',
        'shipping_status',
        'shipping_carrier',
        'tracking_number',
        'payment_amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
