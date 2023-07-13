<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('customer_coupon_id')->nullable();
            $table->float('total_amount');
            $table->float('total_amount_decreased');
            $table->float('total_amount_payable');
            $table->enum('payment_method', ['cod', 'card']);
            $table->enum('payment_status', ['paid', 'unpaid']);
            $table->boolean('deleted')->default(false);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_coupon_id')->references('id')->on('customer_coupons')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
