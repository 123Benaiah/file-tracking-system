<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number');
            $table->boolean('show_popup_notifications')->default(true);
            $table->boolean('auto_hide_after_view')->default(false);
            $table->integer('popup_position')->default(1); // 1=bottom-right, 2=bottom-left, 3=top-right, 4=top-left
            $table->timestamps();

            $table->foreign('employee_number')
                  ->references('employee_number')
                  ->on('employees')
                  ->onDelete('cascade');

            $table->unique('employee_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
