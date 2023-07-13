<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'deleted'
    ];

    public const ROLE_ADMIN = 1;
    public const ROLE_SELLER = 2;
    public const ROLE_CUSTOMER = 3;

}
