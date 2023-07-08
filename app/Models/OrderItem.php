<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_coupon_id',
        'product_id',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productCoupon()
    {
        return $this->belongsTo(ProductCoupon::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPrice()
    {
        $product = $this->product;
        $product_coupon = $this->product_coupon;

        // Apply coupon discount if available
        if ($product_coupon) {
            $productCoupon = ProductCoupon::where('product_id', $product->id)
                ->where('product_coupon_id', $product_coupon->id)
                ->first();

            if ($productCoupon) {
                $discountedAmount = $productCoupon->coupon->discounted_amount;

                if ($product_coupon->type === 'fixed') {
                    $price = $product->price - $discountedAmount;
                } elseif ($product_coupon->type === 'percent') {
                    $price = $product->price * (1 - $discountedAmount / 100);
                }
            }
        }

        // If no coupon or coupon discount is not applicable, use regular product price
        if (!isset($price)) {
            $price = $product->price;
        }

        return $price * $this->quantity;
    }

}
