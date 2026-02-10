<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizational_units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('institution')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('organizational_units')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['institution', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizational_units');
    }
};
