<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\Unit;
use App\Traits\WithToast;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentManagement extends Component
{
    use WithPagination, WithToast;

    public $name;
    public $code;
    public $description;
    public $location;
    public $has_units = false;
    public $is_registry_department = false;
    public $department_id;

    public $search = '';
    public $perPage = 10;

    public $showModal = false;
    public $editMode = false;
    public $deleteId = null;
    public $showDeleteModal = false;

    // Confirmation modal for unchecking registry
    public $showUncheckRegistryModal = false;
    public $registryUnitsToUncheck = [];
    public $pendingSaveData = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $rules = [
        'name' => 'required|string|max:255|unique:departments,name',
        'code' => 'required|string|max:50|unique:departments,code',
        'description' => 'nullable|string|max:500',
        'location' => 'nullable|string|max:255',
        'has_units' => 'boolean',
        'is_registry_department' => 'boolean',
    ];

    public function render()
    {
        return view('livewire.admin.department-management', [
            'departments' => Department::with('units', 'employees')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('code', 'like', '%' . $this->search . '%');
                    });
                })
                ->orderBy('name')
                ->paginate($this->perPage),
            'existingRegistryDepartment' => Department::where('is_registry_department', true)->first(),
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
        $this->has_units = false;
        $this->is_registry_department = false;
        $this->department_id = null;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $this->department_id = $id;
        $this->name = $department->name;
        $this->code = $department->code;
        $this->description = $department->description;
        $this->location = $department->location;
        $this->has_units = $department->has_units;
        $this->is_registry_department = $department->is_registry_department;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;

        if ($this->editMode && $this->department_id) {
            $rules['name'] = 'required|string|max:255|unique:departments,name,' . $this->department_id;
            $rules['code'] = 'required|string|max:50|unique:departments,code,' . $this->department_id;
        }

        $validated = $this->validate($rules);

        // Check if another department is already registry
        $existingRegistry = Department::where('is_registry_department', true)
            ->where('id', '!=', $this->department_id ?? 0)
            ->first();

        if ($this->is_registry_department && $existingRegistry) {
            $this->toastError('Cannot Set Registry', 'Another department ("' . $existingRegistry->name . '") is already the Registry Department.');
            return;
        }

        // When CHECKING is_registry_department = true
        if ($this->is_registry_department) {
            // Check no standalone registry unit exists outside this department
            $standaloneRegistryUnit = Unit::where('is_registry_unit', true)
                ->where('department_id', '!=', $this->department_id ?? 0)
                ->first();

            if ($standaloneRegistryUnit) {
                $this->toastError('Cannot Set Registry', 'Unit "' . $standaloneRegistryUnit->name . '" is currently the sole registry unit. Remove its registry status first.');
                return;
            }
        }

        // When UNCHECKING is_registry_department (was previously true)
        if ($this->editMode && $this->department_id && !$this->is_registry_department) {
            $currentDepartment = Department::find($this->department_id);
            if ($currentDepartment && $currentDepartment->is_registry_department) {
                // Check if there are registry units under this department
                $registryUnits = Unit::where('department_id', $this->department_id)
                    ->where('is_registry_unit', true)
                    ->pluck('name')
                    ->toArray();

                if (count($registryUnits) > 0) {
                    // Show confirmation modal instead of saving immediately
                    $this->registryUnitsToUncheck = $registryUnits;
                    $this->pendingSaveData = [
                        'name' => $this->name,
                        'code' => $this->code,
                        'description' => $this->description,
                        'location' => $this->location,
                        'has_units' => $this->has_units,
                        'is_registry_department' => false,
                    ];
                    $this->showUncheckRegistryModal = true;
                    return;
                }
            }
        }

        $department = Department::updateOrCreate(
            ['id' => $this->department_id],
            [
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'location' => $this->location,
                'has_units' => $this->has_units,
                'is_registry_department' => $this->is_registry_department,
            ]
        );

        // When setting as registry department, auto-set ALL units under it to registry
        if ($this->is_registry_department) {
            Unit::where('department_id', $department->id)
                ->update(['is_registry_unit' => true]);
        }

        $isEdit = $this->editMode;
        $this->closeModal();
        $this->toastSuccess($isEdit ? 'Department Updated' : 'Department Created', $isEdit ? 'Department has been updated successfully.' : 'New department has been created successfully.');
    }

    public function confirmUncheckRegistry()
    {
        if (empty($this->pendingSaveData)) {
            $this->showUncheckRegistryModal = false;
            return;
        }

        $unitCount = count($this->registryUnitsToUncheck);

        // Save department with registry status removed
        Department::where('id', $this->department_id)->update($this->pendingSaveData);

        // Remove registry status from ALL units under this department
        Unit::where('department_id', $this->department_id)
            ->update(['is_registry_unit' => false]);

        $this->showUncheckRegistryModal = false;
        $this->registryUnitsToUncheck = [];
        $this->pendingSaveData = [];
        $this->closeModal();
        $this->toastSuccess('Department Updated', 'Registry status removed from ' . $unitCount . ' unit(s). Only 1 unit can now be registry.');
    }

    public function cancelUncheckRegistry()
    {
        $this->showUncheckRegistryModal = false;
        $this->registryUnitsToUncheck = [];
        $this->pendingSaveData = [];
        // Revert the checkbox back to checked
        $this->is_registry_department = true;
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $department = Department::findOrFail($this->deleteId);

        // If deleting a registry department, cascade registry removal to its units
        if ($department->is_registry_department) {
            Unit::where('department_id', $department->id)
                ->update(['is_registry_unit' => false]);
        }

        $department->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->toastSuccess('Department Deleted', 'Department has been deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }
}
