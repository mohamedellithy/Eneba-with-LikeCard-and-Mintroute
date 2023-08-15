<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eneba_orders', function (Blueprint $table) {
            $table->id();
            $table->text('order_id')->nullable();
            $table->string('status_order')->nullable();
            $table->text('auctions');
            $table->text('product_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eneba_orders');
    }
};
