<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'card_number',
        'delivery_info_id',
    ];

    public function deliveryInfo()
    {
        return $this->belongsTo(DeliveryInfo::class);
    }
}
