<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'card_password',
        'card_number',
        'invoice_id',
        'deleted'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
