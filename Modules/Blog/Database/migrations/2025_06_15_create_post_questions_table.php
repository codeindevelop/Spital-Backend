<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('post_id');
            $table->uuid('parent_id')->nullable();
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->string('author_url')->nullable();
            $table->string('author_ip');
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'spam'])->default('pending');
            $table->uuid('created_by');
            $table->uuid('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // اضافه کردن کلید خارجی برای parent_id به‌صورت جداگانه
        Schema::table('post_questions', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('post_questions')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('post_questions', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
        Schema::dropIfExists('post_questions');
    }
};
