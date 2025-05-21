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
        Schema::create('user_plan_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();


            $table->uuid('plan_id');
            $table->foreign('plan_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('du_price')->nullable(); // geymate gabli ya geymate bdone takhfif
            $table->string('total_price'); // geymate tamam shode
            $table->string('du_monthly_price')->nullable(); // geymate gabli pardakht mahiyane
            $table->string('monthly_price')->nullable(); // geymate har mah
            $table->boolean('can_pay_parttime');
            $table->string('part_pay_number')->nullable(); // pardakht teye chand mah
            $table->string('part_pay_price')->nullable(); // geymate har gest
            $table->string('note')->nullable();


            $table->boolean('active');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_plan_prices');
    }
};
