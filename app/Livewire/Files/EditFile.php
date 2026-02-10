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
    }

    public function deleteFile()
    {
        $fileNo = $this->file->new_file_no;

        // Delete all movements first
        $this->file->movements()->delete();

        // Delete the file
        $this->file->delete();

        $this->toastSuccess('File Deleted', "File {$fileNo} has been deleted successfully.");

        return redirect()->route('registry.dashboard');
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
