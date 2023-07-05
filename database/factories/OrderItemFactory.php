<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id,
            'product_id' => Product::inRandomOrder()->first()->id,
            'quantity' => $this->faker->numberBetween(1, 10),
            'attribute_id' => ProductAttribute::inRandomOrder()->first()->id,
            'total_price' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
