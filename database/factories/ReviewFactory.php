<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        $product = Product::inRandomOrder()->first();

        if ($product) {
            $orderItem = OrderItem::where('product_id', $product->id)->first();

            if ($orderItem) {
                $order = Order::find($orderItem->order_id);

                if ($order) {
                    return [
                        'product_id' => $product->id,
                        'order_item_id' => $orderItem->id,
                        'order_id' => $order->id,
                        'customer_id' => $order->customer_id,
                        'rating' => $this->faker->numberBetween(1, 5),
                        'review_text' => $this->faker->paragraph,
                    ];
                }
            }
        }

        return [];
    }
}
