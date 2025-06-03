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
        Schema::create('page_schemas', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Primary key for the schema table

            $table->uuid('page_id'); // Foreign key to the pages table
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->uuid('created_by')->nullable(); // Foreign key to the users table for the creator
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade'); // Cascade delete on user deletion


            $table->string('title')->nullable(); // Title of the schema
            $table->string('slug')->unique(); // Unique slug for the schema
            $table->text('content')->nullable(); // Content of the schema
            $table->text('description')->nullable(); // Description of the schema
            $table->string('category')->nullable(); // Category of the schema
            $table->string('tags')->nullable(); // Comma-separated tags for the schema

            $table->string('status')->default('draft'); // Status of the schema, default is 'draft'
            $table->string('visibility')->default('public'); // Visibility of the schema, default is 'public'
            $table->string('access_level')->default('everyone'); // Access level for the schema, default is 'everyone'

            $table->string('type')->default('schema'); // Type of the schema, default is 'schema'
            $table->string('version')->default('1.0'); // Version of the schema
            $table->integer('order')->default(0); // Order of the schema, default is 0

            $table->string('keywords')->nullable(); // Keywords associated with the schema
            $table->string('language')->default('en'); // Default language for the schema
            $table->string('region')->nullable(); // Optional region for localization
            $table->string('timezone')->default('UTC'); // Default timezone for the schema


            $table->string('icon')->nullable(); // Icon associated with the schema
            $table->string('featured_image')->nullable(); // Featured image for the schema
            $table->string('thumbnail_image')->nullable(); // Thumbnail image for the schema
            $table->string('banner_image')->nullable(); // Banner image for the schema

            $table->string('video_url')->nullable(); // Video URL associated with the schema
            $table->string('audio_url')->nullable(); // Audio URL associated with the schema
            $table->string('external_link')->nullable(); // External link associated with the schema


            $table->string('template')->nullable(); // Template used for the schema
            $table->string('layout')->default('default'); // Layout type for the schema, default is 'default'
            $table->string('theme')->default('default'); // Theme used for the schema, default is 'default'

            $table->string('author')->nullable(); // Author of the schema
            $table->string('publisher')->nullable(); // Publisher of the schema
            $table->string('date_published')->nullable(); // Date when the schema was published
            $table->string('date_modified')->nullable(); // Date when the schema was last modified


            $table->text('schema_data')->nullable(); // JSON-LD schema data
            $table->text('schema_markup')->nullable(); // Schema markup in HTML format
            $table->text('schema_json')->nullable(); // Schema data in JSON format
            $table->text('schema_rich_snippet')->nullable(); // Rich snippet data for the schema
            $table->text('schema_microdata')->nullable(); // Microdata format for the schema
            $table->text('schema_opengraph')->nullable(); // Open Graph data for the schema
            $table->text('schema_twitter_card')->nullable(); // Twitter Card data for the schema
            $table->text('schema_json_ld')->nullable(); // JSON-LD format for the schema
            $table->text('schema_rdfa')->nullable(); // RDFa format for the schema
            $table->text('schema_knowledge_graph')->nullable(); // Knowledge Graph data for the schema
            $table->text('schema_breadcrumb')->nullable(); // Breadcrumb schema data
            $table->text('schema_faq')->nullable(); // FAQ schema data
            $table->text('schema_event')->nullable(); // Event schema data
            $table->text('schema_product')->nullable(); // Product schema data
            $table->text('schema_article')->nullable(); // Article schema data
            $table->text('schema_local_business')->nullable(); // Local Business schema data
            $table->text('schema_person')->nullable(); // Person schema data
            $table->text('schema_organization')->nullable(); // Organization schema data
            $table->text('schema_website')->nullable(); // Website schema data
            $table->text('schema_video')->nullable(); // Video schema data
            $table->text('schema_audio')->nullable(); // Audio schema data
            $table->text('schema_image')->nullable(); // Image schema data
            $table->text('schema_review')->nullable(); // Review schema data
            $table->text('schema_recipe')->nullable(); // Recipe schema data
            $table->text('schema_job_posting')->nullable(); // Job Posting schema data
            $table->text('schema_aggregate_rating')->nullable(); // Aggregate Rating schema data
            $table->text('schema_software_app')->nullable(); // Software Application schema data
            $table->text('schema_service')->nullable(); // Service schema data
            $table->text('schema_corporate_contact')->nullable(); // Corporate Contact schema data
            $table->text('schema_contact_point')->nullable(); // Contact Point schema data
            $table->text('schema_sitelinks_searchbox')->nullable(); // Sitelinks Searchbox schema data
            $table->text('schema_sitelinks')->nullable(); // Sitelinks schema data
            $table->text('schema_sitemap')->nullable(); // Sitemap schema data
            $table->text('schema_sitemap_index')->nullable(); // Sitemap Index schema data
            $table->text('schema_sitemap_image')->nullable(); // Sitemap Image schema data
            $table->text('schema_sitemap_video')->nullable(); // Sitemap Video schema data
            $table->text('schema_sitemap_news')->nullable(); // Sitemap News schema data
            $table->text('schema_sitemap_mobile')->nullable(); // Sitemap Mobile schema data
            $table->text('schema_sitemap_web')->nullable(); // Sitemap Web schema data
            $table->text('schema_sitemap_other')->nullable(); // Other Sitemap schema data
            $table->text('schema_custom')->nullable(); // Custom schema data for additional formats
            $table->text('schema_custom_json')->nullable(); // Custom schema data in JSON format
            $table->text('schema_custom_html')->nullable(); // Custom schema data in HTML format
            $table->text('schema_custom_rdfa')->nullable(); // Custom schema data in RDFa format
            $table->text('schema_custom_microdata')->nullable(); // Custom schema data in Microdata format
            $table->text('schema_custom_opengraph')->nullable(); // Custom Open Graph schema data


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
        Schema::dropIfExists('page_schemas');
    }
};
