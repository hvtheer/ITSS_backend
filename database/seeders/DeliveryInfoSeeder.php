<?php

namespace Database\Seeders;

use App\Models\DeliveryInfo;
use Illuminate\Database\Seeder;

class DeliveryInfoSeeder extends Seeder
{
    public function run()
    {
        DeliveryInfo::factory()->count(10)->create();
    }
}
