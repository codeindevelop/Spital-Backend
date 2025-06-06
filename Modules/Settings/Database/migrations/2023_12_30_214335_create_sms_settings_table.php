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
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('send_sms_company');
            $table->string('send_sms_server');
            $table->string('send_sms_number')->nullable();
            $table->string('receive_sms_number')->nullable();
            $table->string('api_key');
            $table->boolean('active');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_settings');
    }
};
