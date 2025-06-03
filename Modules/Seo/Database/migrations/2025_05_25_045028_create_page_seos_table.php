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
        Schema::create('page_seos', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key for the SEO settings table

            $table->uuid('page_id'); // Foreign key to the pages table
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();

            $table->string('canonical_url')->nullable();
            $table->string('favicon')->nullable(); // Favicon for the page

            $table->string('robots_index')->default('index'); // Default robots index directive
            $table->string('robots_follow')->default('follow'); // Default robots follow directive


            $table->string('theme_color')->nullable(); // Theme color for the page


            $table->string('language')->default('en'); // Default language for SEO settings
            $table->string('region')->nullable(); // Optional region for localization
            $table->string('timezone')->default('UTC'); // Default timezone for SEO settings
            $table->string('author')->nullable(); // Author of the SEO settings


            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('website'); // Default Open Graph type
            $table->string('og_url')->nullable(); // Open Graph URL
            $table->string('og_site_name')->nullable(); // Open Graph site name
            $table->string('og_locale')->default('en_US'); // Default Open Graph locale
            $table->string('og_image_alt')->nullable(); // Alternative text for Open Graph image
            $table->string('og_image_width')->nullable(); // Width of Open Graph image
            $table->string('og_image_height')->nullable(); // Height of Open Graph image
            $table->string('og_image_type')->default('image/jpeg'); // Default Open Graph image type


            $table->string('twitter_title')->nullable();
            $table->string('twitter_description')->nullable();
            $table->string('twitter_site')->nullable(); // Twitter site handle
            $table->string('twitter_creator')->nullable(); // Twitter creator handle
            $table->string('twitter_image')->nullable();
            $table->string('twitter_card_type')->default('summary_large_image'); // Default Twitter card type
            $table->string('twitter_site_handle')->nullable(); // Twitter site handle
            $table->string('twitter_creator_handle')->nullable(); // Twitter creator handle
            $table->string('twitter_image_alt')->nullable(); // Alternative text for Twitter image
            $table->string('twitter_image_width')->nullable(); // Width of Twitter image
            $table->string('twitter_image_height')->nullable(); // Height of Twitter image

            $table->boolean('is_active')->default(true); // Active status for SEO settings

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_seos');
    }
};
