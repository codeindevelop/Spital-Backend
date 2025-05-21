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
        Schema::create('training_paths', function (Blueprint $table) {
            $table->uuid('id')->primary();


            $table->string('path_name');
            $table->string('path_target_tech'); // in masir baraye che technology hast - web ya mobile
            $table->longText('short_desc')->nullable();
            $table->longText('long_desc')->nullable();
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_paths');
    }
};
