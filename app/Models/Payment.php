<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'order_id',
        'payment_date',
        'payment_status',
        'payment_amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
