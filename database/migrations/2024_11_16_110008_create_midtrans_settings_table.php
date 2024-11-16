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
        Schema::create('midtrans_settings', function (Blueprint $table) {
            $table->id();
            $table->string('client_key')->nullable();
            $table->string('server_key')->nullable();
            $table->string('url')->nullable();
            $table->boolean('production')->default(false);
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
        Schema::dropIfExists('midtrans_settings');
    }
};
