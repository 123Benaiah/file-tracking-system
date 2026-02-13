<?php

namespace App\Livewire\Admin;

use App\Models\Position;
use App\Models\Unit;
use App\Models\UnitHead;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithPagination;

class UnitHeadManagement extends Component
{
    use WithPagination, WithToast;

    public $unit_id;
    public $position_id;
    public $effective_date;
    public $end_date;
    public $is_active = true;
    public $unit_head_id;

    public $search = '';
    public $unitFilter = '';
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
        'unit_id' => 'required|exists:units,id',
        'position_id' => 'required|exists:positions,id',
        'effective_date' => 'required|date',
        'end_date' => 'nullable|date|after:effective_date',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        return view('livewire.admin.unit-head-management', [
            'unitHeads' => UnitHead::with(['unit', 'unit.department', 'position'])
                ->when($this->search, function ($query) {
                    $query->whereHas('unit', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('position', function ($q) {
                        $q->where('title', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->unitFilter, function ($query) {
                    $query->where('unit_id', $this->unitFilter);
                })
                ->orderBy('effective_date', 'desc')
                ->paginate($this->perPage),
            'units' => Unit::with('department')->orderBy('name')->get()->map(function ($u) {
                return ['id' => $u->id, 'name' => $u->name . ' (' . $u->department->name . ')'];
            })->pluck('name', 'id'),
            'positions' => Position::assistantDirectors()->orderBy('level')->pluck('title', 'id'),
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
        $this->unit_id = '';
        $this->position_id = '';
        $this->effective_date = now()->format('Y-m-d');
        $this->end_date = '';
        $this->is_active = true;
        $this->unit_head_id = null;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $uh = UnitHead::findOrFail($id);
        $this->unit_head_id = $id;
        $this->unit_id = $uh->unit_id;
        $this->position_id = $uh->position_id;
        $this->effective_date = $uh->effective_date->format('Y-m-d');
        $this->end_date = $uh->end_date?->format('Y-m-d');
        $this->is_active = $uh->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        UnitHead::updateOrCreate(
            ['id' => $this->unit_head_id],
            [
                'unit_id' => $this->unit_id,
                'position_id' => $this->position_id,
                'effective_date' => $this->effective_date,
                'end_date' => $this->end_date ?: null,
                'is_active' => $this->is_active,
            ]
        );

        $this->closeModal();
        $this->toastSuccess($this->editMode ? 'Unit Head Updated' : 'Unit Head Assigned', $this->editMode ? 'Unit head assignment has been updated successfully.' : 'Unit head has been assigned successfully.');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        UnitHead::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->toastSuccess('Assignment Deleted', 'Unit head assignment has been deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleStatus($id)
    {
        $uh = UnitHead::findOrFail($id);
        $uh->update(['is_active' => !$uh->is_active]);
        $this->toastSuccess('Status Updated', 'Status has been updated successfully.');
    }
}
