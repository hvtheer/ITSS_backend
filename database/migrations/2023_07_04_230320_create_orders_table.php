<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('subtotal', 8, 2);
            $table->decimal('shipping_fee', 8, 2);
            $table->decimal('total', 8, 2);
            $table->enum('payment_method', ['cod', 'card']);
            $table->string('delivery_address');
            $table->enum('order_status', ['pending', 'accepted', 'not accepted']);
            $table->enum('shipping_method', ['slow', 'normal', 'fast']);
            $table->string('tracking_number')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}
