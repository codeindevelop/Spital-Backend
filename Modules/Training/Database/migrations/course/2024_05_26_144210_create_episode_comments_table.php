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
        Schema::create('episode_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('episode_id');
            $table->foreign('episode_id')->references('id')->on('course_episodes')->onDelete('cascade');

            $table->uuid('commented_user');
            $table->foreign('commented_user')->references('id')->on('users')->onDelete('cascade');


            $table->integer('comment_parent')->nullable();

            $table->string('comment_author_IP')->nullable();
            $table->longText('comment_content');
            $table->string('like_number');

            $table->boolean('comment_approved');

            $table->boolean('active');

            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episode_comments');
    }
};
