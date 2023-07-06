<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_name',
        'numberPhone',
        'note',
        'address',
        'shipping_fee',
    ];
}
