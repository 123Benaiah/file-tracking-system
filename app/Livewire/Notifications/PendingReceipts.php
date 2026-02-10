<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use App\Models\FileMovement;
use Illuminate\Support\Facades\Auth;

class PendingReceipts extends Component
{
    public $pendingReceipts = [];
    public $showPopup = false;
    public $selectedMovementId = null;
    public $receiverComments = '';
    public $dismissed = false;

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
            ->with(['file', 'sender'])
            ->orderBy('sent_at', 'desc')
            ->get();

        // Show popup if there are pending receipts and not dismissed
        $this->showPopup = $this->pendingReceipts->count() > 0 && !$this->dismissed;
    }

    public function dismissPopup()
    {
        $this->dismissed = true;
        $this->showPopup = false;
    }

    public function showPopupAgain()
    {
        $this->dismissed = false;
        $this->showPopup = $this->pendingReceipts->count() > 0;
    }

    public function getSelectedMovementProperty()
    {
        if (!$this->selectedMovementId) {
            return null;
        }

        return FileMovement::with(['file', 'sender', 'intendedReceiver'])
            ->find($this->selectedMovementId);
    }

    public function selectMovement($movementId)
    {
        $movement = FileMovement::with(['file', 'sender', 'intendedReceiver'])
            ->findOrFail($movementId);

        // Verify this user is the intended receiver
        if ($movement->intended_receiver_emp_no !== auth()->user()->employee_number) {
            session()->flash('error', 'You are not authorized to receive this file.');
            return;
        }

        $this->selectedMovementId = $movementId;
    }

    public function confirmReceipt()
    {
        if (!$this->selectedMovementId) {
            session()->flash('error', 'No file movement selected.');
            return;
        }

        $movement = FileMovement::with('file')->find($this->selectedMovementId);

        if (!$movement) {
            session()->flash('error', 'File movement not found.');
            $this->closeConfirmation();
            return;
        }

        // Verify this user is the intended receiver
        if ($movement->intended_receiver_emp_no !== auth()->user()->employee_number) {
            session()->flash('error', 'You are not authorized to receive this file.');
            $this->closeConfirmation();
            return;
        }

        // Update the movement
        $movement->update([
            'actual_receiver_emp_no' => auth()->user()->employee_number,
            'received_at' => now(),
            'movement_status' => 'received',
            'receiver_comments' => $this->receiverComments,
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

        session()->flash('success', 'File "' . $movement->file->new_file_no . '" received successfully.');

        // Reset and reload
        $this->closeConfirmation();
        $this->loadPendingReceipts();

        // Dispatch event to refresh other components
        $this->dispatch('fileReceived');
    }

    public function closeConfirmation()
    {
        $this->selectedMovementId = null;
        $this->receiverComments = '';
    }

    public function render()
    {
        return view('livewire.notifications.pending-receipts');
    }
}
