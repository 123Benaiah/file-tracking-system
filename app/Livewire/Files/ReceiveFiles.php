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

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        // Received files with pagination — only the latest movement per file you currently hold
        $latestMovementIds = FileMovement::where('actual_receiver_emp_no', $user->employee_number)
            ->where('movement_status', 'received')
            ->whereHas('file', function ($q) use ($user) {
                $q->where('current_holder', $user->employee_number);
            })
            ->selectRaw('MAX(id) as id')
            ->groupBy('file_id')
            ->pluck('id');

        $receivedFiles = FileMovement::whereIn('id', $latestMovementIds)
            ->when($this->search, function ($query) {
                $query->whereHas('file', function ($q) {
                    $q->where('subject', 'like', '%'.$this->search.'%')
                      ->orWhere('file_title', 'like', '%'.$this->search.'%')
                      ->orWhere('new_file_no', 'like', '%'.$this->search.'%');
                });
            })
            ->with(['file', 'sender.departmentRel', 'sender.unitRel.department', 'intendedReceiver'])
            ->orderBy('received_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.files.receive-files', [
            'receivedFiles' => $receivedFiles,
        ]);
    }
}
