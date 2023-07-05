<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id,
            'payment_date' => $this->faker->dateTime(),
            'payment_status' => $this->faker->randomElement(['pending', 'paid']),
            'payment_amount' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
