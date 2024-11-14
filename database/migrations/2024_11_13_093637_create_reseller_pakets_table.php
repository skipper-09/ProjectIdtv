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
        Schema::create('reseller_pakets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paket_id');
            $table->unsignedBigInteger('reseller_id');
            $table->string('name');
            $table->decimal('price', 10, 0);
            $table->boolean('status')->default(true);
            $table->foreign('paket_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('reseller_id')->references('id')->on('resellers')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('reseller_pakets');
    }
};
