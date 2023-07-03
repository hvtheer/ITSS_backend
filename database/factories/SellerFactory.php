<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'user_id' => User::inRandomOrder()->first()->id,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'logo' => null,
            'status' => $this->faker->randomElement(['pending', 'accepted', 'not_accepted']),
        ];
    }
}

