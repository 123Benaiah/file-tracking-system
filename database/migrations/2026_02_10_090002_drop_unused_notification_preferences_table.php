<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('notification_preferences');
    }

    public function down(): void
    {
        Schema::create('notification_preferences', function ($table) {
            $table->id();
            $table->string('employee_number');
            $table->boolean('show_popup_notifications')->default(true);
            $table->boolean('auto_hide_after_view')->default(false);
            $table->integer('popup_position')->default(1);
            $table->timestamps();

            $table->foreign('employee_number')
                  ->references('employee_number')
                  ->on('employees')
                  ->onDelete('cascade');

            $table->unique('employee_number');
        });
    }
};
