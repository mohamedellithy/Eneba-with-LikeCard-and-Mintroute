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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->text('auction');
            $table->text('product_id');
            $table->integer('status')->default(0);
            $table->float('min_price')->nullable();
            $table->float('max_price')->nullable();
            $table->float('current_price')->nullable();
            $table->integer('automation')->default(0);
            $table->string('change_time')->nullable();
            $table->float('price_step')->default('0.01');
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
        Schema::dropIfExists('auctions');
    }
};
