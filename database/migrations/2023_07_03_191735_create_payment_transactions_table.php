<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('card_number');
            $table->unsignedBigInteger('delivery_info_id');
            $table->timestamps();

            $table->foreign('delivery_info_id')->references('id')->on('delivery_infos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
}
