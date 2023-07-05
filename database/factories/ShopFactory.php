<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    protected $model = Shop::class;

    private static $userId = 1;

    public function definition()
    {
        return [
            'user_id' => self::$userId++,
            'shop_name' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'registration_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'is_verified' => $this->faker->boolean,
            'shop_logo_url' => $this->faker->imageUrl(),
        ];
    }
}

