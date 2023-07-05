<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->dateTime('shipping_date');
            $table->enum('shipping_status', ['shipping soon', 'shipped', 'out of delivery', 'delivered']);
            $table->string('shipping_carrier');
            $table->string('tracking_number');
            $table->decimal('payment_amount', 10, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
}
