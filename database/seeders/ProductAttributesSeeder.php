<?php

namespace Database\Seeders;
use App\Models\ProductAttributes;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductAttributes::factory()->count(15)->create();
        
    }
}
