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
        Schema::create('user_personal_infos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


            $table->string('display_name')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('father_name')->nullable();

            $table->string('gender')->nullable();


            $table->string('phone_number')->nullable();
            $table->text('home_address')->nullable();


            $table->string('team_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('marriage_type')->nullable();


            $table->text('short_bio')->nullable();
            $table->text('long_bio')->nullable();
            $table->text('born_city')->nullable();
            $table->text('live_city')->nullable();
            $table->text('education_level')->nullable();
            $table->text('university_name')->nullable();

            $table->text('profile_image')->nullable();
            $table->text('cover_image')->nullable();


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_personal_infos');
    }
};
