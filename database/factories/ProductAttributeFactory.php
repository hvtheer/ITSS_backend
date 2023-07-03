<?php

namespace Database\Factories;

use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::inRandomOrder()->first()->id,
            'attribute' => $this->faker->word,
            'value' => $this->faker->word,
        ];
    }
}
