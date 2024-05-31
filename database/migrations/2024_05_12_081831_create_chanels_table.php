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
        Schema::create('chanels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categori_id');
            $table->string('name', length: 50);
            $table->string('url');
            $table->string('logo');
            $table->string('user_agent')->nullable();
            $table->enum('type', ['m3u', 'mpd'])->nullable();
            $table->enum('security_type', ['clearkey', 'widevine'])->nullable();
            $table->string('security')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->foreign('categori_id')->references('id')->on('categoris')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chanels');
    }
};
