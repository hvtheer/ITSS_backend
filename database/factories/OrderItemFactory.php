<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductCoupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        $order = Order::inRandomOrder()->first();
        $product = Product::where('shop_id', $order->shop_id)->first();
        $productCoupon = ProductCoupon::where('product_id', $product->id)->first();
    
        return [
            'order_id' => $order->id,
            'product_coupon_id' => $productCoupon->id,
            'product_id' => $product->id,
            'quantity' => $this->faker->numberBetween(1, 10),
        ];    
    }
}
