<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_seos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('category_id');
            $table->foreign('category_id')->references('id')->on('post_categories')->onDelete('cascade');

            $table->string('meta_title', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url', 255)->nullable();
            $table->string('robots_index', 50)->default('index');
            $table->string('robots_follow', 50)->default('follow');
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_type', 50)->default('website');
            $table->string('og_url', 255)->nullable();
            $table->string('og_site_name', 255)->nullable();
            $table->string('og_image', 255)->nullable();
            $table->string('og_image_alt', 255)->nullable();
            $table->integer('og_image_width')->nullable();
            $table->integer('og_image_height')->nullable();
            $table->string('og_locale', 10)->default('fa_IR');
            $table->string('twitter_card', 50)->default('summary_large_image');
            $table->text('twitter_description')->nullable();
            $table->string('twitter_site', 255)->nullable();
            $table->string('twitter_creator', 255)->nullable();
            $table->string('twitter_image', 255)->nullable();
            $table->string('generator', 255)->default('Spital CMS, Created By Abrecode.com');

            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_seos');
    }
};
