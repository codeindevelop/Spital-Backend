<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('set null');

            $table->string('title')->unique();
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();

            $table->integer('order')->default(0);
            $table->string('template')->nullable();

            $table->string('status')->default('draft'); // draft, published, archived
            $table->string('visibility')->default('public'); // public, private, unlisted
            $table->string('password')->nullable(); // برای صفحات رمزدار

            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->uuid('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
