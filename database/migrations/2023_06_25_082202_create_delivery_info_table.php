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
        Schema::create('delivery_info', function (Blueprint $table) {
            $table->id();
            $table->string('receiver_name');
            $table->string('numberPhone');
            $table->string('province');
            $table->text('note');
            $table->text('address');
            $table->float('shipping_fee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_info');
    }
};
