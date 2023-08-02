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
        Schema::create('offline_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->text('product_name')->nullable();
            $table->text('product_image')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('product_type');
            $table->text('code');
            $table->string('status')->default('allow');
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
        Schema::dropIfExists('offline_codes');
    }
};
