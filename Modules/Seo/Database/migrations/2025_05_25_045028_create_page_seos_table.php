<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_seo', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('page_id');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            $table->string('meta_title', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical_url', 255)->nullable();
            $table->string('image')->nullable();
            $table->enum('robots_index', ['index', 'noindex'])->default('index')->nullable();
            $table->enum('robots_follow', ['follow', 'nofollow'])->default('follow')->nullable();
            $table->string('theme_color', 7)->nullable();
            $table->string('language', 10)->default('fa')->nullable();
            $table->string('region', 255)->nullable();
            $table->string('timezone', 255)->default('Asia/Tehran')->nullable();
            $table->string('author', 255)->nullable();

            // Open Graph
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 255)->nullable();
            $table->string('og_type', 255)->default('website')->nullable();
            $table->string('og_url', 255)->nullable();
            $table->string('og_site_name', 255)->nullable();
            $table->string('og_locale', 10)->default('fa_IR')->nullable();
            $table->string('og_image_alt', 255)->nullable();
            $table->integer('og_image_width')->nullable();
            $table->integer('og_image_height')->nullable();
            $table->string('og_image_type', 255)->nullable();

            // Twitter Card
            $table->string('twitter_card', 255)->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_site', 255)->nullable();
            $table->string('twitter_creator', 255)->nullable();
            $table->string('twitter_image', 255)->nullable();
            $table->string('twitter_card_type', 255)->default('summary_large_image')->nullable();
            $table->string('twitter_site_handle', 255)->nullable();
            $table->string('twitter_creator_handle', 255)->nullable();
            $table->string('twitter_image_alt', 255)->nullable();
            $table->integer('twitter_image_width')->nullable();
            $table->integer('twitter_image_height')->nullable();

            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_seo');
    }
};
