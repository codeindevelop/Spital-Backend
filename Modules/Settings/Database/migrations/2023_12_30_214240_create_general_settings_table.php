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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->unsignedInteger('timezone_id');

            $table->string('portal_name')->nullable();
            $table->string('portal_desc')->nullable();
            $table->integer('time_zone');
            $table->boolean('maintenance_mode')->default(false);
            $table->string('signup_type')->default('email');
            $table->string('user_panel_url')->nullable();

            // $table->foreign('timezone_id')->references('id')->on('time_zones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
