<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'slug' => $this->faker->slug,
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'stock_quantity' => $this->faker->randomNumber(),
            'category_id' => Category::factory(),
        ];
    }
}
