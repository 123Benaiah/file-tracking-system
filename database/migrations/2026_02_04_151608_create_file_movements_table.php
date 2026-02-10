<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->string('sender_emp_no');
            $table->string('intended_receiver_emp_no');
            $table->string('actual_receiver_emp_no')->nullable();
            $table->string('hand_carried_by')->nullable();
            $table->enum('delivery_method', [
                'internal_messenger',
                'hand_carry',
                'courier',
                'email'
            ])->default('internal_messenger');
            $table->text('sender_comments')->nullable();
            $table->text('receiver_comments')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->enum('movement_status', [
                'sent',
                'delivered',
                'received',
                'acknowledged',
                'rejected'
            ])->default('sent');
            $table->integer('sla_days')->default(3);
            $table->boolean('is_overdue')->default(false);
            $table->text('qr_code')->nullable();
            $table->timestamps();

            $table->foreign('sender_emp_no')
                ->references('employee_number')
                ->on('employees');

            $table->foreign('intended_receiver_emp_no')
                ->references('employee_number')
                ->on('employees');

            $table->foreign('actual_receiver_emp_no')
                ->references('employee_number')
                ->on('employees')
                ->nullable();

            $table->index(['file_id', 'movement_status']);
            $table->index('sent_at');
            $table->index('received_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_movements');
    }
};
