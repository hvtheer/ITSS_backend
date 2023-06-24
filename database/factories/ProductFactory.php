<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' =>$this->faker->address,
            'price'=>$this->faker->randomDigit,
            'quantity' =>$this->faker->randomDigit,
            'category_id' =>$this->faker->randomElement([1, 20]),
            'shop_id' =>$this->faker->randomElement([1, 30]),
            'quantity_sold' =>$this->faker->randomDigit,



        ];
    }
}
