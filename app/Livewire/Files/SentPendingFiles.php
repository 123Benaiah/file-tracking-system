<?php

namespace App\Livewire\Files;

use App\Models\FileMovement;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithPagination;

class SentPendingFiles extends Component
{
    use WithPagination, WithToast;

    public $search = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function render()
    {
        $user = auth()->user();

        $sentPending = FileMovement::where('sender_emp_no', $user->employee_number)
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
            ->with(['file', 'intendedReceiver'])
            ->orderBy('sent_at', 'desc')
            ->paginate($this->perPage);

        $stats = [
            'sent_pending_count' => $sentPending->total(),
        ];

        return view('livewire.files.sent-pending-files', [
            'sentPending' => $sentPending,
            'stats' => $stats,
        ]);
    }
}
