<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained()->onDelete('cascade');
            $table->string('filename');
            $table->string('original_name');
            $table->string('path');
            $table->string('mime_type');
            $table->integer('size');
            $table->string('uploaded_by');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('uploaded_by')
                ->references('employee_number')
                ->on('employees');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_attachments');
    }
};
