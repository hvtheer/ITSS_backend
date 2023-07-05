<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Shipping;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingFactory extends Factory
{
    protected $model = Shipping::class;

    public function definition()
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id,
            'shipping_date' => $this->faker->dateTime(),
            'shipping_status' => $this->faker->randomElement(['shipping soon', 'shipped', 'out of delivery', 'delivered']),
            'shipping_carrier' => $this->faker->word,
            'tracking_number' => $this->faker->unique()->randomNumber(),
            'payment_amount' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
