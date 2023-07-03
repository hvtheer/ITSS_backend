<?php

namespace Database\Factories;

use App\Models\DiscountFor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountForFactory extends Factory
{
    protected $model = DiscountFor::class;

    public function definition()
    {
        return [
            'product_id' => \App\Models\Product::inRandomOrder()->first()->id,
            'discount_id' => \App\Models\Discount::inRandomOrder()->first()->id,
            'user_id' => function (array $attributes) {
                // Check if product_id is null to determine whether to generate a user_id
                if ($attributes['product_id'] === null) {
                    return \App\Models\User::inRandomOrder()->first()->id;
                }
                return null;
            },
        ];
    }
}
