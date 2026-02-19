<?php

namespace App\Livewire\Files;

use Livewire\Component;
use App\Models\File;
use App\Models\Employee;
use App\Traits\WithToast;

class EditFile extends Component
{
    use WithToast;
    public File $file;
    public $showDeleteModal = false;

    public $subject;
    public $file_title;
    public $old_file_no;
    public $new_file_no;
    public $priority;
    public $status;
    public $confidentiality;
    public $remarks;
    public $due_date;
    public $current_holder;

    protected function rules()
    {
        return [
            'subject' => 'required|string|max:500',
            'file_title' => 'required|string|max:200',
            'old_file_no' => 'nullable|string|max:100',
            'new_file_no' => 'required|string|max:100|unique:files,new_file_no,' . $this->file->id,
            'priority' => 'required|in:normal,urgent,very_urgent',
            'status' => 'required|in:at_registry,in_transit,received,under_review,action_required,completed,returned_to_registry,archived',
            'confidentiality' => 'required|in:public,confidential,secret',
            'remarks' => 'nullable|string',
            'due_date' => 'nullable|date',
            'current_holder' => 'nullable|exists:employees,employee_number',
        ];
    }

    public function mount(File $file)
    {
        $this->file = $file;
        $this->subject = $file->subject;
        $this->file_title = $file->file_title;
        $this->old_file_no = $file->old_file_no;
        $this->new_file_no = $file->new_file_no;
        $this->priority = $file->priority;
        $this->status = $file->status;
        $this->confidentiality = $file->confidentiality;
        $this->remarks = $file->remarks;
        $this->due_date = $file->due_date?->format('Y-m-d');
        $this->current_holder = $file->current_holder;
    }

    public function update()
    {
        $this->validate();

        try {
            $this->file->update([
                'subject' => $this->subject,
                'file_title' => $this->file_title,
                'old_file_no' => $this->old_file_no,
                'new_file_no' => $this->new_file_no,
                'priority' => $this->priority,
                'status' => $this->status,
                'confidentiality' => $this->confidentiality,
                'remarks' => $this->remarks,
                'due_date' => $this->due_date,
                'current_holder' => $this->current_holder,
            ]);

            $this->toastSuccess('File Updated', 'File ' . $this->new_file_no . ' has been updated successfully.');

            return redirect()->route('files.show', $this->file);
        } catch (\Exception $e) {
            report($e);
            $this->toastError('Update Failed', 'Something went wrong while updating the file. Please try again.');
        }
    }

    public function deleteFile()
    {
        try {
            $fileNo = $this->file->new_file_no;

            $tjFiles = File::where('original_file_no', $this->file->new_file_no)
                ->orWhere('new_file_no', 'like', $this->file->new_file_no . '-tj%')
                ->get();

            $tjCount = $tjFiles->count();

            foreach ($tjFiles as $tjFile) {
                $tjFile->movements()->delete();
                $tjFile->forceDelete();
            }

            $this->file->movements()->delete();
            $this->file->delete();

            $message = "File {$fileNo} has been deleted successfully.";
            if ($tjCount > 0) {
                $message .= " Also deleted {$tjCount} TJ file(s) associated with this file.";
            }

            $this->toastSuccess('File Deleted', $message);

            return redirect()->route('registry.dashboard');
        } catch (\Exception $e) {
            report($e);
            $this->toastError('Delete Failed', 'Something went wrong while deleting the file. Please try again.');
        }
    }

    public function openDeleteModal()
    {
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
    }

    public function getTjFilesCount()
    {
        return File::where('original_file_no', $this->file->new_file_no)
            ->orWhere('new_file_no', 'like', $this->file->new_file_no . '-tj%')
            ->count();
    }

    public function getTjFiles()
    {
        return File::where('original_file_no', $this->file->new_file_no)
            ->orWhere('new_file_no', 'like', $this->file->new_file_no . '-tj%')
            ->get();
    }

    public function render()
    {
        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.files.edit-file', [
            'employees' => $employees,
        ]);
    }
}
