<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\UserCoupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id,
            'user_coupon_id' => UserCoupon::inRandomOrder()->first()->id,
            'total_amount' => $this->faker->randomFloat(2, 0, 1000),
            'total_amount_decreased' => $this->faker->randomFloat(2, 0, 1000),
            'total_amount_payable' => $this->faker->randomFloat(2, 0, 1000),
            'payment_method' => $this->faker->randomElement(['cod', 'card']),
            'paid' => $this->faker->boolean,
        ];
    }
}
