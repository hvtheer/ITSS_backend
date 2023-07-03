<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'name' => $this->faker->word,
            'status' => $this->faker->boolean,
        ];
    }
}

