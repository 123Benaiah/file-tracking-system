<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->boolean('has_units')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('location')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'name']);
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            
            // Position type for role classification
            $table->enum('position_type', [
                'director',           // Department head
                'assistant_director', // Unit head
                'supervisor',        // Section supervisor
                'staff',            // Regular staff
                'support'           // Support staff
            ])->default('staff');
            
            // Hierarchical level for ordering (higher = more senior)
            $table->unsignedTinyInteger('level')->default(1);
            
            // Employment type
            $table->enum('employment_type', [
                'permanent',
                'contract',
                'temporary',
                'intern'
            ])->default('permanent');
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['position_type', 'level']);
        });

        // Department Head Positions - links Director position to Department
        Schema::create('department_heads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            $table->date('effective_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['department_id', 'is_active'], 'unique_active_department_head');
        });

        // Unit Head Positions - links Assistant Director position to Unit
        Schema::create('unit_heads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            $table->date('effective_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['unit_id', 'is_active'], 'unique_active_unit_head');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_heads');
        Schema::dropIfExists('department_heads');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('units');
        Schema::dropIfExists('departments');
    }
};
