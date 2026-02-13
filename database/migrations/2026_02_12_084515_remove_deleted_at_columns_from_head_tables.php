<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('department_heads', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('unit_heads', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('department_heads', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::table('unit_heads', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });
    }
};
