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
        Schema::create('certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('certificate_name');

            $table->string('certificate_subject');
            $table->string('institution_name');

            $table->longText('description')->nullable();

            $table->longText('certificate_body_text')->nullable();
            $table->longText('certificate_signature_text')->nullable();

            $table->string('certificate_image')->nullable();
            $table->string('certificate_video')->nullable();
            $table->boolean('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
