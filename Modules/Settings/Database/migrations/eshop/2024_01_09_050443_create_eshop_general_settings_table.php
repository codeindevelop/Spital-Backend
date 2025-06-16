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
        Schema::create('eshop_general_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('store_logo')->nullable(); // لوگو فروشگاه
            $table->string('store_name')->nullable(); // نام فروشگاه
            $table->string('email')->nullable(); // ایمیل
            $table->string('phone_number')->nullable(); // شماره تماس
            $table->string('mobile_number')->nullable(); // شماره موبایل مسئول فروشگاه
            $table->enum('purchase_mode',
                ['guest', 'registered'])->default('guest'); // امکان خرید: مهمان یا ثبت‌نام‌شده
            $table->text('address')->nullable(); // آدرس فروشگاه
            $table->string('city')->nullable(); // شهر
            $table->string('country')->nullable(); // کشور
            $table->string('province')->nullable(); // استان
            $table->string('postal_code')->nullable(); // کدپستی
            $table->enum('sale_scope', ['worldwide', 'iran', 'province', 'city'])->default('worldwide'); // محدوده فروش
            $table->enum('shipping_scope',
                ['worldwide', 'iran', 'province', 'city'])->default('worldwide'); // محدوده حمل و نقل
            $table->boolean('tax_enabled')->default(false); // فعال بودن مالیات
            $table->boolean('coupon_enabled')->default(false); // فعال بودن کوپن‌های تخفیف
            $table->string('currency'); // نوع ارز
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};
