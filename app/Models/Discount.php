<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'created_by',
        'start_at',
        'end_at',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

