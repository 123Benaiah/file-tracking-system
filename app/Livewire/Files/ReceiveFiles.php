<?php

namespace App\Livewire\Files;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FileMovement;
use App\Models\File;
use App\Traits\WithToast;

class ReceiveFiles extends Component
{
    use WithPagination, WithToast;

    public $search = '';
    public $perPage = 10;

    public function mount()
    {
        //
    }

    public function confirmReceipt($movementId)
    {
        $movement = FileMovement::with('file')->find($movementId);

        if (!$movement) {
            $this->toastError('Not Found', 'File movement not found.');
            return;
        }

        // Verify this user is the intended receiver
        if ($movement->intended_receiver_emp_no !== auth()->user()->employee_number) {
            $this->toastError('Unauthorized', 'You are not authorized to receive this file.');
            return;
        }

        // Update the movement
        $movement->update([
            'actual_receiver_emp_no' => auth()->user()->employee_number,
            'received_at' => now(),
            'movement_status' => 'received',
        ]);

        // Update the file status and current holder
        $movement->file->update([
            'status' => 'received',
            'current_holder' => auth()->user()->employee_number,
        ]);

        // Log the action
        \App\Models\AuditLog::create([
            'employee_number' => auth()->user()->employee_number,
            'action' => 'file_received',
            'description' => 'Received file ' . $movement->file->new_file_no . ' from ' . $movement->sender_emp_no,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'new_data' => $movement->fresh()->toArray(),
        ]);

        $this->toastSuccess('File Received', 'File "' . $movement->file->new_file_no . '" has been received successfully.');
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        // Get pending file movements for this user
        $pendingMovements = FileMovement::where('intended_receiver_emp_no', $user->employee_number)
            ->where('movement_status', 'sent')
            ->when($this->search, function ($query) {
                $query->whereHas('file', function ($q) {
                    $q->where('subject', 'like', '%'.$this->search.'%')
                      ->orWhere('file_title', 'like', '%'.$this->search.'%')
                      ->orWhere('new_file_no', 'like', '%'.$this->search.'%');
                });
            })
            ->with(['file', 'sender', 'intendedReceiver'])
            ->orderBy('sent_at', 'desc')
            ->paginate($this->perPage);

        // Recently received files
        $recentlyReceived = FileMovement::where('actual_receiver_emp_no', $user->employee_number)
            ->where('movement_status', 'received')
            ->with(['file', 'sender', 'intendedReceiver'])
            ->orderBy('received_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.files.receive-files', [
            'pendingMovements' => $pendingMovements,
            'recentlyReceived' => $recentlyReceived,
        ]);
    }
}
