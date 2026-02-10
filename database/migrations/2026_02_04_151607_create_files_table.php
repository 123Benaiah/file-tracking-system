<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('file_title');
            $table->string('old_file_no')->nullable();
            $table->string('new_file_no')->unique();
            $table->enum('priority', ['normal', 'urgent', 'very_urgent'])->default('normal');
            $table->enum('status', [
                'at_registry',
                'in_transit',
                'received',
                'under_review',
                'action_required',
                'completed',
                'returned_to_registry',
                'archived'
            ])->default('at_registry');
            $table->enum('confidentiality', ['public', 'confidential', 'secret'])->default('public');
            $table->text('remarks')->nullable();
            $table->date('due_date')->nullable();
            $table->date('date_registered');
            $table->string('current_holder')->nullable();
            $table->string('registered_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('current_holder')
                ->references('employee_number')
                ->on('employees')
                ->onDelete('set null');

            $table->foreign('registered_by')
                ->references('employee_number')
                ->on('employees');

            $table->index(['status', 'current_holder']);
            $table->index(['new_file_no', 'old_file_no']);
            $table->index('due_date');
            $table->index('registered_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
