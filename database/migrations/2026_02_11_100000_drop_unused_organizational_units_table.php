<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function ($table) {
            $table->dropForeign(['organizational_unit_id']);
            $table->dropColumn('organizational_unit_id');
        });
        Schema::dropIfExists('organizational_units');
    }

    public function down(): void
    {
        Schema::create('organizational_units', function ($table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('institution')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('organizational_units')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['institution', 'name']);
        });
    }
};
