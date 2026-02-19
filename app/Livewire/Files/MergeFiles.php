<?php

namespace App\Livewire\Files;

use App\Models\File;
use App\Models\AuditLog;
use App\Traits\WithToast;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MergeFiles extends Component
{
    use WithPagination, WithToast;

    public $originalFileNo = '';
    public $originalFile = null;
    public $selectedCopies = [];
    public $showMergeModal = false;
    public $isMerging = false;

    protected $rules = [
        'originalFileNo' => 'required|min:3',
    ];

    public function searchOriginalFile()
    {
        $this->validateOnly('originalFileNo');

        $this->originalFile = File::where('new_file_no', $this->originalFileNo)->first();

        if (!$this->originalFile) {
            $this->toastError('File Not Found', 'No file found with that number.');
            $this->originalFile = null;
            $this->selectedCopies = [];
            return;
        }

        if ($this->originalFile->status === 'merged') {
            $this->toastError('File Already Merged', 'This file has already been merged into another file.');
            $this->originalFile = null;
            return;
        }

        $this->selectedCopies = [];
    }

    public function toggleCopySelection($fileId)
    {
        if (in_array($fileId, $this->selectedCopies)) {
            $this->selectedCopies = array_values(array_diff($this->selectedCopies, [$fileId]));
        } else {
            $this->selectedCopies[] = $fileId;
        }
    }

    public function selectAllCopies()
    {
        $copies = $this->getCopies();
        $this->selectedCopies = $copies->whereIn('status', ['completed', 'at_registry'])->pluck('id')->toArray();
    }

    public function clearSelection()
    {
        $this->selectedCopies = [];
    }

    public function openMergeModal()
    {
        if (count($this->selectedCopies) < 1) {
            $this->toastError('Selection Required', 'Please select at least 1 copy to merge.');
            return;
        }

        $this->showMergeModal = true;
    }

    public function closeMergeModal()
    {
        $this->showMergeModal = false;
    }

    public function mergeFiles()
    {
        $this->isMerging = true;

        if (count($this->selectedCopies) < 1) {
            $this->isMerging = false;
            $this->toastError('Selection Required', 'Please select at least 1 copy to merge.');
            return;
        }

        $copies = File::whereIn('id', $this->selectedCopies)
            ->whereIn('status', ['completed', 'at_registry'])
            ->get();

        if ($copies->count() !== count($this->selectedCopies)) {
            $this->isMerging = false;
            $this->toastError('Invalid Selection', 'Only files with status Completed or At Registry can be merged.');
            return;
        }

        try {
            $mergedFileNumbers = $copies->pluck('new_file_no')->toArray();

            foreach ($copies as $copy) {
                $copy->movements()->update(['file_id' => $this->originalFile->id]);
                $copy->attachments()->update(['file_id' => $this->originalFile->id]);
                $copy->forceDelete();
            }

            $existingMergedFiles = $this->originalFile->merged_file_numbers ?? [];
            $this->originalFile->update([
                'merged_file_numbers' => array_merge($existingMergedFiles, $mergedFileNumbers),
            ]);

            AuditLog::create([
                'employee_number' => Auth::user()->employee_number,
                'action' => 'files_merged',
                'description' => 'Merged and deleted copies ' . implode(', ', $mergedFileNumbers) . ' into ' . $this->originalFile->new_file_no,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'new_data' => [
                    'original_file_id' => $this->originalFile->id,
                    'original_file_no' => $this->originalFile->new_file_no,
                    'merged_file_ids' => $this->selectedCopies,
                    'merged_file_numbers' => $mergedFileNumbers,
                ],
            ]);

            $this->isMerging = false;
            $this->toastSuccess('Files Merged', count($copies) . ' copy/copies merged and deleted. Original file: ' . $this->originalFile->new_file_no);

            $this->reset(['originalFileNo', 'originalFile', 'selectedCopies', 'showMergeModal']);
        } catch (\Exception $e) {
            $this->isMerging = false;
            $this->showMergeModal = false;
            report($e);
            $this->toastError('Merge Failed', 'Something went wrong while merging files. Please try again.');
        }
    }

    public function getCopies()
    {
        if (!$this->originalFile) {
            return collect([]);
        }

        return File::where('original_file_no', $this->originalFile->new_file_no)
            ->orWhere(function ($query) {
                $query->where('new_file_no', 'like', $this->originalFile->new_file_no . '-tj%')
                      ->where('is_tj', true);
            })
            ->where('id', '!=', $this->originalFile->id)
            ->whereIn('status', ['completed', 'at_registry'])
            ->orderBy('tj_number')
            ->get();
    }

    public function render()
    {
        $copies = $this->getCopies();

        return view('livewire.files.merge-files', [
            'originalFile' => $this->originalFile,
            'copies' => $copies,
            'selectedCopiesCount' => count($this->selectedCopies),
        ]);
    }
}
