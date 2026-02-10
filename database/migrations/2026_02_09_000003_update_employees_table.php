<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['position', 'department', 'unit']);

            $table->foreignId('position_id')
                ->nullable()
                ->constrained('positions')
                ->nullOnDelete();

            $table->foreignId('organizational_unit_id')
                ->nullable()
                ->constrained('organizational_units')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropForeign(['organizational_unit_id']);
            $table->dropColumn(['position_id', 'organizational_unit_id']);

            $table->string('position');
            $table->string('department');
            $table->string('unit')->nullable();
        });
    }
};
