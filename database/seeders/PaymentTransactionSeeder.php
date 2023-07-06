<?php

namespace Database\Seeders;

use App\Models\PaymentTransaction;
use Illuminate\Database\Seeder;

class PaymentTransactionSeeder extends Seeder
{
    public function run()
    {
        PaymentTransaction::factory()->count(10)->create();
    }
}
