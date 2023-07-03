<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'seller_id',
        'total',
        'discount_id',
        'subtotal',
        'payment_method',
        'payment_status',
        'status',
        'note',
        'total_qty',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
