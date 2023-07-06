<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductCoupon;
use App\Models\ProductImage;
use App\Models\RoleUser;
use App\Models\Shipping;
use App\Models\Shop;
use App\Models\UserCoupon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            RoleUserSeeder::class,
            ShopSeeder::class,
            CategorySeeder::class,
            CustomerSeeder::class,
            CouponSeeder::class,
            UserCouponSeeder::class,
            ProductSeeder::class,
            ProductImageSeeder::class,
            ProductAttributeSeeder::class,
            ProductCouponSeeder::class,
            DeliveryInfoSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            InvoiceSeeder::class,
            PaymentTransactionSeeder::class,
            ReviewSeeder::class
        ]);
    }
}
