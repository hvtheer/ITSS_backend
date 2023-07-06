<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition()
    {
        return [
            'code' => $this->faker->word,
            'type' => $this->faker->randomElement(['fixed', 'percent']),
            'discounted_amount' => $this->faker->randomFloat(2, 0, 100),
            'quantity' => $this->faker->randomNumber(),
            'created_by' => User::inRandomOrder()->first()->id,
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
