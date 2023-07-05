<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCouponFactory extends Factory
{
    protected $model = UserCoupon::class;

    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'coupon_id' => Coupon::inRandomOrder()->first()->id,
            'is_used' => $this->faker->boolean,
            'used_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
