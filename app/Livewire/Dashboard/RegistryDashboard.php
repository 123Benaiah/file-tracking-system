<?php

namespace App\Livewire\Dashboard;

use App\Models\Employee;
use App\Models\File;
use App\Models\FileMovement;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithPagination;

class RegistryDashboard extends Component
{
    use WithPagination, WithToast;

    public $search = '';

    public $statusFilter = '';

    public $priorityFilter = '';

    public $departmentFilter = '';

    public $dateFrom = '';

    public $dateTo = '';

    public $showFilters = false;

    public $selectedFiles = [];

    public $selectAll = false;

    public $showDeleteModal = false;

    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatedDepartmentFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = ! $this->showFilters;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedFiles = $this->getFilteredFileIds();
        } else {
            $this->selectedFiles = [];
        }
    }

    public function updatedSelectedFiles()
    {
        $this->selectAll = false;
    }

    protected function getFilteredQuery()
    {
        return File::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('subject', 'like', '%'.$this->search.'%')
                        ->orWhere('file_title', 'like', '%'.$this->search.'%')
                        ->orWhere('new_file_no', 'like', '%'.$this->search.'%')
                        ->orWhere('old_file_no', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($query) {
                $query->where('priority', $this->priorityFilter);
            })
            ->when($this->departmentFilter, function ($query) {
                $query->whereHas('currentHolder', function ($q) {
                    $q->whereHas('organizationalUnit', function ($q2) {
                        $q2->where('name', $this->departmentFilter);
                    });
                });
            })
            ->when($this->dateFrom && $this->dateTo, function ($query) {
                $query->whereBetween('date_registered', [$this->dateFrom, $this->dateTo]);
            });
    }

    protected function getFilteredFileIds()
    {
        return $this->getFilteredQuery()->pluck('id')->toArray();
    }

    public function getSelectedCountProperty()
    {
        return count($this->selectedFiles);
    }

    public function confirmDeleteSelected()
    {
        if (empty($this->selectedFiles)) {
            $this->toastError('No Selection', 'Please select at least one file to delete.');

            return;
        }
        $this->showDeleteModal = true;
    }

    public function deleteSelected()
    {
        $count = File::whereIn('id', $this->selectedFiles)->count();
        File::whereIn('id', $this->selectedFiles)->delete();

        $this->toastSuccess('Files Deleted', "$count file(s) have been deleted successfully.");
        $this->showDeleteModal = false;
        $this->selectedFiles = [];
        $this->selectAll = false;
    }

    public function exportReport($type = 'filtered')
    {
        if ($type === 'selected' && ! empty($this->selectedFiles)) {
            $files = File::whereIn('id', $this->selectedFiles)
                ->with(['currentHolder'])
                ->get();
        } else {
            $files = $this->getFilteredQuery()
                ->with(['currentHolder'])
                ->get();
        }

        return response()->streamDownload(function () use ($files) {
            echo "File No,Subject,Status,Priority,Current Holder,Department,Date Registered,Due Date\n";
            foreach ($files as $file) {
                echo implode(',', [
                    $file->new_file_no,
                    '"'.str_replace('"', '""', $file->subject).'"',
                    ucwords(str_replace('_', ' ', $file->status)),
                    ucwords(str_replace('_', ' ', $file->priority)),
                    $file->currentHolder?->name ?? 'N/A',
                    $file->currentHolder?->department ?? 'N/A',
                    $file->date_registered->format('Y-m-d'),
                    $file->due_date?->format('Y-m-d') ?? 'N/A',
                ])."\n";
            }
        }, 'file-report-'.now()->format('Y-m-d-His').'.csv');
    }

    public function confirmReceipt($movementId)
    {
        $movement = FileMovement::with('file')->find($movementId);

        if (! $movement) {
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

        $files = $this->getFilteredQuery()
            ->with(['currentHolder', 'latestMovement.intendedReceiver', 'movements'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        // Pending receipts for this registry user (files sent TO me)
        $pendingReceipts = FileMovement::where('intended_receiver_emp_no', $user->employee_number)
            ->where('movement_status', 'sent')
            ->with(['file', 'sender'])
            ->orderBy('sent_at', 'desc')
            ->paginate(5, ['*'], 'pendingPage');

        // Files I've sent that are pending confirmation (files sent BY me)
        $sentPendingConfirmation = FileMovement::where('sender_emp_no', $user->employee_number)
            ->where('movement_status', 'sent')
            ->with(['file', 'intendedReceiver'])
            ->orderBy('sent_at', 'desc')
            ->paginate(5, ['*'], 'sentPendingPage');

        $stats = [
            'total' => File::count(),
            'at_registry' => File::where('status', 'at_registry')->count(),
            'in_transit' => File::where('status', 'in_transit')->count(),
            'overdue' => File::overdue()->count(),
            'urgent' => File::where('priority', 'urgent')->orWhere('priority', 'very_urgent')->count(),
            'pending_receipts' => FileMovement::where('intended_receiver_emp_no', $user->employee_number)
                ->where('movement_status', 'sent')
                ->count(),
            'sent_pending' => FileMovement::where('sender_emp_no', $user->employee_number)
                ->where('movement_status', 'sent')
                ->count(),
        ];

        $departments = Employee::where(function ($query) {
                $query->whereNotNull('department_id')
                      ->orWhereHas('unitRel');
            })
            ->where('is_active', true)
            ->get()
            ->map(function ($employee) {
                // Get department name from effective department
                return $employee->department;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $recentlyReceived = FileMovement::where('actual_receiver_emp_no', $user->employee_number)
            ->where('movement_status', 'received')
            ->with(['file', 'sender'])
            ->orderBy('received_at', 'desc')
            ->limit(2)
            ->get();

        return view('livewire.dashboard.registry-dashboard', [
            'files' => $files,
            'stats' => $stats,
            'departments' => $departments,
            'pendingReceipts' => $pendingReceipts,
            'sentPendingConfirmation' => $sentPendingConfirmation,
            'recentlyReceived' => $recentlyReceived,
        ]);
    }
}
