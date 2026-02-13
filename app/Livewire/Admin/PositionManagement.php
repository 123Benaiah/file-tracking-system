<?php

namespace App\Livewire\Admin;

use App\Models\Position;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithPagination;

class PositionManagement extends Component
{
    use WithPagination, WithToast;

    public $title;
    public $code;
    public $description;
    public $position_type;
    public $level;
    public $employment_type;
    public $position_id;

    public $search = '';
    public $typeFilter = '';
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
        'title' => 'required|string|max:255|unique:positions,title',
        'code' => 'required|string|max:50|unique:positions,code',
        'description' => 'nullable|string|max:500',
        'position_type' => 'required|in:director,assistant_director,supervisor,staff,support',
        'level' => 'nullable|integer|min:1',
        'employment_type' => 'nullable|in:permanent,contract,temporary,intern',
    ];

    public function render()
    {
        return view('livewire.admin.position-management', [
            'positions' => Position::with('employees')
                ->when($this->search, function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                })
                ->when($this->typeFilter, function ($query) {
                    $query->where('position_type', $this->typeFilter);
                })
                ->orderBy('level')
                ->paginate($this->perPage),
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
        $this->title = '';
        $this->code = '';
        $this->description = '';
        $this->position_type = 'staff';
        $this->level = 1;
        $this->employment_type = 'permanent';
        $this->position_id = null;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $position = Position::findOrFail($id);
        $this->position_id = $id;
        $this->title = $position->title;
        $this->code = $position->code;
        $this->description = $position->description;
        $this->position_type = $position->position_type;
        $this->level = $position->level;
        $this->employment_type = $position->employment_type;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate($this->editMode ? array_merge($this->rules, ['title' => 'required|string|max:255|unique:positions,title,' . $this->position_id, 'code' => 'required|string|max:50|unique:positions,code,' . $this->position_id]) : $this->rules);

        Position::updateOrCreate(
            ['id' => $this->position_id],
            [
                'title' => $this->title,
                'code' => $this->code,
                'description' => $this->description,
                'position_type' => $this->position_type,
                'level' => $this->level ?? 1,
                'employment_type' => $this->employment_type,
            ]
        );

        $this->closeModal();
        $this->toastSuccess($this->editMode ? 'Position Updated' : 'Position Created', $this->editMode ? 'Position has been updated successfully.' : 'New position has been created successfully.');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Position::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->toastSuccess('Position Deleted', 'Position has been deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }
}
