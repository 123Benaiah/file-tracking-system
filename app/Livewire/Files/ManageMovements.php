<?php

namespace App\Livewire\Files;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\File;
use App\Models\FileMovement;
use App\Models\Employee;
use App\Traits\WithToast;

class ManageMovements extends Component
{
    use WithPagination, WithToast;

    public File $file;
    public $showEditModal = false;
    public $selectedMovement = null;
    public $search = '';
    public $statusFilter = '';
    public $perPage = 10;
    public $selectedMovements = [];
    public $showDeleteModal = false;
    public $selectAll = false;
    public $deletingId = null;

    public function getSelectedCountProperty()
    {
        return count($this->selectedMovements);
    }

    // Edit form fields
    public $sender_emp_no;
    public $intended_receiver_emp_no;
    public $actual_receiver_emp_no;
    public $movement_status;
    public $sender_comments;
    public $receiver_comments;
    public $sent_at;
    public $received_at;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function mount(File $file)
    {
        $this->file = $file;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedMovements = $this->getQuery()->pluck('id')->toArray();
        } else {
            $this->selectedMovements = [];
        }
    }

    public function updatedSelectedMovements()
    {
        $this->selectAll = false;
    }

    private function getQuery()
    {
        return $this->file->movements()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('sender', function ($sq) {
                        $sq->where('name', 'like', '%'.$this->search.'%')
                           ->orWhere('employee_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('intendedReceiver', function ($sq) {
                        $sq->where('name', 'like', '%'.$this->search.'%')
                           ->orWhere('employee_number', 'like', '%'.$this->search.'%');
                    });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('movement_status', $this->statusFilter);
            });
    }

    public function confirmDeleteSelected()
    {
        if (empty($this->selectedMovements)) {
            $this->toastError('No Selection', 'Please select at least one movement to delete.');
            return;
        }
        $this->showDeleteModal = true;
    }

    public function deleteSelected()
    {
        $count = FileMovement::whereIn('id', $this->selectedMovements)->count();
        FileMovement::whereIn('id', $this->selectedMovements)->delete();
        
        $this->toastSuccess('Movements Deleted', "$count movement(s) have been deleted successfully.");
        $this->showDeleteModal = false;
        $this->selectedMovements = [];
        $this->selectAll = false;
    }

    public function openEditModal($movementId)
    {
        $this->selectedMovement = FileMovement::findOrFail($movementId);

        $this->sender_emp_no = $this->selectedMovement->sender_emp_no;
        $this->intended_receiver_emp_no = $this->selectedMovement->intended_receiver_emp_no;
        $this->actual_receiver_emp_no = $this->selectedMovement->actual_receiver_emp_no;
        $this->movement_status = $this->selectedMovement->movement_status;
        $this->sender_comments = $this->selectedMovement->sender_comments;
        $this->receiver_comments = $this->selectedMovement->receiver_comments;
        $this->sent_at = $this->selectedMovement->sent_at->format('Y-m-d\TH:i');
        $this->received_at = $this->selectedMovement->received_at?->format('Y-m-d\TH:i');

        $this->showEditModal = true;
    }

    public function updateMovement()
    {
        $this->validate([
            'sender_emp_no' => 'required|exists:employees,employee_number',
            'intended_receiver_emp_no' => 'required|exists:employees,employee_number',
            'actual_receiver_emp_no' => 'nullable|exists:employees,employee_number',
            'movement_status' => 'required|in:sent,delivered,received,acknowledged,rejected',
            'sender_comments' => 'nullable|string',
            'receiver_comments' => 'nullable|string',
            'sent_at' => 'required|date',
            'received_at' => 'nullable|date',
        ]);

        $this->selectedMovement->update([
            'sender_emp_no' => $this->sender_emp_no,
            'intended_receiver_emp_no' => $this->intended_receiver_emp_no,
            'actual_receiver_emp_no' => $this->actual_receiver_emp_no,
            'movement_status' => $this->movement_status,
            'sender_comments' => $this->sender_comments,
            'receiver_comments' => $this->receiver_comments,
            'sent_at' => $this->sent_at,
            'received_at' => $this->received_at,
        ]);

        $this->toastSuccess('Movement Updated', 'File movement has been updated successfully.');

        $this->closeModal();
    }

    public function deleteMovement($movementId)
    {
        $this->deletingId = $movementId;
        
        $movement = FileMovement::findOrFail($movementId);
        $movement->delete();

        $this->deletingId = null;
        $this->toastSuccess('Movement Deleted', 'File movement has been deleted successfully.');
    }

    public function closeModal()
    {
        $this->showEditModal = false;
        $this->reset(['selectedMovement', 'sender_emp_no', 'intended_receiver_emp_no', 'actual_receiver_emp_no', 'movement_status', 'sender_comments', 'receiver_comments', 'sent_at', 'received_at']);
    }

    public function render()
    {
        $movements = $this->getQuery()
            ->with(['sender', 'intendedReceiver', 'actualReceiver'])
            ->orderBy('sent_at', 'desc')
            ->paginate($this->perPage);

        $employees = Employee::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.files.manage-movements', [
            'movements' => $movements,
            'employees' => $employees,
        ]);
    }
}
