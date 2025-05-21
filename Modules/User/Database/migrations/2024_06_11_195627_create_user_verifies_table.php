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
        Schema::create('user_verifies', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('shenasname_no')->nullable();
            $table->string('mellicode')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('email_verify_token')->nullable();
            $table->string('mobile_verify_token')->nullable();

            $table->boolean('verify_email')->default(false);
            $table->boolean('verify_mobile_number')->default(false);
            $table->string('passport_number')->nullable();
            $table->string('passport_document')->nullable();
            $table->text('shenasname_document')->nullable();
            $table->text('mellicard_document')->nullable();
            $table->text('mellicard_back_document')->nullable();
            $table->boolean('atbaa_document')->nullable(); // madarke atbaa afghani

            $table->dateTime('verify_dateTime')->nullable();
            $table->dateTime('reject_dateTime')->nullable();
            $table->string('reject_status')->nullable();
            $table->string('status');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_verifies');
    }
};
