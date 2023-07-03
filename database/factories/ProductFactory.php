<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'quantity' => $this->faker->numberBetween(1, 100),
            'category_id' => Category::inRandomOrder()->first()->id,
            'seller_id' => Seller::inRandomOrder()->first()->id,
            'sold_qty' => $this->faker->numberBetween(0, 50),
        ];
    }
}
