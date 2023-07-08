<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerCouponFactory extends Factory
{
    protected $model = CustomerCoupon::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'coupon_id' => Coupon::inRandomOrder()->first()->id,
            'is_used' => $this->faker->boolean,
        ];
    }
}
