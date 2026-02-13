<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use App\Models\FileMovement;
use Illuminate\Support\Facades\Auth;

class PendingReceipts extends Component
{
    public $pendingReceipts = [];
    public $showPopup = false;

    protected $listeners = ['refreshPendingReceipts' => 'loadPendingReceipts'];

    public function mount()
    {
        $this->loadPendingReceipts();
    }

    public function loadPendingReceipts()
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        $this->pendingReceipts = FileMovement::where('intended_receiver_emp_no', $user->employee_number)
            ->where('movement_status', 'sent')
            ->whereHas('file', function ($q) {
                $q->where('status', '!=', 'merged');
            })
            ->with(['file', 'sender'])
            ->orderBy('sent_at', 'desc')
            ->get();

        $this->showPopup = $this->pendingReceipts->count() > 0 && !session('pending_receipts_dismissed_' . $user->employee_number, false);
    }

    public function dismissPopup()
    {
        session(['pending_receipts_dismissed_' . Auth::user()->employee_number => true]);
        $this->showPopup = false;
    }

    public function showPopupAgain()
    {
        session(['pending_receipts_dismissed_' . Auth::user()->employee_number => false]);
        $this->loadPendingReceipts();
    }

    public function quickConfirm($movementId)
    {
        $movement = FileMovement::with('file')->find($movementId);

        if (!$movement) {
            $this->dispatch('notify', type: 'error', message: 'File movement not found.');
            return;
        }

        $user = Auth::user();
        if ($movement->intended_receiver_emp_no !== $user->employee_number) {
            $this->dispatch('notify', type: 'error', message: 'You are not authorized to receive this file.');
            return;
        }

        $movement->update([
            'actual_receiver_emp_no' => $user->employee_number,
            'received_at' => now(),
            'movement_status' => 'received',
        ]);

        // If receiver is registry staff, file is completed
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

        $this->dispatch('notify', type: 'success', message: $isReturningToRegistry
            ? 'File "'.$movement->file->new_file_no.'" returned to registry and completed.'
            : 'File "'.$movement->file->new_file_no.'" confirmed successfully.');
        $this->dispatch('fileReceived');
        $this->loadPendingReceipts();
    }

    public function render()
    {
        return view('livewire.notifications.pending-receipts');
    }
}
