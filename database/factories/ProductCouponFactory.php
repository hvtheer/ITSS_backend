<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductCoupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCouponFactory extends Factory
{
    protected $model = ProductCoupon::class;

    public function definition()
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'coupon_id' => Coupon::inRandomOrder()->first()->id,
        ];
    }
}
