<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))->create(config('activitylog.table_name'),
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('log_name')->nullable();
                $table->text('description');
                $table->string('subject_id', 36)->nullable(); // UUID به‌صورت string
                $table->string('subject_type')->nullable();
                $table->index(['subject_id', 'subject_type'], 'subject_subject_id_subject_type_index');
                $table->uuid('causer_id')->nullable();
                $table->string('causer_type')->nullable();
                $table->index(['causer_type', 'causer_id'], 'causer_causer_type_causer_id_index');
                $table->json('properties')->nullable();
                $table->timestamps();
                $table->index('log_name');
            });
    }

    public function down(): void
    {
        Schema::connection(config('activitylog.database_connection'))->dropIfExists(config('activitylog.table_name'));
    }
}
