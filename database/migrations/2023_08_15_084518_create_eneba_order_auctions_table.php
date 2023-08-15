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
        Schema::create('eneba_order_auctions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eneba_order_id');
            $table->foreign('eneba_order_id')->on('eneba_orders')->references('id');
            $table->unsignedBigInteger('eneba_auction_id');
            $table->foreign('eneba_auction_id')->on('auctions')->references('id');
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
        Schema::dropIfExists('eneba_order_auctions');
    }
};
