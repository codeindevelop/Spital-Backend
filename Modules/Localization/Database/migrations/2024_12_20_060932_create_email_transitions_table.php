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
        Schema::create('email_translations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('language_id');
            $table->foreign('language_id')->references('id')->on('languages');

            $table->string('name');
            $table->text('subject')->nullable();
            $table->text('content')->nullable();
            $table->string('property')->nullable();


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_transitions');
    }
};
