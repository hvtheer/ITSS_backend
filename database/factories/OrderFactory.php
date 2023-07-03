<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer_id' => function () {
                return \App\Models\Customer::inRandomOrder()->first()->id;
            },
            'seller_id' => function () {
                return \App\Models\Seller::inRandomOrder()->first()->id;
            },
            'total' => $this->faker->randomFloat(2, 0, 1000),
            'discount_id' => function () {
                return \App\Models\Discount::inRandomOrder()->first()->id;
            },
            'subtotal' => $this->faker->randomFloat(2, 0, 1000),
            'payment_method' => $this->faker->randomElement(['cod', 'card']),
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid']),
            'status' => $this->faker->randomElement(['pending', 'shipping', 'delivered', 'cancel']),
            'note' => $this->faker->sentence(10),
            'total_qty' => $this->faker->randomNumber(2),
        ];
    }
}
