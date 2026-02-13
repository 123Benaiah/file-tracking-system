<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\Unit;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithPagination;

class UnitManagement extends Component
{
    use WithPagination, WithToast;

    public $name;
    public $code;
    public $description;
    public $location;
    public $department_id;
    public $is_registry_unit = false;
    public $unit_id;

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
        'name' => 'required|string|max:255|unique:units,name',
        'code' => 'required|string|max:50|unique:units,code',
        'description' => 'nullable|string|max:500',
        'location' => 'nullable|string|max:255',
        'department_id' => 'required|exists:departments,id',
        'is_registry_unit' => 'boolean',
    ];

    public function render()
    {
        $registryDepartment = Department::where('is_registry_department', true)->first();

        // When no registry department exists, find the current registry unit (if any)
        $existingRegistryUnit = null;
        if (!$registryDepartment) {
            $existingRegistryUnit = Unit::where('is_registry_unit', true)->first();
        }

        return view('livewire.admin.unit-management', [
            'units' => Unit::with('department', 'employees')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('code', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->deptFilter, function ($query) {
                    $query->where('department_id', $this->deptFilter);
                })
                ->orderBy('name')
                ->paginate($this->perPage),
            'departments' => Department::where('has_units', true)->orderBy('name')->pluck('name', 'id'),
            'registryDepartment' => $registryDepartment,
            'existingRegistryUnit' => $existingRegistryUnit,
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
        $this->name = '';
        $this->code = '';
        $this->description = '';
        $this->location = '';
        $this->department_id = '';
        $this->is_registry_unit = false;
        $this->unit_id = null;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unit_id = $id;
        $this->name = $unit->name;
        $this->code = $unit->code;
        $this->description = $unit->description;
        $this->location = $unit->location;
        $this->department_id = $unit->department_id;
        $this->is_registry_unit = $unit->is_registry_unit;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;

        if ($this->editMode) {
            $rules['name'] = 'required|string|max:255|unique:units,name,' . $this->unit_id;
            $rules['code'] = 'required|string|max:50|unique:units,code,' . $this->unit_id;
        }

        $this->validate($rules);

        $registryDepartment = Department::where('is_registry_department', true)->first();

        if ($registryDepartment) {
            // A registry department exists
            if ($this->is_registry_unit && $this->department_id != $registryDepartment->id) {
                $this->toastError('Cannot Set Registry Unit', 'Only units under the Registry Department ("' . $registryDepartment->name . '") can be Registry Units.');
                return;
            }
            // Units under registry department are auto-registry (handled by department management)
            // If this unit is under the registry department, force it to be registry
            if ($this->department_id == $registryDepartment->id) {
                $this->is_registry_unit = true;
            }
        } else {
            // No registry department exists — only 1 unit system-wide can be registry
            if ($this->is_registry_unit) {
                $existingRegistryUnit = Unit::where('is_registry_unit', true)
                    ->where('id', '!=', $this->unit_id ?? 0)
                    ->first();

                if ($existingRegistryUnit) {
                    $this->toastError('Cannot Set Registry Unit', 'Another unit ("' . $existingRegistryUnit->name . '") is already the registry unit. Only one unit can be registry when no department is designated as registry.');
                    return;
                }
            }
        }

        Unit::updateOrCreate(
            ['id' => $this->unit_id],
            [
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'location' => $this->location,
                'department_id' => $this->department_id,
                'is_registry_unit' => $this->is_registry_unit,
            ]
        );

        $isEdit = $this->editMode;
        $this->closeModal();
        $this->toastSuccess($isEdit ? 'Unit Updated' : 'Unit Created', $isEdit ? 'Unit has been updated successfully.' : 'New unit has been created successfully.');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $unit = Unit::findOrFail($this->deleteId);

        if ($unit->is_registry_unit && $unit->employees()->where('is_active', true)->exists()) {
            $this->toastError('Cannot Delete', 'Cannot delete a Registry Unit with active employees. Remove registry status first or deactivate employees.');
            $this->showDeleteModal = false;
            $this->deleteId = null;
            return;
        }

        $unit->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->toastSuccess('Unit Deleted', 'Unit has been deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function updatedDepartmentId()
    {
        if ($this->department_id) {
            $department = Department::find($this->department_id);
            $registryDepartment = Department::where('is_registry_department', true)->first();

            if ($registryDepartment && $this->department_id == $registryDepartment->id) {
                // Auto-check registry for units under the registry department
                $this->is_registry_unit = true;
            } elseif ($registryDepartment) {
                // Under a non-registry department when a registry department exists — can't be registry
                $this->is_registry_unit = false;
            }
            // If no registry department exists, leave the checkbox as-is (user controls it)
        }
    }
}
