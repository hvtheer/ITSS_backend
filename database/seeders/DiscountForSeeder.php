<?php

namespace Database\Seeders;

use App\Models\DiscountFor;
use Illuminate\Database\Seeder;

class DiscountForSeeder extends Seeder
{
    public function run()
    {
        DiscountFor::factory()->count(10)->create();
    }
}
