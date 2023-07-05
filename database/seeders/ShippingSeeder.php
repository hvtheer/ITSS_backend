<?php

namespace Database\Seeders;

use App\Models\Shipping;
use Illuminate\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run()
    {
        Shipping::factory()->count(10)->create();
    }
}
