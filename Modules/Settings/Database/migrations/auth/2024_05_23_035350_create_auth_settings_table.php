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
        Schema::create('auth_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('users_can_login');
            $table->boolean('users_can_signup');
            $table->boolean('admins_can_login');
            $table->boolean('admins_can_signup');
            $table->string('users_sigup_type');
            $table->string('users_login_type');
            $table->string('admins_sigup_type');
            $table->string('admins_login_type');
            $table->boolean('enable_reset_password');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_settings');
    }
};
