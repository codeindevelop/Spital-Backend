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
        Schema::create('instructors', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


            $table->string('instructor_slug');


            $table->string('link_instagram')->nullable();
            $table->string('link_facebook')->nullable();
            $table->string('link_linkedin')->nullable();
            $table->string('link_twitter')->nullable();
            $table->string('link_github')->nullable();
            $table->string('link_youtube')->nullable();
            $table->string('link_website')->nullable();

            $table->longText('skills')->nullable();
            $table->longText('short_biography')->nullable();
            $table->mediumText('biography')->nullable();
            $table->string('instructor_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
