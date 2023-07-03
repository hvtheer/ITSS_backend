<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'order_id',
        'note',
        'address',
        'delivery_fee',
        'total',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
