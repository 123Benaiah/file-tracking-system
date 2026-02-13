<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('file_movements', function (Blueprint $table) {
            $table->index('intended_receiver_emp_no', 'fm_intended_receiver_idx');
            $table->index('sender_emp_no', 'fm_sender_idx');
            $table->index('actual_receiver_emp_no', 'fm_actual_receiver_idx');
            $table->index('movement_status', 'fm_movement_status_idx');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->index('date_registered', 'files_date_registered_idx');
            $table->index('priority', 'files_priority_idx');
            $table->index('current_holder', 'files_current_holder_idx');
        });
    }

    public function down(): void
    {
        Schema::table('file_movements', function (Blueprint $table) {
            $table->dropIndex('fm_intended_receiver_idx');
            $table->dropIndex('fm_sender_idx');
            $table->dropIndex('fm_actual_receiver_idx');
            $table->dropIndex('fm_movement_status_idx');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->dropIndex('files_date_registered_idx');
            $table->dropIndex('files_priority_idx');
            $table->dropIndex('files_current_holder_idx');
        });
    }
};
