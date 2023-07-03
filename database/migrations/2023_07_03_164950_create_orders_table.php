<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('seller_id');
            $table->float('total');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->float('subtotal');
            $table->enum('payment_method',['cod','card']);
            $table->enum('payment_status', ['unpaid', 'paid']);
            $table->enum('status', ['pending', 'shipping', 'delivered', 'cancel']);
            $table->string('note', 200)->nullable();
            $table->unsignedInteger('total_qty');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
