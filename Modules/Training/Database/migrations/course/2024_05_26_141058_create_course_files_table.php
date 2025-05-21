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
        Schema::create('course_files', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->uuid('uploader_id');
            $table->foreign('uploader_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('file_name');
            $table->string('file_title');
            $table->string('file_type');
            $table->string('file_url');
            $table->string('file_size');
            $table->string('duration')->nullable();
            $table->text('file_tag')->nullable();
            $table->boolean('active');


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cource_files');
    }
};
