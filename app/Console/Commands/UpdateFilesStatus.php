<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Models\File;
use Illuminate\Console\Command;

class UpdateFilesStatus extends Command
{
    protected $signature = 'files:update-status';
    protected $description = 'Update files held by registry staff to completed status';

    public function handle()
    {
        // Soft delete all merged files so they don't appear anywhere
        $mergedFiles = File::where('status', 'merged')->get();
        $count = $mergedFiles->count();
        
        foreach ($mergedFiles as $file) {
            $file->delete();
        }

        $this->info("Soft-deleted {$count} merged files.");

        return Command::SUCCESS;
    }
}
