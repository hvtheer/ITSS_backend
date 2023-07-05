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
        $user_id = User::inRandomOrder()->first()->id;
        return [
            'user_id' => null,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'date_of_birth' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'profile_picture_url' => $this->faker->imageUrl(),
            'is_verified' => $this->faker->boolean,
        ];
    }
}
