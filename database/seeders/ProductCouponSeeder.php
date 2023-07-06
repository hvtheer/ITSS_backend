<?php

namespace Database\Seeders;

use App\Models\ProductCoupon;
use Illuminate\Database\Seeder;

class ProductCouponSeeder extends Seeder
{
    public function run()
    {
        ProductCoupon::factory()->count(10)->create();
    }
}
