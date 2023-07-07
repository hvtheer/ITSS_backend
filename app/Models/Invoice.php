<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_coupon_id',
        'total_amount',
        'total_amount_decreased',
        'total_amount_payable',
    ];

    protected $attributes = [
        'total_amount' => 0,
        'total_amount_decreased' => 0,
        'total_amount_payable' => 0,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function userCoupon()
    {
        return $this->belongsTo(UserCoupon::class);
    }

    public function calculateTotalAmountPayable()
    {
        $order = $this->order;
        $orderItems = $order->orderItems;

        $totalAmount = 0;

        foreach ($orderItems as $orderItem) {
            $totalAmount += $orderItem->getPrice();
        }

        $userCoupon = $this->userCoupon;

        if ($userCoupon && $userCoupon->is_used === false) {
            $coupon = $userCoupon->coupon;
            $discountedAmount = $coupon->discounted_amount;

            if ($coupon->type === 'fixed') {
                $totalAmountDecreased = $discountedAmount;
            } elseif ($coupon->type === 'percent') {
                $totalAmountDecreased = $totalAmount * ($discountedAmount / 100);
            }

            $totalAmountPayable = $totalAmount - $totalAmountDecreased;
        } else {
            $totalAmountDecreased = 0;
            $totalAmountPayable = $totalAmount;
        }

        $this->total_amount = $totalAmount;
        $this->total_amount_decreased = $totalAmountDecreased;
        $this->total_amount_payable = $totalAmountPayable;
    }
}
