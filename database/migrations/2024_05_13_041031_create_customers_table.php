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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('stb_id');
            $table->unsignedBigInteger('packet_id');
            $table->string('mac');
            $table->string('nik');
            $table->string('name', length: 100);
            $table->string('phone', length: 13);
            $table->longText('address');
            $table->string('username')->unique();
            $table->string('showpassword');
            $table->string('password');
            $table->string('device_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('stb_id')->references('id')->on('stbs')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('packet_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
