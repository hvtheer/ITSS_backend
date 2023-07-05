<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'coupon_id' => Coupon::inRandomOrder()->first()->id,
            'subtotal' => $this->faker->randomFloat(2, 10, 100),
            'shipping_fee' => $this->faker->randomFloat(2, 0, 20),
            'total' => $this->faker->randomFloat(2, 50, 200),
            'payment_method' => $this->faker->randomElement(['cod', 'card']),
            'delivery_address' => $this->faker->address,
            'order_status' => $this->faker->randomElement(['pending', 'accepted', 'not accepted']),
            'shipping_method' => $this->faker->randomElement(['slow', 'normal', 'fast']),
            'tracking_number' => $this->faker->randomNumber(),
        ];
    }
}
