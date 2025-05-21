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
        Schema::create('course_quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');


            $table->text('quiz_title');

            $table->text('quiz_question');
            $table->text('quiz_res_1');
            $table->text('quiz_res_2');
            $table->text('quiz_res_3');
            $table->text('quiz_res_4');

            $table->text('quiz_right_answer');

            $table->boolean('active');


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cource_quizzes');
    }
};
