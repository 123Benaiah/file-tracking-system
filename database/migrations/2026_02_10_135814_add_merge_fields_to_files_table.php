<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->unsignedBigInteger('merged_into_file_id')->nullable()->after('status');
            $table->timestamp('merged_at')->nullable()->after('merged_into_file_id');
            $table->string('merged_by')->nullable()->after('merged_at');
            $table->text('merge_reason')->nullable()->after('merged_by');
            $table->json('merged_file_numbers')->nullable()->after('merge_reason');

            $table->foreign('merged_into_file_id')->references('id')->on('files')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['merged_into_file_id']);
            $table->dropColumn([
                'merged_into_file_id',
                'merged_at',
                'merged_by',
                'merge_reason',
                'merged_file_numbers',
            ]);
        });
    }
};
