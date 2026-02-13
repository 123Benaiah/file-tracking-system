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
        // Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->boolean('is_registry')->default(false);
            $table->boolean('has_units')->default(true);
            $table->timestamps();
            
            $table->index('is_registry');
            $table->index('name');
        });

        // Units
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->boolean('is_registry')->default(false);
            $table->timestamps();
            
            $table->index('department_id');
            $table->index('is_registry');
        });

        // Positions
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('position_code')->unique()->nullable();
            $table->enum('position_type', ['director', 'assistant_director', 'supervisor', 'staff', 'support'])->default('staff');
            $table->integer('position_level')->default(3);
            $table->enum('employment_type', ['permanent', 'contract', 'temporary', 'intern'])->default('permanent');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('position_type');
            $table->index('position_level');
        });

        // Employees
        Schema::create('employees', function (Blueprint $table) {
            $table->string('employee_number')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_registry_head')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('position_id')->nullable()->constrained()->onDelete('set null');
            $table->string('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('employee_number')->on('employees')->onDelete('set null');
            $table->index('department_id');
            $table->index('unit_id');
            $table->index('position_id');
            $table->index('is_admin');
            $table->index('is_active');
            $table->index('email');
        });

        // Department Heads
        Schema::create('department_heads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('department_id');
            $table->index('is_active');
        });

        // Unit Heads
        Schema::create('unit_heads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('unit_id');
            $table->index('is_active');
        });

        // Files
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_number')->unique();
            $table->string('old_file_number')->nullable();
            $table->string('file_name');
            $table->string('subject');
            $table->string('title')->nullable();
            $table->enum('priority', ['normal', 'urgent', 'very_urgent'])->default('normal');
            $table->enum('confidentiality', ['public', 'confidential', 'secret'])->default('public');
            $table->enum('status', ['at_registry', 'in_transit', 'received', 'under_review', 'action_required', 'completed', 'returned_to_registry', 'archived', 'merged'])->default('at_registry');
            $table->string('current_holder_employee_number')->nullable();
            $table->string('registered_by_employee_number');
            $table->date('due_date')->nullable();
            $table->boolean('is_copy')->default(false);
            $table->foreignId('parent_file_id')->nullable()->constrained('files')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('current_holder_employee_number')->references('employee_number')->on('employees')->onDelete('set null');
            $table->foreign('registered_by_employee_number')->references('employee_number')->on('employees')->onDelete('cascade');
            $table->index('file_number');
            $table->index('status');
            $table->index('priority');
            $table->index('current_holder_employee_number');
            $table->index('due_date');
            $table->index('created_at');
            $table->index('deleted_at');
        });

        // File Attachments
        Schema::create('file_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->string('file_path', 500);
            $table->string('original_name');
            $table->unsignedInteger('file_size');
            $table->string('mime_type', 100);
            $table->string('uploaded_by_employee_number');
            $table->timestamp('created_at');
            
            $table->foreign('uploaded_by_employee_number')->references('employee_number')->on('employees')->onDelete('cascade');
            $table->index('file_id');
        });

        // File Movements
        Schema::create('file_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->string('sender_employee_number');
            $table->string('intended_receiver_employee_number');
            $table->string('actual_receiver_employee_number')->nullable();
            $table->enum('movement_status', ['sent', 'delivered', 'received', 'acknowledged', 'rejected'])->default('sent');
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('received_at')->nullable();
            $table->enum('delivery_method', ['internal_messenger', 'hand_carry', 'courier', 'email'])->default('hand_carry');
            $table->text('sender_comments')->nullable();
            $table->text('receiver_comments')->nullable();
            $table->string('hand_carried_by')->nullable();
            $table->integer('sla_days')->default(3);
            $table->boolean('is_overdue')->default(false);
            $table->timestamps();
            
            $table->foreign('sender_employee_number')->references('employee_number')->on('employees')->onDelete('cascade');
            $table->foreign('intended_receiver_employee_number')->references('employee_number')->on('employees')->onDelete('cascade');
            $table->foreign('actual_receiver_employee_number')->references('employee_number')->on('employees')->onDelete('set null');
            $table->index('file_id');
            $table->index('sender_employee_number');
            $table->index('intended_receiver_employee_number');
            $table->index('actual_receiver_employee_number');
            $table->index('movement_status');
            $table->index('sent_at');
            $table->index('is_overdue');
        });

        // Audit Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_employee_number');
            $table->string('action', 50);
            $table->string('entity_type', 100);
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at');
            
            $table->foreign('user_employee_number')->references('employee_number')->on('employees')->onDelete('cascade');
            $table->index('user_employee_number');
            $table->index('action');
            $table->index('entity_type');
            $table->index('entity_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('file_attachments');
        Schema::dropIfExists('file_movements');
        Schema::dropIfExists('files');
        Schema::dropIfExists('unit_heads');
        Schema::dropIfExists('department_heads');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('units');
        Schema::dropIfExists('departments');
    }
};
