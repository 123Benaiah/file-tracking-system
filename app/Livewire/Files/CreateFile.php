<?php

namespace App\Livewire\Files;

use App\Models\File;
use App\Models\FileAttachment;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateFile extends Component
{
    use WithFileUploads, WithToast;

    public $subject;
    public $subjectType = 'existing';
    public $newSubject = '';
    public $file_title;
    public $old_file_no = '';
    public $new_file_no = '';
    public $priority = 'normal';
    public $confidentiality = 'public';
    public $remarks;
    public $due_date;
    public $attachments = [];
    public $fileCreationType = 'new';
    public $tjFileNo = '';
    public $newFileNoExists = false;
    public $oldFileNoExists = false;
    public $oldFileNoData = null;
    public $tjFileData = null;
    public $isSaving = false;

    public $existingSubjects = [
        'HEAD OF STATE',
        'PREROGATIVE OF MERCY',
        'PUBLIC SERVICE MANAGEMENT POLICY',
        'STATE VISITS',
        'PRESIDENTIAL AFFAIRS',
        'INTERNAL AUDIT',
        'ADMINISTRATION',
    ];

    protected function rules()
    {
        $rules = [
            'subjectType' => 'required|in:existing,new',
            'fileCreationType' => 'required|in:new,tj',
            'file_title' => 'required|string|max:200',
            'old_file_no' => 'nullable|string|max:50',
            'priority' => 'required|in:normal,urgent,very_urgent',
            'confidentiality' => 'required|in:public,confidential,secret',
            'remarks' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date|after:today',
            'attachments.*' => 'nullable|file|max:10240',
        ];

        if ($this->fileCreationType === 'new') {
            $rules['new_file_no'] = 'required|string|max:100|unique:files,new_file_no';
        } else {
            $rules['tjFileNo'] = 'required|string|max:100';
        }

        if ($this->subjectType === 'existing') {
            $rules['subject'] = 'required|string|max:500';
        } else {
            $rules['newSubject'] = 'required|string|max:500';
        }

        return $rules;
    }

    public function updatedSubjectType()
    {
        if ($this->subjectType === 'existing') {
            $this->newSubject = '';
        } else {
            $this->subject = '';
        }
    }

    public function removeAttachment($index)
    {
        if (isset($this->attachments[$index])) {
            unset($this->attachments[$index]);
            $this->attachments = array_values($this->attachments);
        }
    }

    public function updatedFileCreationType()
    {
        if ($this->fileCreationType === 'new') {
            $this->tjFileNo = '';
            $this->tjFileData = null;
            $this->generateNewFileNumber();
        } else {
            $this->new_file_no = '';
            $this->newFileNoExists = false;
        }
    }

    public function updatedTjFileNo()
    {
        $this->tjFileData = null;

        if (! empty($this->tjFileNo) && strlen($this->tjFileNo) >= 3) {
            $originalFile = File::where('new_file_no', $this->tjFileNo)
                ->orWhere('new_file_no', 'like', $this->tjFileNo.'-tj%')
                ->first();

            if ($originalFile && ! $originalFile->is_tj) {
                $this->tjFileData = [
                    'new_file_no' => $originalFile->new_file_no,
                    'subject' => $originalFile->subject,
                    'file_title' => $originalFile->file_title,
                    'status' => $originalFile->status,
                    'next_tj_number' => $originalFile->getNextTjNumber(),
                ];

                $this->new_file_no = $originalFile->new_file_no.'-tj'.$originalFile->getNextTjNumber();
                $this->subject = $originalFile->subject;
                $this->file_title = $originalFile->file_title;
                $this->old_file_no = $originalFile->new_file_no;
            } else {
                $this->tjFileData = null;
                $this->new_file_no = '';
            }
        } else {
            $this->new_file_no = '';
        }
    }

    public function updatedNewFileNo()
    {
        $this->newFileNoExists = false;

        if (! empty($this->new_file_no) && strlen($this->new_file_no) >= 3 && $this->fileCreationType === 'new') {
            $exists = File::withoutTrashed()->where('new_file_no', $this->new_file_no)->exists();
            $this->newFileNoExists = $exists;
        }
    }

    public function updatedOldFileNo()
    {
        $this->oldFileNoExists = false;
        $this->oldFileNoData = null;

        if (! empty($this->old_file_no) && strlen($this->old_file_no) >= 3) {
            $file = File::where('old_file_no', $this->old_file_no)
                ->orWhere('new_file_no', $this->old_file_no)
                ->first();

            if ($file) {
                $this->oldFileNoExists = true;
                $this->oldFileNoData = [
                    'new_file_no' => $file->new_file_no,
                    'subject' => $file->subject,
                    'status' => $file->status,
                ];
            }
        }
    }

    public function mount()
    {
        if (! auth()->user()->canRegisterFiles()) {
            abort(403, 'Only Registry Head can register new files.');
        }

        $this->generateNewFileNumber();
    }

    protected function generateNewFileNumber()
    {
        $today = now()->format('Ymd');
        $prefix = 'FTS-'.$today.'-';

        $lastFile = File::withoutTrashed()
            ->where('new_file_no', 'like', $prefix.'%')
            ->orderBy('new_file_no', 'desc')
            ->first();

        if ($lastFile) {
            $lastNumber = (int) substr($lastFile->new_file_no, strlen($prefix));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $this->new_file_no = $prefix.$newNumber;
    }

    public function save()
    {
        $this->isSaving = true;
        $this->validate();

        if (File::withoutTrashed()->where('new_file_no', $this->new_file_no)->exists()) {
            $this->isSaving = false;
            $this->toastError('Duplicate File Number', 'This file number already exists in the system.');

            return;
        }

        try {
            $subjectValue = $this->subjectType === 'existing' ? $this->subject : $this->newSubject;

            $isTj = $this->fileCreationType === 'tj';
            $originalFileNo = $isTj ? $this->tjFileNo : null;
            $tjNumber = null;

            if ($isTj && $this->tjFileData) {
                $tjNumber = $this->tjFileData['next_tj_number'];
            }

            $file = File::create([
                'subject' => $subjectValue,
                'file_title' => $this->file_title,
                'old_file_no' => $this->old_file_no,
                'new_file_no' => $this->new_file_no,
                'original_file_no' => $originalFileNo,
                'is_tj' => $isTj,
                'tj_number' => $tjNumber,
                'priority' => $this->priority,
                'status' => 'at_registry',
                'confidentiality' => $this->confidentiality,
                'current_holder' => auth()->user()->employee_number,
                'registered_by' => auth()->user()->employee_number,
                'remarks' => $this->remarks,
                'due_date' => $this->due_date,
                'date_registered' => now(),
            ]);

            foreach ($this->attachments as $attachment) {
                $path = $attachment->store('attachments/'.$file->id, 'local');

                FileAttachment::create([
                    'file_id' => $file->id,
                    'filename' => $attachment->hashName(),
                    'original_name' => $attachment->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                    'uploaded_by' => auth()->user()->employee_number,
                    'description' => null,
                ]);
            }

            \App\Models\AuditLog::create([
                'employee_number' => auth()->user()->employee_number,
                'action' => $isTj ? 'file_tj_created' : 'file_registered',
                'description' => $isTj
                    ? 'Created TJ file: '.$this->tjFileNo.' as '.$this->new_file_no
                    : 'Registered new file: '.$this->new_file_no,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'new_data' => $file->toArray(),
            ]);

            $this->toastSuccess(
                $isTj ? 'TJ File Created' : 'File Registered',
                $isTj
                    ? 'TJ File '.$this->new_file_no.' has been created successfully.'
                    : 'File No: '.$this->new_file_no.' has been registered successfully.'
            );

            return redirect()->route('registry.dashboard');

        } catch (\Exception $e) {
            $this->isSaving = false;
            $this->toastError('Operation Failed', 'Error: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.files.create-file');
    }
}
