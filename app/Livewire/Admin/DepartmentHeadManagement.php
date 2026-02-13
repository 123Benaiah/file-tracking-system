<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\DepartmentHead;
use App\Models\Position;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentHeadManagement extends Component
{
    use WithPagination, WithToast;

    public $department_id;
    public $position_id;
    public $effective_date;
    public $end_date;
    public $is_active = true;
    public $department_head_id;

    public $search = '';
    public $deptFilter = '';
    public $perPage = 10;

    public $showModal = false;
    public $editMode = false;
    public $deleteId = null;
    public $showDeleteModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $rules = [
        'department_id' => 'required|exists:departments,id',
        'position_id' => 'required|exists:positions,id',
        'effective_date' => 'required|date',
        'end_date' => 'nullable|date|after:effective_date',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        return view('livewire.admin.department-head-management', [
            'departmentHeads' => DepartmentHead::with(['department', 'position', 'employee'])
                ->when($this->search, function ($query) {
                    $query->whereHas('department', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('position', function ($q) {
                        $q->where('title', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->deptFilter, function ($query) {
                    $query->where('department_id', $this->deptFilter);
                })
                ->orderBy('effective_date', 'desc')
                ->paginate($this->perPage),
            'departments' => Department::orderBy('name')->pluck('name', 'id'),
            'positions' => Position::directors()->orderBy('level')->pluck('title', 'id'),
        ]);
    }

    public function openModal()
    {
        $this->resetFields();
        $this->showModal = true;
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
        $this->resetErrorBag();
    }

    public function resetFields()
    {
        $this->department_id = '';
        $this->position_id = '';
        $this->effective_date = now()->format('Y-m-d');
        $this->end_date = '';
        $this->is_active = true;
        $this->department_head_id = null;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $dh = DepartmentHead::findOrFail($id);
        $this->department_head_id = $id;
        $this->department_id = $dh->department_id;
        $this->position_id = $dh->position_id;
        $this->effective_date = $dh->effective_date->format('Y-m-d');
        $this->end_date = $dh->end_date?->format('Y-m-d');
        $this->is_active = $dh->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        DepartmentHead::updateOrCreate(
            ['id' => $this->department_head_id],
            [
                'department_id' => $this->department_id,
                'position_id' => $this->position_id,
                'effective_date' => $this->effective_date,
                'end_date' => $this->end_date ?: null,
                'is_active' => $this->is_active,
            ]
        );

        $this->closeModal();
        $this->toastSuccess($this->editMode ? 'Department Head Updated' : 'Department Head Assigned', $this->editMode ? 'Department head assignment has been updated successfully.' : 'Department head has been assigned successfully.');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        DepartmentHead::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->toastSuccess('Assignment Deleted', 'Department head assignment has been deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        $dh = DepartmentHead::findOrFail($id);
        $dh->update(['is_active' => !$dh->is_active]);
        $this->toastSuccess('Status Updated', 'Status has been updated successfully.');
    }
}
