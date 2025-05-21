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
        Schema::create('user_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();


            $table->uuid('target_user_id');
            $table->foreign('target_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->uuid('reporter_user_id');
            $table->foreign('reporter_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('report_subject');
            $table->text('report_text');
            $table->text('status'); // under review - rejected - complete
            $table->boolean('active');

            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reports');
    }
};
