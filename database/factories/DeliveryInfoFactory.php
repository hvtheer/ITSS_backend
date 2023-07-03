<?php

namespace Database\Factories;

use App\Models\DeliveryInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryInfoFactory extends Factory
{
    protected $model = DeliveryInfo::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'order_id' => \App\Models\Order::inRandomOrder()->first()->id,
            'note' => $this->faker->sentence,
            'address' => $this->faker->address,
            'delivery_fee' => $this->faker->randomFloat(2, 10, 100),
            'total' => '0'
        ];
    }
}
