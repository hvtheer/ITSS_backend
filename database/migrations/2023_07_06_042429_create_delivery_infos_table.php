<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryInfosTable extends Migration
{
    public function up()
    {
        Schema::create('delivery_infos', function (Blueprint $table) {
            $table->id();
            $table->string('receiver_name');
            $table->string('numberPhone', 10);
            $table->string('note', 200)->nullable();
            $table->string('address', 100);
            $table->decimal('shipping_fee', 8, 2)->default('30000');
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_infos');
    }
}
