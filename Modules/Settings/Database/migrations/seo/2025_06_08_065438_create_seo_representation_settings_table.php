<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_representation_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('site_type')->default('personal'); // personal or company
            $table->string('company_name')->nullable();
            $table->string('company_alternative_name')->nullable();
            $table->string('company_logo')->nullable();
            $table->text('company_description')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_legal_name')->nullable();
            $table->date('company_founded_date')->nullable();
            $table->integer('company_employee_count')->nullable();
            $table->integer('company_branch_count')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_ceo')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_telegram')->nullable();
            $table->string('social_tiktok')->nullable();
            $table->string('social_snapchat')->nullable();
            $table->string('social_threads')->nullable();
            $table->string('social_github')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_pinterest')->nullable();
            $table->string('social_wikipedia')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_representation_settings');
    }
};
