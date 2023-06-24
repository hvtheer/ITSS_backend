<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' =>$this->faker->randomElement([1, 20]),
            'customer_name' => $this->faker->name,
            'customer_address' =>$this->faker->address,
            'customer_numberPhone'=>$this->faker->phoneNumber,
            'is_login' =>$this->faker->randomElement([0, 1]),


        ];
    }
}
