<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update positions table
        Schema::table('positions', function (Blueprint $table) {
            if (!Schema::hasColumn('positions', 'code')) {
                $table->string('code')->unique()->nullable()->after('title');
            }
            if (!Schema::hasColumn('positions', 'position_type')) {
                $table->enum('position_type', ['director', 'assistant_director', 'supervisor', 'staff', 'support'])
                      ->default('staff')->after('description');
            }
            if (!Schema::hasColumn('positions', 'level')) {
                $table->unsignedTinyInteger('level')->default(1)->after('position_type');
            }
            if (!Schema::hasColumn('positions', 'employment_type')) {
                $table->enum('employment_type', ['permanent', 'contract', 'temporary', 'intern'])
                      ->default('permanent')->after('level');
            }
        });

        // Create departments table
        if (!Schema::hasTable('departments')) {
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
        }

        // Create units table
        if (!Schema::hasTable('units')) {
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
        }

        // Create department_heads table
        if (!Schema::hasTable('department_heads')) {
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
        }

        // Create unit_heads table
        if (!Schema::hasTable('unit_heads')) {
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

        // Update employees table to add department_id and unit_id
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'department_id')) {
                $table->foreignId('department_id')->nullable()
                      ->constrained('departments')->nullOnDelete();
            }
            if (!Schema::hasColumn('employees', 'unit_id')) {
                $table->foreignId('unit_id')->nullable()
                      ->constrained('units')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['department_id', 'unit_id']);
        });

        Schema::dropIfExists('unit_heads');
        Schema::dropIfExists('department_heads');
        Schema::dropIfExists('units');
        Schema::dropIfExists('departments');

        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn(['code', 'position_type', 'level', 'employment_type']);
        });
    }
};
