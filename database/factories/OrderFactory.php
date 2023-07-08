<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\Order;
use App\Models\Customer;
use App\Models\DeliveryInfo;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'delivery_info_id' => DeliveryInfo::inRandomOrder()->first()->id,
            'order_status' => $this->faker->randomElement(['pending', 'accepted', 'not accepted']),
            'note' => $this->faker->text,
        ];
    }
}
