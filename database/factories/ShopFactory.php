<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
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
            'shop_name' => $this->faker->name,
            'description' => $this->faker->company,
            'shop_address' =>$this->faker->address,
            'shop_numberPhone'=>$this->faker->phoneNumber,
            'shop_logo' =>$this->faker->url('http'),
            'status' =>$this->faker->text(20),
        ];
    }
}
