<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('gateway_name');
            $table->longText('description')->nullable();
            $table->string('merchant_id');
            $table->string('request_url')->nullable();
            $table->string('start_url');
            $table->string('verify_url');
            $table->string('inquiry_url')->nullable();
            $table->string('call_back_url');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
