<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();

        return [
            'user_id' => rand(0, 1) ? $user->id : null,
            'name' => $this->faker->name,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'is_login' => $user ? true : false,
        ];
    }
}

