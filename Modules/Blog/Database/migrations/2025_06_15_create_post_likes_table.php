<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('post_likes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('post_id');
            $table->uuid('user_id');

            $table->timestamp('liked_at')->useCurrent();
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['post_id', 'user_id']); // جلوگیری از لایک تکراری
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_likes');
    }
};
