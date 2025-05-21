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
        Schema::create('path_course_ids', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('path_id');
            $table->foreign('path_id')->references('id')->on('training_paths')->onDelete('cascade');

            $table->uuid('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');


            $table->boolean('active');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('path_course_ids');
    }
};
