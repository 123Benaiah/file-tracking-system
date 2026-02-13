<?php

namespace App\Livewire\Files;

use App\Models\FileMovement;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithPagination;

class ConfirmFiles extends Component
{
    use WithPagination, WithToast;

    public $search = '';
    public $perPage = 10;
    public $confirmingIds = [];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function confirmReceipt($movementId)
    {
        $this->confirmingIds[] = $movementId;
        
        $movement = FileMovement::with('file')->find($movementId);

        if (!$movement) {
            $this->confirmingIds = array_diff($this->confirmingIds, [$movementId]);
            $this->toastError('Not Found', 'File movement not found.');
            return;
        }

        $user = auth()->user();
        if ($movement->intended_receiver_emp_no !== $user->employee_number) {
            $this->confirmingIds = array_diff($this->confirmingIds, [$movementId]);
            $this->toastError('Unauthorized', 'You are not authorized to receive this file.');
            return;
        }

        $movement->update([
            'actual_receiver_emp_no' => $user->employee_number,
            'received_at' => now(),
            'movement_status' => 'received',
        ]);

        $isReturningToRegistry = $user->isRegistryStaff();

        $movement->file->update([
            'status' => $isReturningToRegistry ? 'completed' : 'received',
            'current_holder' => $user->employee_number,
        ]);

        \App\Models\AuditLog::create([
            'employee_number' => $user->employee_number,
            'action' => $isReturningToRegistry ? 'file_returned_to_registry' : 'file_received',
            'description' => $isReturningToRegistry
                ? 'Returned file '.$movement->file->new_file_no.' to registry'
                : 'Received file '.$movement->file->new_file_no.' from '.$movement->sender_emp_no,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'new_data' => $movement->fresh()->toArray(),
        ]);

        $this->confirmingIds = array_diff($this->confirmingIds, [$movementId]);
        
        $this->toastSuccess(
            $isReturningToRegistry ? 'File Returned to Registry' : 'File Received',
            $isReturningToRegistry
                ? 'File "'.$movement->file->new_file_no.'" has been returned to registry and marked as completed.'
                : 'File "'.$movement->file->new_file_no.'" has been received successfully.'
        );

        $this->dispatch('receipt-confirmed');
    }

    public function render()
    {
        $user = auth()->user();

        $pendingReceipts = FileMovement::where('intended_receiver_emp_no', $user->employee_number)
            ->where('movement_status', 'sent')
            ->whereHas('file', function ($q) {
                $q->where('status', '!=', 'merged');
            })
            ->when($this->search, function ($query) {
                $query->whereHas('file', function ($q) {
                    $q->where('subject', 'like', '%'.$this->search.'%')
                        ->orWhere('new_file_no', 'like', '%'.$this->search.'%');
                });
            })
            ->with(['file', 'sender'])
            ->orderBy('sent_at', 'desc')
            ->paginate($this->perPage);

        $stats = [
            'pending_count' => FileMovement::where('intended_receiver_emp_no', $user->employee_number)
                ->where('movement_status', 'sent')
                ->whereHas('file', function ($q) {
                    $q->where('status', '!=', 'merged');
                })
                ->count(),
        ];

        return view('livewire.files.confirm-files', [
            'pendingReceipts' => $pendingReceipts,
            'stats' => $stats,
        ]);
    }
}
