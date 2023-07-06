<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'order_id' => Order::inRandomOrder()->first()->id,
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'review_text' => $this->faker->paragraph,
        ];
    }
}
