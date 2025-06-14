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
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('comment_status')->default(true);
            $table->string('status')->default('draft'); // draft, published, archived
            $table->string('visibility')->default('public'); // public, private, unlisted
            $table->string('password')->nullable();
            $table->dateTime('published_at')->nullable();

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
