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
        Schema::create('user_auth_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_method')->nullable(); // e.g., 'sms', 'email', 'authenticator_app'


            $table->boolean('password_reset_enabled')->default(true);
            $table->string('password_reset_method')->default('email'); // e.g., 'email', 'sms'

            $table->boolean('email_verification_required')->default(true);
            $table->boolean('sms_verification_required')->default(false);
            $table->boolean('phone_verification_required')->default(false); // Enable phone verification

            $table->boolean('user_profile_visibility_enabled')->default(true); // Enable user profile visibility settings
            $table->string('user_profile_visibility')->default('public'); // e.g., 'public', 'private', 'friends_only'
            $table->boolean('user_profile_editing_enabled')->default(true); // Enable user profile editing

            $table->boolean('security_questions_enabled')->default(false);
            $table->json('security_questions')->nullable(); // JSON for security questions and answers

            $table->boolean('multi_factor_authentication_enabled')->default(false); // Enable multifactorial authentication
            $table->string('mfa_method')->default('totp'); // e.g., 'totp', 'sms', 'email'

            $table->boolean('account_lockout_enabled')->default(false);
            $table->integer('account_lockout_threshold')->default(5); // Number of failed login attempts before lockout
            $table->integer('account_lockout_duration')->default(15); // Duration in minutes for lockout

            $table->boolean('session_management_enabled')->default(true);
            $table->string('session_management_method')->default('browser'); // e.g., 'browser', 'device'
            $table->text('session_management_devices')->nullable(); // JSON or serialized data for devices used


            $table->boolean('api_access_enabled')->default(false); // Enable API access for the user
            $table->string('api_access_token')->nullable(); // API access token for the user
            $table->string('api_access_token_expiry')->nullable(); // Expiry time for the API access token

            $table->boolean('ip_restriction_enabled')->default(false); // Enable IP restriction for login
            $table->text('allowed_ip_addresses')->nullable(); // Comma-separated list of allowed IP addresses

            $table->boolean('geo_restriction_enabled')->default(false); // Enable geo-restriction for login
            $table->string('geo_restriction_countries')->nullable(); // Comma-separated list of allowed countries


            $table->boolean('data_export_enabled')->default(false); // Enable data export for the user
            $table->string('data_export_method')->default('email'); // e.g., 'email', 'download'
            $table->string('data_export_email')->nullable(); // Email for data export notifications
            $table->string('data_export_file_path')->nullable(); // File path for data export if using download method

            $table->boolean('data_import_enabled')->default(false); // Enable data import for the user
            $table->string('data_import_method')->default('upload'); // e.g., 'upload', 'api'
            $table->string('data_import_file_path')->nullable(); // File path for data import if using upload method


            $table->boolean('data_backup_enabled')->default(false); // Enable data backup for the user
            $table->string('data_backup_method')->default('cloud'); // e.g., 'cloud', 'local'
            $table->string('data_backup_service')->nullable(); // Service used for data backup
            $table->string('data_backup_file_path')->nullable(); // File path for data backup if using local method


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_auth_settings');
    }
};
