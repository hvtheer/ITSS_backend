<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryInfo;

class DeliveryInfoSeeder extends Seeder
{
    public function run()
    {
        DeliveryInfo::factory()
            ->count(10)
            ->create();
    }
}
