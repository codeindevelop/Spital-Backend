<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('timezone_id');
            $table->uuid('language_id');
            $table->uuid('country_id');

            $table->string('site_name')->nullable();
            $table->string('site_desc')->nullable();

            $table->boolean('maintenance_mode')->default(false);

            $table->string('user_panel_url')->nullable();

            $table->foreign('timezone_id')->references('id')->on('time_zones')->onDelete('restrict');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('restrict');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict');

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
