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
        Schema::create('fee_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reseller_id');
            $table->integer('amount');
            $table->enum('status',['pending','aproved','rejected'])->default('pending');
            $table->string('bukti_tf')->nullable();
            $table->timestamps();
            $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_claims');
    }
};
