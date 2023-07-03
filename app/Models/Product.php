<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'category_id',
        'seller_id',
        'sold_qty',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
