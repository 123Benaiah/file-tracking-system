<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('files', function ($table) {
            $table->dropColumn('merge_reason');
        });
    }

    public function down(): void
    {
        Schema::table('files', function ($table) {
            $table->text('merge_reason')->nullable()->after('merged_by');
        });
    }
};
