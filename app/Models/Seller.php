<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'address',
        'phone',
        'logo',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
