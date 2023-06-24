<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' =>$this->faker->regexify('[A-Z]{5}[0-4]{3}'),
            'type' => $this->faker->company,
            'created_by' =>$this->faker->randomElement([1, 20]),
            'start_at'=>$this->faker->date,
            'end_at'=>$this->faker->date,
        ];
    }
}
