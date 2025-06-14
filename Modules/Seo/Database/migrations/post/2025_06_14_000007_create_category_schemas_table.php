<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_schemas', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('category_id');
            $table->foreign('category_id')->references('id')->on('post_categories')->onDelete('cascade');

            $table->string('type')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->unique()->nullable();
            $table->text('content')->nullable();
            $table->text('description')->nullable();
            $table->json('data')->nullable();
            $table->json('schema_json')->nullable();
            $table->string('status')->default('draft');
            $table->string('visibility')->default('public');
            $table->string('language')->default('fa')->nullable();
            $table->string('author')->nullable();

            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_schemas');
    }
};
