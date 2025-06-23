<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_id')->nullable();

            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
// اضافه کردن کلید خارجی برای parent_id به‌صورت جداگانه
        Schema::table('post_categories', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('post_categories')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

    }

    public function down(): void
    {
        // حذف کلید خارجی قبل از حذف جدول
        Schema::table('post_categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
        Schema::dropIfExists('post_categories');
    }
};
