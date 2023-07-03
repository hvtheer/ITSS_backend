<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->enum('type', ['fixed', 'percent']);
            $table->float('value');
            $table->unsignedBigInteger('created_by');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}


