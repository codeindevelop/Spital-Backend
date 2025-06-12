<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seo_general_settings', function (Blueprint $table) {

            $table->ulid('id')->primary();
            $table->string('site_name')->default('My Site');
            $table->string('site_alternative_name')->nullable();
            $table->string('site_slogan')->nullable();
            $table->string('og_image')->nullable();
            $table->string('title_separator')->default('-');


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_general_settings');
    }
};
