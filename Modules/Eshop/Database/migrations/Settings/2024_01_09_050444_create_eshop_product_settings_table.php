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
        Schema::create('eshop_product_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // General
            $table->boolean('redirect_to_cart')->default(true); // Redirect to the cart page after successful addition
            $table->boolean('dynamic_cart')->default(true); // Products in cart has been analysis
            $table->string('placeholder_image')->nullable(); // Tedade ax gabele nemayesh mahsool
            $table->string('weight_unit')->nullable(); // KG - G - lbs - os
            $table->string('dimensions_unit')->nullable(); // m - cm - mm - in - yd
            $table->boolean('product_reviews')->default(true); // Enable product reviews
            $table->boolean('only_owners_can_reviews')->default(true); // Reviews can only be left by "verified owners"
            $table->boolean('show_verified')->default(true); // Show "verified owner" label on customer reviews
            $table->boolean('star_rating_review')->default(true); // Enable star rating on reviews
            $table->boolean('star_rating_review_required')->default(false); // Star ratings should be required, not optional

            // Inventory
            $table->boolean('manage_stock')->default(true); // Enable stock management
            $table->string('hold_stock')->default('60'); // Hold stock (for unpaid orders) for x minutes. When this limit is reached, the pending order will be cancelled. Leave blank to disable.
            $table->boolean('low_stock_notification')->default(true); // Enable low stock notifications
            $table->boolean('out_of_stock_notification')->default(true); //  Enable out of stock notifications
            $table->string('low_stock_threshold')->default('2'); // Hold stock (for unpaid orders) for x minutes. When this limit is reached, the pending order will be cancelled. Leave blank to disable.
            $table->string('out_of_stock_threshold')->default('2'); // Hold stock (for unpaid orders) for x minutes. When this limit is reached, the pending order will be cancelled. Leave blank to disable.
            $table->boolean('out_of_stock_visibility')->default(false); // Hide out of stock items from the catalog

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eshop_product_settings');
    }
};
