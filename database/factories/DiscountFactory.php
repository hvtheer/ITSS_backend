<?php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->regexify('[A-Za-z0-9]{10}'),
            'type' => $this->faker->randomElement(['fixed', 'percent']),
            'value' => $this->faker->randomFloat(2, 0, 100),
            'created_by' => \App\Models\User::inRandomOrder()->first()->id,
            'start_at' => $this->faker->dateTimeBetween('now', '+1 week'),
            'end_at' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
        ];
    }
}


