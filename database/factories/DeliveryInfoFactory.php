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
            'receiver_name' => $this->faker->name,
            'numberPhone' => $this->faker->numerify('##########'),
            'note' => $this->faker->text(200),
            'address' => $this->faker->address,
            'shipping_fee' => '30000',
        ];
    }
}
