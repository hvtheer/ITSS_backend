<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\CustomerCoupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        $order = Order::inRandomOrder()->first();
        $customerId = $order->customer_id;
        $customerCoupon = CustomerCoupon::where('customer_id', $customerId)->first();
        return [
            'order_id' => $order->id,
            'customer_coupon_id' => $customerCoupon->id,
            'total_amount' => $this->faker->randomFloat(2, 0, 100000),
            'total_amount_decreased' => $this->faker->randomFloat(2, 0, 100000),
            'total_amount_payable' => $this->faker->randomFloat(2, 0, 100000),
            'payment_method' => $this->faker->randomElement(['cod', 'card']),
            'payment_status' => $this->faker->randomElement(['paid', 'unpaid']),
        ];
    }
}
