<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('employee_number', 20)->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('position');
            $table->string('department');
            $table->string('unit')->nullable();
            $table->string('office');
            $table->enum('role', [
                'registry_head', 
                'registry_clerk', 
                'department_head', 
                'user'
            ])->default('user');
            $table->boolean('is_active')->default(true);
            $table->string('created_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')
                ->references('employee_number')
                ->on('employees')
                ->onDelete('set null');

            $table->index(['department', 'role']);
            $table->index(['is_active', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};