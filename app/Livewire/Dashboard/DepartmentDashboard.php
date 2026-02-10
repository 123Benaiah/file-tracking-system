<?php

namespace App\Livewire\Dashboard;

use App\Models\Department;
use App\Models\File;
use App\Models\FileMovement;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentDashboard extends Component
{
    use WithPagination, WithToast;

    public $search = '';

    public $statusFilter = '';

    public $perPage = 10;

    public $showAllFilesModal = false;

    public $allFilesSearch = '';

    public $allFilesStatus = '';

    public $allFilesPriority = '';

    public $allFilesDepartment = '';

    public $allFilesPerPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedAllFilesPerPage()
    {
        $this->resetPage('allFilesPage');
    }

    public function updatedAllFilesSearch()
    {
        $this->resetPage('allFilesPage');
    }

    public function updatedAllFilesStatus()
    {
        $this->resetPage('allFilesPage');
    }

    public function updatedAllFilesPriority()
    {
        $this->resetPage('allFilesPage');
    }

    public function updatedAllFilesDepartment()
    {
        $this->resetPage('allFilesPage');
    }

    public function openAllFilesModal()
    {
        $this->showAllFilesModal = true;
    }

    public function closeAllFilesModal()
    {
        $this->showAllFilesModal = false;
        $this->reset(['allFilesSearch', 'allFilesStatus', 'allFilesPriority', 'allFilesDepartment']);
    }

    public function confirmReceipt($movementId)
    {
        $movement = FileMovement::with('file')->find($movementId);

        if (! $movement) {
            $this->toastError('Not Found', 'File movement not found.');

            return;
        }

        if ($movement->intended_receiver_emp_no !== auth()->user()->employee_number) {
            $this->toastError('Unauthorized', 'You are not authorized to receive this file.');

            return;
        }

        $movement->update([
            'actual_receiver_emp_no' => auth()->user()->employee_number,
            'received_at' => now(),
            'movement_status' => 'received',
        ]);

        $movement->file->update([
            'status' => 'received',
            'current_holder' => auth()->user()->employee_number,
        ]);

        \App\Models\AuditLog::create([
            'employee_number' => auth()->user()->employee_number,
            'action' => 'file_received',
            'description' => 'Received file '.$movement->file->new_file_no.' from '.$movement->sender_emp_no,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'new_data' => $movement->fresh()->toArray(),
        ]);

        $this->toastSuccess('File Received', 'File "'.$movement->file->new_file_no.'" has been received successfully.');
    }

    public function render()
    {
        $user = auth()->user();

        $myFiles = File::query()
            ->where('current_holder', $user->employee_number)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('subject', 'like', '%'.$this->search.'%')
                        ->orWhere('file_title', 'like', '%'.$this->search.'%')
                        ->orWhere('new_file_no', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->with(['currentHolder', 'latestMovement'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $allFiles = collect();
        $departments = [];

        if ($this->showAllFilesModal) {
            $allFiles = File::query()
                ->when($this->allFilesSearch, function ($query) {
                    $query->where(function ($q) {
                        $q->where('subject', 'like', '%'.$this->allFilesSearch.'%')
                            ->orWhere('file_title', 'like', '%'.$this->allFilesSearch.'%')
                            ->orWhere('new_file_no', 'like', '%'.$this->allFilesSearch.'%');
                    });
                })
                ->when($this->allFilesStatus, function ($query) {
                    $query->where('status', $this->allFilesStatus);
                })
                ->when($this->allFilesPriority, function ($query) {
                    $query->where('priority', $this->allFilesPriority);
                })
                ->when($this->allFilesDepartment, function ($query) {
                    $query->where(function ($q) {
                        $q->whereHas('currentHolder.department', function ($q2) {
                            $q2->where('name', $this->allFilesDepartment);
                        })->orWhereHas('currentHolder.unit.department', function ($q2) {
                            $q2->where('name', $this->allFilesDepartment);
                        });
                    });
                })
                ->with(['currentHolder.department', 'currentHolder.unit', 'registeredBy'])
                ->orderBy('created_at', 'desc')
                ->paginate($this->allFilesPerPage, ['*'], 'allFilesPage');

            $departments = Department::orderBy('name')->pluck('name', 'id');
        }

        $pendingReceipts = FileMovement::where('intended_receiver_emp_no', $user->employee_number)
            ->where('movement_status', 'sent')
            ->with(['file', 'sender'])
            ->orderBy('sent_at', 'desc')
            ->paginate(5, ['*'], 'pendingPage');

        $sentPendingConfirmation = FileMovement::where('sender_emp_no', $user->employee_number)
            ->where('movement_status', 'sent')
            ->with(['file', 'intendedReceiver'])
            ->orderBy('sent_at', 'desc')
            ->paginate(5, ['*'], 'sentPendingPage');

        $recentlyReceived = FileMovement::where('actual_receiver_emp_no', $user->employee_number)
            ->where('movement_status', 'received')
            ->with(['file', 'sender'])
            ->orderBy('received_at', 'desc')
            ->limit(2)
            ->get();

        $stats = [
            'my_files' => File::where('current_holder', $user->employee_number)->count(),
            'pending_receipts' => FileMovement::where('intended_receiver_emp_no', $user->employee_number)
                ->where('movement_status', 'sent')
                ->count(),
            'sent_pending' => FileMovement::where('sender_emp_no', $user->employee_number)
                ->where('movement_status', 'sent')
                ->count(),
            'all_files' => File::count(),
        ];

        return view('livewire.dashboard.department-dashboard', [
            'myFiles' => $myFiles,
            'allFiles' => $allFiles,
            'pendingReceipts' => $pendingReceipts,
            'sentPendingConfirmation' => $sentPendingConfirmation,
            'stats' => $stats,
            'departments' => $departments,
            'recentlyReceived' => $recentlyReceived,
        ]);
    }
}
