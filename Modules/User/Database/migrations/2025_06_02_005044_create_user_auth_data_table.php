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
        Schema::create('user_auth_data', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('register_ip')->nullable();
            $table->string('register_device')->nullable(); // e.g., 'mobile', 'desktop', 'tablet'
            $table->string('register_browser')->nullable(); // e.g., 'Chrome', 'Firefox', 'Safari'
            $table->string('register_location')->nullable(); // e.g., 'New York, USA'
            $table->timestamp('registered_at')->nullable();

            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_method')->nullable(); // e.g., 'email', 'mobile', 'social'
            $table->string('last_login_ip')->nullable();
            $table->string('last_login_device')->nullable(); // e.g., 'mobile', 'desktop', 'tablet'
            $table->string('last_login_browser')->nullable(); // e.g., 'Chrome', 'Firefox', 'Safari'
            $table->string('last_login_location')->nullable(); // e.g., 'New York, USA'

            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('banned_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_auth_data');
    }
};
