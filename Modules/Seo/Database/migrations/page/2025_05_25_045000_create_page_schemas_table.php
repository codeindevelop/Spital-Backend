// database/migrations/2025_06_12_000004_create_page_schemas_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_schemas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('page_id');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            $table->string('type')->nullable(); // نوع Schema (مثل Organization, FAQPage)
            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('content')->nullable();
            $table->text('description')->nullable();
            $table->json('data')->nullable(); // داده‌های Schema
            $table->json('schema_json')->nullable(); // JSON-LD نهایی

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
        Schema::dropIfExists('page_schemas');
    }
};
