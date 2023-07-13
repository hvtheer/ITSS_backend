<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'shop_logo' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['active', 'inactive'])
        ];
    }
}
