<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','user_id', 'customer_name','customer_address', 'customer_numberPhone', 'is_login'
    ];
}
