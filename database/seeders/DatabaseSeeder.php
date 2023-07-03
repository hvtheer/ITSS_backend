<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(SellerSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(DiscountSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductAttributeSeeder::class);
        $this->call(DiscountForSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OrderDetailSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(DeliveryInfoSeeder::class);
        $this->call(PaymentTransactionSeeder::class);
    }
}
