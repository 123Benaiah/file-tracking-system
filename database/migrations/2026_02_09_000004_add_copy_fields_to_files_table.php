<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->boolean('is_copy')->default(false);
            $table->integer('copy_number')->nullable();
            $table->string('original_file_no')->nullable()->after('new_file_no');

            $table->index(['original_file_no', 'copy_number']);
        });
    }

    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn(['is_copy', 'copy_number', 'original_file_no']);
        });
    }
};
