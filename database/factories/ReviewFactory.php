<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        $product = Product::inRandomOrder()->first();
        $orderDetail = OrderDetail::where('product_id', $product->id)->first();

        return [
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'product_id' => $product->id,
            'order_detail_id' => $orderDetail ? $orderDetail->id : null,
            'content' => $this->faker->paragraph,
            'star' => $this->faker->numberBetween(1, 5),
        ];
    }
}
