<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Shop extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'shops';

    protected $fillable = [
        'id','user_id', 'shop_name','description', 'shop_address', 'shop_numberPhone','shop_logo','status'
    ];
}
