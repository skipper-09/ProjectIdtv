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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('packet_id');
            $table->unsignedBigInteger('reseller_package_id')->nullable();
            $table->string('invoices');
            $table->date('start_date')->nullable();
            $table->date('end_date');
            $table->decimal('fee',10,2)->nullable();
            $table->decimal('tagihan',10,2)->nullable();
            $table->string('midtras_random')->nullable();
            $table->string('midtras_link')->nullable();
            $table->boolean('is_claim')->default(false);
            $table->boolean('status')->default(true);
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('packet_id')->references('id')->on('packages')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('reseller_package_id')->references('id')->on('reseller_pakets')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('subscriptions');
    }
};
