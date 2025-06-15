<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('post_id');
            $table->uuid('parent_id')->nullable();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('post_comments')->onDelete('cascade');

            $table->string('author_name', 255)->nullable();
            $table->string('author_email', 255)->nullable();
            $table->string('author_url', 255)->nullable();
            $table->string('author_ip', 45)->nullable();
            $table->text('content');
            $table->string('status')->default('pending'); // pending, approved, spam

            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_comments');
    }
};
