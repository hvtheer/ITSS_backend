<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('order_detail_id')->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('star');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('order_detail_id')->references('id')->on('order_details');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
