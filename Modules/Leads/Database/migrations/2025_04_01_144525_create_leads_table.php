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
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Lead Assigned To Admin or User
            $table->uuid('staff_id');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');

            $table->uuid('source_id');
            $table->foreign('source_id')->references('id')->on('lead_sources')->onDelete('cascade');

            $table->uuid('status_id');
            $table->foreign('status_id')->references('id')->on('lead_statuses')->onDelete('cascade');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('company_name')->nullable();
            $table->longText('description')->nullable();
            $table->longText('tags')->nullable();
            $table->boolean('is_public')->default(false);

            $table->boolean('send_welcome_sms')->nullable()->default(false);
            $table->boolean('send_welcome_email')->nullable()->default(false);
            $table->boolean('active');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
