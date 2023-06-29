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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->float('price');
            $table->integer('quantity');
            $table->unsignedInteger('category_id');
            $table->unsignedBigInteger('shop_id');
            $table->integer('quantity_sold');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categorys');
            $table->foreign('shop_id')->references('id')->on('shops');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
