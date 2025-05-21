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
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('category_id');
            $table->foreign('category_id')->references('id')->on('course_categories')->onDelete('cascade');

            $table->uuid('instructor_id');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');


            $table->string('course_title');
            $table->string('course_slug');
            $table->longText('short_desc');
            $table->longText('longdesc')->nullable();
            $table->string('status');
            $table->text('keywords')->nullable();

            $table->string('course_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('course_intro_video')->nullable();
            $table->string('course_time')->nullable();
            $table->boolean('need_requirement');
            $table->string('course_type')->nullable(); // nagdi - vijeh
            $table->string('course_level')->nullable(); // mogadamati - herfeii


            $table->boolean('is_special')->default(false);
            $table->string('price')->nullable();
            $table->boolean('active');


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
