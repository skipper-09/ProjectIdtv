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
            $table->foreignId('id_kateogri')->constrained(table: 'categoris', column: 'id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name',length : 50);
            $table->string('url');
            $table->string('logo');
            $table->string('user_agent');
            $table->enum('type',['m3u','mpd']);
            $table->enum('security_type',['clearkey','widevine'])->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('chanels');
    }
};
