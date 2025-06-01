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
        Schema::create('user_notification_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->boolean('login_email');

            $table->boolean('login_sms');
            $table->boolean('register_email');

            $table->boolean('logout_email');
            $table->boolean('logout_sms');

            $table->boolean('change_password_email');
            $table->boolean('change_password_sms');

            $table->boolean('change_email_email');
            $table->boolean('change_email_sms');

            $table->boolean('change_mobile_email');
            $table->boolean('change_mobile_sms');

            $table->boolean('change_profile_image_email');
            $table->boolean('change_profile_image_sms');


//            $table->boolean('create_transaction_email');
//            $table->boolean('create_transaction_sms');
//
//            $table->boolean('payed_transaction_email');
//            $table->boolean('payed_transaction_sms');
//
//            $table->boolean('create_withdraw_email');
//            $table->boolean('create_withdraw_sms');
//
//            $table->boolean('add_bank_email');
//            $table->boolean('add_bank_sms');
//
//            $table->boolean('pay_profit_email');
//            $table->boolean('pay_profit_sms');
//
//            $table->boolean('create_investment_email');
//            $table->boolean('create_investment_sms');
//
//            $table->boolean('create_ticket_email');
//            $table->boolean('create_ticket_sms');
//
//            $table->boolean('answer_ticket_email');
//            $table->boolean('answer_ticket_sms');
//
//            $table->boolean('store_verification_email');
//            $table->boolean('store_verification_sms');


            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_settings');
    }
};
