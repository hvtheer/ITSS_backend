<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    public function definition()
    {
        return [
            'content' => $this->faker->sentence,
            'card_password' => $this->faker->password,
            'card_number' => $this->faker->randomNumber(8),
            'invoice_id' => Invoice::inRandomOrder()->first()->id,
        ];
    }
}
