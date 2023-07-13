<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'shop_id',
        'name',
        'description',
        'price',
        'thumbnail',
        'sold_quantity',
        'stock_quantity',
        'category_id',
        'avg_rating',
        'deleted'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeWithinPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productCoupons()
    {
        return $this->hasMany(ProductCoupon::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function updateStockQuantity($quantity)
    {
        $this->sold_quantity += $quantity;
        $this->stock_quantity -= $quantity;
        $this->save();
    }
    
    public function calculateAverageRating()
    {
        $averageRating = $this->reviews()->avg('rating');
        $this->avg_rating = $averageRating ?? 0;
        $this->save();
    }    
}
