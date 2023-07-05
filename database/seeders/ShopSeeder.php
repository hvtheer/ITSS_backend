<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run()
    {
        Shop::factory()->count(10)->create();
    }
}
