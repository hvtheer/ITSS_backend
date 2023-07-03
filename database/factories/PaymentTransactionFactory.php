<?php

namespace Database\Factories;

use App\Models\DeliveryInfo;
use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    public function definition()
    {
        return [
            'content' => $this->faker->text,
            'card_number' => $this->faker->creditCardNumber,
            'delivery_info_id' => DeliveryInfo::inRandomOrder()->first()->id,
        ];
    }
}
