<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('1111'), // You may change this according to your requirements
        ];
    }
}

