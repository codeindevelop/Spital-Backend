<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('social_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('instagram')->nullable();
            $table->string('telegram')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('whatsapp_business')->nullable();
            $table->string('facebook_user')->nullable();
            $table->string('facebook_page')->nullable();
            $table->string('twitter')->nullable();
            $table->string('skype')->nullable();
            $table->string('youtube')->nullable();
            $table->string('aparat')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('pintrest')->nullable();
            $table->string('linkdin')->nullable();
            $table->string('dribbble')->nullable();
            $table->string('snapchat')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_settings');
    }
};
