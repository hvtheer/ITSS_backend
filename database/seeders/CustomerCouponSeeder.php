<?php

namespace Database\Seeders;

use App\Models\CustomerCoupon;
use Illuminate\Database\Seeder;

class CustomerCouponSeeder extends Seeder
{
    public function run()
    {
        CustomerCoupon::factory()->count(10)->create();
    }
}
