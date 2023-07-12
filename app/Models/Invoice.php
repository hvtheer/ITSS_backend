<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_coupon_id',
        'total_amount',
        'total_amount_decreased',
        'total_amount_payable',
        'payment_method',
        'payment_status',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customerCoupon()
    {
        return $this->belongsTo(customerCoupon::class);
    }

    public function calculateTotalAmountPayable()
    {
        $order = $this->order;
        $orderItems = $order->orderItems;

        $totalAmount = 0;

        foreach ($orderItems as $orderItem) {
            $totalAmount += $orderItem->getPrice();
        }

        $customerCoupon = $this->customerCoupon;
        
        $totalAmountDecreased = 0;
        
        if ($customerCoupon && $customerCoupon->is_used === 0) {
            $coupon = $customerCoupon->coupon;
            
            if ($coupon->type === 'fixed') {
                $totalAmountDecreased = $coupon->discounted_amount;
            }
            if ($coupon->type === 'percent') {
                $totalAmountDecreased = $totalAmount * ($coupon->discounted_amount / 100);
            }
            
            $totalAmountPayable = $totalAmount - $totalAmountDecreased;
        } else {
         $totalAmountPayable = $totalAmount;
        }

        $this->total_amount = $totalAmount;
        $this->total_amount_decreased = $totalAmountDecreased;
        $this->total_amount_payable = $totalAmountPayable;
}
}
