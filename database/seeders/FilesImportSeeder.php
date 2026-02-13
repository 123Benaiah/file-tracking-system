<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilesImportSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('files.txt');

        if (!file_exists($filePath)) {
            $this->command->error("files.txt not found in project root!");
            return;
        }

        $this->command->info("Reading files.txt...");
        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);

        // Get registry head as the one who registered all files
        // Registry Head is identified by department (HRA), unit (Registry), and position (Registry Head)
        $registryHead = Employee::whereHas('unitRel', function ($query) {
                $query->where('name', 'Registry');
            })
            ->whereHas('departmentRel', function ($query) {
                $query->where('code', 'HRA')->orWhere('name', 'Human Resources and Administration');
            })
            ->whereHas('position', function ($query) {
                $query->where('title', 'like', '%Registry Head%');
            })
            ->first();

        if (!$registryHead) {
            $this->command->error("No registry head found! Please run DatabaseSeeder first.");
            return;
        }

        $currentSubject = '';
        $filesCreated = 0;
        $filesSkipped = 0;

        $this->command->info("Parsing and importing files...");
        $progressBar = $this->command->getOutput()->createProgressBar(count($lines));

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines
            if (empty($line)) {
                $progressBar->advance();
                continue;
            }

            // Check if this is a SUBJECT line
            if (stripos($line, 'SUBJECT:') === 0 || stripos($line, 'SUBJECT :') === 0) {
                $currentSubject = trim(str_ireplace(['SUBJECT:', 'SUBJECT :'], '', $line));
                $currentSubject = trim(preg_replace('/^\d+[\/-]\s*/', '', $currentSubject)); // Remove leading numbers like "1-" or "1/"
                $progressBar->advance();
                continue;
            }

            // Skip header lines
            if (stripos($line, 'FILE TITLE') !== false ||
                stripos($line, 'OLD FILE NO') !== false ||
                stripos($line, 'NEW FILE NO') !== false) {
                $progressBar->advance();
                continue;
            }

            // Try to parse a file entry
            // Format: FILE TITLE [spaces/tabs] OLD FILE NO [spaces/tabs] NEW FILE NO
            if (!empty($currentSubject)) {
                // Split by multiple spaces/tabs to separate title, old no, and new no
                $parts = preg_split('/\s{2,}/', $line);

                if (count($parts) >= 1) {
                    $fileTitle = trim($parts[0]);
                    $oldFileNo = isset($parts[1]) ? trim($parts[1]) : null;
                    $newFileNo = isset($parts[2]) ? trim($parts[2]) : (isset($parts[1]) ? trim($parts[1]) : null);

                    // Clean up file numbers - remove extra spaces
                    if ($oldFileNo) {
                        $oldFileNo = preg_replace('/\s+/', '', $oldFileNo);
                    }
                    if ($newFileNo) {
                        $newFileNo = preg_replace('/\s+/', '', $newFileNo);
                    }

                    // Validate that we have at least a title and new file number
                    if (!empty($fileTitle) && !empty($newFileNo) &&
                        preg_match('/^MHA\/\d+/', $newFileNo)) {

                        // Check if file already exists
                        $exists = File::where('new_file_no', $newFileNo)->exists();

                        if (!$exists) {
                            try {
                                File::create([
                                    'subject' => $currentSubject,
                                    'file_title' => $fileTitle,
                                    'old_file_no' => $oldFileNo ?: null,
                                    'new_file_no' => $newFileNo,
                                    'priority' => 'normal',
                                    'status' => 'at_registry',
                                    'confidentiality' => 'public',
                                    'date_registered' => now()->format('Y-m-d'),
                                    'current_holder' => $registryHead->employee_number,
                                    'registered_by' => $registryHead->employee_number,
                                ]);
                                $filesCreated++;
                            } catch (\Exception $e) {
                                $this->command->warn("Failed to create file {$newFileNo}: " . $e->getMessage());
                                $filesSkipped++;
                            }
                        } else {
                            $filesSkipped++;
                        }
                    }
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine(2);
        $this->command->info("Import completed!");
        $this->command->info("Files created: {$filesCreated}");
        $this->command->info("Files skipped (duplicates/invalid): {$filesSkipped}");
    }
}
