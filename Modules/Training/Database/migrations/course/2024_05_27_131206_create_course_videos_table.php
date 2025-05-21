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
        Schema::create('course_videos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->uuid('course_season_id');
            $table->foreign('course_season_id')->references('id')->on('course_seasons')->onDelete('cascade');

            $table->uuid('course_episode_id');
            $table->foreign('course_episode_id')->references('id')->on('course_episodes')->onDelete('cascade');


            $table->string('video_name');
            $table->longText('video_desc')->nullable();
            $table->string('video_link');
            $table->string('video_format');
            $table->string('video_size')->nullable();
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
        Schema::dropIfExists('course_videos');
    }
};
