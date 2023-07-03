<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    public function run()
    {
        ProductAttribute::factory()->count(10)->create();
    }
}
