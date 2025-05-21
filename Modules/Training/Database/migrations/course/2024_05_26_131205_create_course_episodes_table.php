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
        Schema::create('course_episodes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('course_season_id');
            $table->foreign('course_season_id')->references('id')->on('course_seasons')->onDelete('cascade');


            $table->string('episode_order_number');
            $table->string('episode_name');
            $table->longText('episode_desc')->nullable();

            $table->string('duration')->nullable();

            $table->boolean('active');


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_episodes');
    }
};
