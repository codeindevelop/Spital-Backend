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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('send_email_company');
            $table->string('send_email_host');
            $table->string('email_protocol');
            $table->string('email_encryption');
            $table->string('send_email_port');
            $table->string('send_email_name');
            $table->string('send_email_url');
            $table->string('smtp_username');
            $table->string('smtp_password');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
