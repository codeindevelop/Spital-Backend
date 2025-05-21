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
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->uuid('student_group_id');
            $table->foreign('student_group_id')->references('id')->on('student_groups')->onDelete('cascade');

            $table->uuid('level_id');
            $table->foreign('level_id')->references('id')->on('student_levels')->onDelete('cascade');


            $table->longText('status')->nullable();
            $table->longText('profile_img')->nullable();
            $table->longText('cover_img')->nullable();

            $table->longText('about_student')->nullable();
            $table->longText('company_name')->nullable();
            $table->longText('location_name')->nullable();

            $table->longText('instagram_id')->nullable();
            $table->longText('x_id')->nullable();
            $table->longText('github_id')->nullable();
            $table->longText('gitlab_id')->nullable();
            $table->longText('bitbucket_id')->nullable();
            $table->longText('website_url')->nullable();


            $table->timestamp('register_datetime')->nullable();
            $table->timestamp('canceling_datetime')->nullable();

            $table->string('job_name')->nullable();
            $table->longText('description')->nullable();

            $table->boolean('active')->default(true);


            // if any user has referred
            $table->string('referer_code')->nullable();
            $table->string('referer_name')->nullable();
            $table->string('referer_mobile_number')->nullable();
            $table->string('referer_email')->nullable();

            $table->timestamps();
            $table->softDeletes();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
