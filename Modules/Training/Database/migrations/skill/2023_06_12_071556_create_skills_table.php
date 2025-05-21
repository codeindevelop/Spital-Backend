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
        Schema::create('skills', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('category_id');
            $table->foreign('category_id')->references('id')->on('skill_categories')->onDelete('cascade');

            $table->string('skill_name');
            $table->string('requirement_skills');
            $table->integer('job_level'); // level az 10 shoro mishe - mizane kar dar bazar az 1 ta 10
            $table->longText('description')->nullable();
            $table->longText('day_need_to_learn')->nullable(); // dar chand rooz mishe yadgereft
            $table->longText('minimum_salary')->nullable(); // mizane hogooge pardakhti be in takhasos
            $table->integer('migration_level')->nullable(); // level mohajerat ba in takhasos
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->longText('software_requirement')->nullable(); // narmafzare morede niaz baraye kar ba in takhasos
            $table->longText('used_platform')->nullable(); // morede estefade dar mobile ya web va ...
            $table->boolean('active');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
