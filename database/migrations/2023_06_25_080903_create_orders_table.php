<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('buyer_id');
            $table->integer('shop_id');
            $table->float('total_amout');
            $table->float('total_amount_descreased');
            $table->string('delivery_info_id',20);
            $table->enum('payment_method', ['cash', 'card']);
            $table->enum('status',['pending','paid','delivered','cancel'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('buyer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('delivery_info_id')->references('id')->on('deliveryinfo')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
