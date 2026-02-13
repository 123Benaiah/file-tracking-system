<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE files MODIFY COLUMN status ENUM('at_registry', 'in_transit', 'received', 'under_review', 'action_required', 'completed', 'returned_to_registry', 'archived', 'merged') DEFAULT 'at_registry'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE files MODIFY COLUMN status ENUM('at_registry', 'in_transit', 'received', 'under_review', 'action_required', 'completed', 'returned_to_registry', 'archived') DEFAULT 'at_registry'");
    }
};
