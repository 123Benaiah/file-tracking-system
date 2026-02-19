<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->renameColumn('is_copy', 'is_tj');
            $table->renameColumn('copy_number', 'tj_number');
        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->renameColumn('is_tj', 'is_copy');
            $table->renameColumn('tj_number', 'copy_number');
        });
    }
};
