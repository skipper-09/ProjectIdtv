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
        Schema::create('detail_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcription_id');
            $table->unsignedBigInteger('feeclaim_id');
            $table->timestamps();
            $table->foreign('subcription_id')->references('id')->on('subscriptions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('feeclaim_id')->references('id')->on('fee_claims')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_claims');
    }
};
