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
        Schema::create('user_personal_infos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('account_type')->default('individual'); // e.g., 'individual', 'business', 'government'
            $table->string('user_name')->unique()->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('display_name')->nullable();
            $table->string('gender')->nullable();


            $table->text('profile_image')->nullable();
            $table->text('cover_image')->nullable();


            $table->string('father_name')->nullable();
            $table->string('father_last_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_last_name')->nullable();


            $table->text('short_bio')->nullable();
            $table->text('long_bio')->nullable();

            $table->string('marital_status')->nullable(); // e.g., 'single', 'married', 'divorced', 'widowed'
            $table->string('identity_number')->nullable(); // e.g., national ID, passport number
            $table->string('national_id')->nullable(); // e.g., national ID number

            $table->string('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable(); // e.g., 'New York, USA', 'Kabul, Afghanistan'
            $table->text('born_city')->nullable();
            $table->text('live_city')->nullable();


            $table->string('phone_number')->nullable();
            $table->text('home_address')->nullable();


            $table->string('social_security_number')->nullable(); // e.g., social security number
            $table->string('tax_id_number')->nullable(); // e.g., tax identification number

            $table->string('occupation')->nullable(); // e.g., 'engineer', 'doctor', 'teacher'
            $table->string('job_title')->nullable(); // e.g., 'Software Engineer', 'Project Manager'
            $table->string('job_type')->nullable(); // e.g., 'full-time', 'part-time', 'contractor'
            $table->string('job_location')->nullable(); // e.g., 'New York, USA', 'Remote'
            $table->string('job_department')->nullable(); // e.g., 'Engineering', 'Marketing'
            $table->string('job_experience_years')->nullable(); // e.g., '5 years', '10 years'
            $table->string('job_skills')->nullable(); // e.g., 'PHP, Laravel, JavaScript'
            $table->string('job_industry')->nullable(); // e.g., 'Technology', 'Healthcare'
            $table->string('job_company')->nullable(); // e.g., 'Tech Solutions Inc.', 'HealthCare Ltd.'
            $table->string('job_company_address')->nullable(); // e.g., '123 Main St, New York, NY'
            $table->string('job_company_phone')->nullable(); // e.g., '+1 234 567 8900'


            $table->text('university_name')->nullable();
            $table->text('education_level')->nullable();
            $table->text('education_field')->nullable(); // e.g., 'Computer Science', 'Business Administration'
            $table->text('education_degree')->nullable(); // e.g., 'Bachelor', 'Master', 'PhD'
            $table->text('education_institution')->nullable(); // e.g., 'Harvard University', 'Stanford University'
            $table->text('education_start_date')->nullable(); // e.g., '2010-09-01'
            $table->text('education_end_date')->nullable(); // e.g., '2014-06-30'
            $table->text('education_grade')->nullable(); // e.g., 'A', 'B+', '3.5 GPA'
            $table->text('education_country')->nullable(); // e.g., 'USA', 'Afghanistan'
            $table->text('education_city')->nullable(); // e.g., 'New York', 'Kabul'
            $table->text('education_major')->nullable(); // e.g., 'Software Engineering', 'Business Management'
            $table->text('education_minor')->nullable(); // e.g., 'Data Science', 'Marketing'
            $table->text('education_certification')->nullable(); // e.g., 'Certified Java Developer', 'Project Management Professional'
            $table->text('education_start_year')->nullable(); // e.g., '2010'
            $table->text('education_end_year')->nullable(); // e.g., '2014'
            $table->text('education_description')->nullable(); // e.g., 'Studied computer science with a focus on software development.'
            $table->text('education_document')->nullable(); // e.g., 'Bachelor of Science in Computer Science'
            $table->text('education_document_url')->nullable(); // URL to the education document
            $table->text('education_document_type')->nullable(); // e.g., 'diploma', 'transcript', 'certificate'
            $table->text('education_document_number')->nullable(); // e.g., '1234567890'
            $table->text('education_document_issue_date')->nullable(); // e.g., '2014-06-30'
            $table->text('education_document_expiry_date')->nullable(); // e.g., '2024-06-30'
            $table->text('education_document_issuer')->nullable(); // e.g., 'Harvard University', 'Stanford University'
            $table->text('education_document_issuer_address')->nullable(); // e.g., '123 Main St, Cambridge, MA'
            $table->text('education_document_issuer_phone')->nullable(); // e.g., '+1 234 567 8900'


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_personal_infos');
    }
};
