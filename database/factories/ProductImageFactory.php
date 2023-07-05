<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition()
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'image_url' => $this->faker->imageUrl(),
            'is_primary_image' => $this->faker->boolean(30),
        ];
    }
}
