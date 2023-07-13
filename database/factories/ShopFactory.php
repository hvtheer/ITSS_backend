<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'shop_name' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'shop_logo' => $this->faker->imageUrl(),
        ];
    }
}
