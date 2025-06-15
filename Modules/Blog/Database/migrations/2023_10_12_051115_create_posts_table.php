<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('author_id');
            $table->uuid('category_id')->nullable();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('post_categories')->onDelete('set null');

            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('short_link')->nullable()->unique();
            $table->enum('post_type', ['article', 'video', 'audio'])->default('article');
            $table->string('media_link')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();

            $table->uuid('cover_image_id')->nullable();
            $table->string('cover_image_name')->nullable();
            $table->string('cover_image_alt')->nullable();
            $table->string('cover_image_preview')->nullable();
            $table->unsignedInteger('cover_image_width')->nullable();
            $table->unsignedInteger('cover_image_height')->nullable();

            $table->boolean('comment_status')->default(true);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->string('status')->default('draft'); // draft, published, archived
            $table->string('visibility')->default('public'); // public, private, unlisted
            $table->string('password')->nullable();
            $table->dateTime('published_at')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trend')->default(false);
            $table->boolean('is_advertisement')->default(false);
            $table->unsignedInteger('reading_time')->nullable(); // به دقیقه

            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->boolean('is_active')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
