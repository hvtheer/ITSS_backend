<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('shop_id');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('thumbnail');
            $table->integer('sold_quantity')->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->decimal('avg_rating', 3, 2)->default('0');
            $table->boolean('deleted')->default(false);
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
