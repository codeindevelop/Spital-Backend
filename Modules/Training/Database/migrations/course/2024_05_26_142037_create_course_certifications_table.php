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
        Schema::create('course_certifications', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            $table->uuid('certificate_id');
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');


            $table->string('certificate_text')->nullable();
            $table->string('certificate_desc')->nullable();
            $table->boolean('active');

            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cource_certifications');
    }
};
