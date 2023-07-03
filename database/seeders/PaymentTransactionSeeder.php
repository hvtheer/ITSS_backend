<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentTransaction;

class PaymentTransactionSeeder extends Seeder
{
    public function run()
    {
        PaymentTransaction::factory()
            ->count(10)
            ->create();
    }
}
