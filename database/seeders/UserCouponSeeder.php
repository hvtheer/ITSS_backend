<?php

namespace Database\Seeders;

use App\Models\UserCoupon;
use Illuminate\Database\Seeder;

class UserCouponSeeder extends Seeder
{
    public function run()
    {
        UserCoupon::factory()->count(50)->create();
    }
}
