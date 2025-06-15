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
        Schema::create('blog_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->unsignedInteger('public_posts_per_page')->default(15);
            $table->unsignedInteger('admin_posts_per_page')->default(15);

            $table->string('font_family')->default('estedad');
            $table->string('default_cover_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_settings');
    }
};
