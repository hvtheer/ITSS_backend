<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition()
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'attribute_name' => $this->faker->word,
            'attribute_value' => $this->faker->word,
        ];
    }
}
