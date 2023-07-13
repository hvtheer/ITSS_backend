<?php

namespace Database\Factories;

use App\Models\Shop;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'slug' => Str::slug($this->faker->unique()->word),
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'thumbnail' => $this->faker->imageUrl(),
            'sold_quantity' => $this->faker->numberBetween(0, 100),
            'stock_quantity' => $this->faker->numberBetween(0, 1000),
            'category_id' => Category::inRandomOrder()->first()->id,
            'deleted' => false
        ];
    }
}
