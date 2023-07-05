<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'description',
        'address',
        'phone_number',
        'registration_date',
        'is_verified',
        'shop_logo_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
