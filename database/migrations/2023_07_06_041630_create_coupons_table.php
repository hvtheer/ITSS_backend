<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->enum('type', ['fixed', 'percent']);
            $table->decimal('discounted_amount', 8, 2);
            $table->integer('quantity');
            $table->unsignedBigInteger('created_by');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->boolean('deleted')->default(false);
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
