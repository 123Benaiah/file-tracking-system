<?php

namespace App\Livewire\Files;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\File;
use App\Models\FileAttachment;
use App\Traits\WithToast;
use Illuminate\Support\Facades\Storage;

class FileAttachments extends Component
{
    use WithToast, WithFileUploads;

    public File $file;
    public $newAttachments = [];
    public $attachmentsToDelete = [];
    public $showDeleteModal = false;
    public $attachmentToDelete = null;
    public $openGroups = [];

    protected function rules()
    {
        return [
            'newAttachments.*' => 'file|max:102400',
        ];
    }

    protected function messages()
    {
        return [
            'newAttachments.*.max' => 'Each file must not exceed 100 MB.',
        ];
    }

    public function mount(File $file)
    {
        $this->file = $file;
        $this->openGroups = [];
    }

    public function toggleGroup($uploaderKey)
    {
        if (in_array($uploaderKey, $this->openGroups)) {
            $this->openGroups = array_diff($this->openGroups, [$uploaderKey]);
        } else {
            $this->openGroups[] = $uploaderKey;
        }
    }

    public function isGroupOpen($uploaderKey)
    {
        return in_array($uploaderKey, $this->openGroups);
    }

    public function canDeleteAttachment(FileAttachment $attachment)
    {
        $user = auth()->user();
        
        if ($user->isRegistryHead()) {
            return true;
        }

        return $attachment->uploaded_by === $user->employee_number;
    }

    public function canAddAttachment()
    {
        $user = auth()->user();
        
        return $this->file->current_holder === $user->employee_number 
            || $user->isRegistryHead()
            || $user->isRegistryStaff();
    }

    public function uploadAttachments()
    {
        $this->validate();

        try {
            foreach ($this->newAttachments as $attachment) {
                $path = $attachment->store('attachments/'.$this->file->id, 'local');

                FileAttachment::create([
                    'file_id' => $this->file->id,
                    'filename' => $attachment->hashName(),
                    'original_name' => $attachment->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                    'uploaded_by' => auth()->user()->employee_number,
                    'description' => null,
                ]);
            }

            $this->newAttachments = [];
            $this->toastSuccess('Attachments Added', 'New attachments have been uploaded successfully.');
        } catch (\Exception $e) {
            report($e);
            $this->toastError('Upload Failed', 'Something went wrong while uploading attachments.');
        }
    }

    public function confirmDelete($attachmentId)
    {
        $this->attachmentToDelete = $attachmentId;
        $this->showDeleteModal = true;
    }

    public function deleteAttachment()
    {
        if (!$this->attachmentToDelete) {
            return;
        }

        $attachment = FileAttachment::find($this->attachmentToDelete);
        
        if (!$attachment) {
            $this->toastError('Error', 'Attachment not found.');
            $this->closeModal();
            return;
        }

        if (!$this->canDeleteAttachment($attachment)) {
            $this->toastError('Permission Denied', 'You can only delete attachments you uploaded.');
            $this->closeModal();
            return;
        }

        $attachment->delete();
        $this->toastSuccess('Attachment Deleted', 'Attachment has been removed.');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showDeleteModal = false;
        $this->attachmentToDelete = null;
    }

    public function removeNewAttachment($index)
    {
        if (isset($this->newAttachments[$index])) {
            unset($this->newAttachments[$index]);
            $this->newAttachments = array_values($this->newAttachments);
        }
    }

    public function render()
    {
        $attachments = $this->file->attachments()
            ->with('uploader')
            ->orderBy('created_at', 'asc')
            ->get();

        // Get all movements in chronological order
        $movements = $this->file->movements()
            ->with(['sender', 'actualReceiver'])
            ->reorder('sent_at', 'asc')
            ->get();

        // Each movement is its own dropdown - no grouping by person
        // Attachments are assigned to exactly one movement using non-overlapping time windows
        // Window: (previous_movement.sent_at, this_movement.sent_at]
        // This captures attachments uploaded while the sender held the file
        $movementDropdowns = collect();
        $assignedAttachmentIds = [];

        foreach ($movements as $index => $movement) {
            $sender = $movement->sender;
            if (!$sender) continue;

            // Start = previous movement's sent_at (exclusive, already claimed by previous movement)
            // For the first movement, start from beginning of time
            $startTime = $index > 0 && isset($movements[$index - 1])
                ? $movements[$index - 1]->sent_at
                : null;

            // End = this movement's sent_at (inclusive, when the sender sent the file)
            $endTime = $movement->sent_at;

            $movementAttachments = $attachments->filter(function($attachment) use ($startTime, $endTime, &$assignedAttachmentIds) {
                if (in_array($attachment->id, $assignedAttachmentIds)) {
                    return false;
                }
                $afterStart = $startTime ? $attachment->created_at->gt($startTime) : true;
                $beforeEnd = $attachment->created_at->lte($endTime);
                return $afterStart && $beforeEnd;
            });

            // Mark as assigned
            foreach ($movementAttachments as $att) {
                $assignedAttachmentIds[] = $att->id;
            }

            $key = 'movement_' . $movement->id;
            $movementDropdowns[$key] = [
                'attachments' => $movementAttachments,
                'sender' => $sender,
                'movement' => $movement,
                'movement_number' => $index + 1,
            ];
        }

        // Handle attachments uploaded AFTER the last movement (current holder's attachments)
        $remainingAttachments = $attachments->filter(function($attachment) use ($assignedAttachmentIds) {
            return !in_array($attachment->id, $assignedAttachmentIds);
        });

        if ($remainingAttachments->isNotEmpty()) {
            $currentHolder = $this->file->currentHolder;
            $movementDropdowns['current_holder'] = [
                'attachments' => $remainingAttachments,
                'sender' => $currentHolder ?? $remainingAttachments->first()->uploader,
                'movement' => null,
                'movement_number' => $movements->count() + 1,
            ];
        }

        // If no movements exist, show all attachments in a single group
        if ($movements->isEmpty() && $attachments->isNotEmpty()) {
            $movementDropdowns['remaining'] = [
                'attachments' => $attachments,
                'sender' => $attachments->first()->uploader,
                'movement' => null,
                'movement_number' => 1,
            ];
        }

        return view('livewire.files.file-attachments', [
            'movementDropdowns' => $movementDropdowns,
            'canAdd' => $this->canAddAttachment(),
        ]);
    }
}
