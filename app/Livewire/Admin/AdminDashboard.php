<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\File;
use App\Models\FileMovement;
use App\Models\AuditLog;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $perPage = 10;
    public $showDeleteModal = false;
    public $showClearModal = false;
    public $deleteId = null;

    protected $queryString = [
        'perPage' => ['except' => 10],
    ];

    public function render()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('is_active', true)->count(),
            'inactive_employees' => Employee::where('is_active', false)->count(),
            'total_files' => File::count(),
            'files_in_transit' => File::where('status', 'in_transit')->count(),
            'files_at_registry' => File::where('status', 'at_registry')->count(),
            'files_completed' => File::where('status', 'completed')->count(),
            'pending_movements' => FileMovement::where('movement_status', 'sent')->count(),
        ];

        $recentActivities = AuditLog::with('employee')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.admin-dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
        ]);
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteActivity()
    {
        AuditLog::find($this->deleteId)?->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function confirmClearAll()
    {
        $this->showClearModal = true;
    }

    public function clearAllActivity()
    {
        AuditLog::truncate();
        $this->showClearModal = false;
    }

    public function cancelClear()
    {
        $this->showClearModal = false;
    }
}
