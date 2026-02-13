<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\Unit;
use App\Traits\WithToast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeManagement extends Component
{
    use WithPagination, WithToast;

    public $search = '';
    public $roleFilter = '';
    public $departmentFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

    public $showModal = false;
    public $editMode = false;

    public $employee_number = '';
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'user';
    public $office = '';
    public $position_id = '';
    public $department_id = '';
    public $unit_id = '';
    public $is_active = true;
    public $is_registry_head = false;

    protected $rules = [
        'employee_number' => 'required|string|max:50|unique:employees,employee_number',
        'name' => 'required|string|max:200',
        'email' => 'required|email|unique:employees,email',
        'password' => 'required|min:6|confirmed',
        'role' => 'required|in:admin,user',
        'office' => 'nullable|string|max:255',
        'position_id' => 'nullable|exists:positions,id',
        'department_id' => 'nullable|exists:departments,id',
        'unit_id' => 'nullable|exists:units,id',
        'is_active' => 'boolean',
        'is_registry_head' => 'boolean',
    ];

    protected function rules()
    {
        $rules = $this->rules;
        if ($this->editMode) {
            $rules['employee_number'] = 'required|string|max:50';
            $rules['email'] = 'required|email';
            $rules['password'] = 'nullable|min:6|confirmed';
        }
        return $rules;
    }

    public function render()
    {
        $currentUser = auth()->user();

        $employees = Employee::with(['position', 'departmentRel', 'unitRel.department'])
            ->where('employee_number', '!=', $currentUser->employee_number)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('employee_number', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->when($this->departmentFilter, function ($query) {
                $query->where('department_id', $this->departmentFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        $departmentsList = Department::orderBy('name')->pluck('name', 'id');
        $departments = Department::orderBy('name')->pluck('name', 'id');
        $positions = Position::orderBy('title')->pluck('title', 'id');

        // Filter units by selected department (only show units for departments that have units)
        $units = collect();
        if ($this->department_id) {
            $dept = Department::find($this->department_id);
            if ($dept && $dept->has_units) {
                $units = Unit::where('department_id', $this->department_id)
                    ->orderBy('name')
                    ->pluck('name', 'id');
            }
        }

        // Get existing active registry head (excluding current editing employee)
        $existingRegistryHead = Employee::where('is_registry_head', true)
            ->where('is_active', true)
            ->where('employee_number', '!=', $this->employee_number)
            ->first();

        return view('livewire.admin.employee-management', [
            'employees' => $employees,
            'departments' => $departments,
            'departmentsList' => $departmentsList,
            'positions' => $positions,
            'units' => $units,
            'existingRegistryHead' => $existingRegistryHead,
        ]);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->reset(['employee_number', 'name', 'email', 'password', 'password_confirmation', 'role', 'office', 'position_id', 'department_id', 'unit_id', 'is_active', 'is_registry_head', 'editMode']);
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->employee_number = $employee->employee_number;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = $employee->role;
        $this->office = $employee->office ?? '';
        $this->position_id = $employee->position_id;
        $this->department_id = $employee->department_id;
        $this->unit_id = $employee->unit_id;
        $this->is_active = $employee->is_active;
        $this->is_registry_head = $employee->is_registry_head;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        // Check if another active employee is already registry head
        $existingRegistryHead = Employee::where('is_registry_head', true)
            ->where('is_active', true)
            ->where('employee_number', '!=', $this->employee_number)
            ->first();

        if ($this->is_registry_head && $existingRegistryHead) {
            $this->addError('is_registry_head', 'Another active employee ("' . $existingRegistryHead->name . '") is already the Registry Head.');
            return;
        }

        // Auto-determine is_registry_staff from unit/department assignment
        $isRegistryStaff = false;
        if ($this->unit_id) {
            $unit = Unit::find($this->unit_id);
            if ($unit && $unit->is_registry_unit) {
                $isRegistryStaff = true;
            }
        }
        if (!$isRegistryStaff && $this->department_id) {
            $dept = Department::find($this->department_id);
            if ($dept && $dept->is_registry_department) {
                $isRegistryStaff = true;
            }
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'office' => $this->office ?: null,
            'position_id' => $this->position_id ?: null,
            'department_id' => $this->department_id ?: null,
            'unit_id' => $this->unit_id ?: null,
            'is_active' => $this->is_active,
            'is_registry_head' => $this->is_registry_head,
            'is_registry_staff' => $isRegistryStaff,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editMode) {
            $employee = Employee::findOrFail($this->employee_number);
            $employee->update($data);
            $employee->refresh();
            $this->dispatch('$refresh');
            $this->toastSuccess('Employee Updated', 'Employee has been updated successfully.');
        } else {
            $data['employee_number'] = $this->employee_number;
            $data['password'] = Hash::make($this->password);
            Employee::create($data);
            $this->dispatch('$refresh');
            $this->toastSuccess('Employee Created', 'New employee has been created successfully.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->employee_number === auth()->user()->employee_number) {
            $this->toastError('Cannot Delete', 'You cannot delete your own account.');
            return;
        }
        $employee->delete();
        $this->toastSuccess('Employee Deleted', 'Employee has been deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $employee = Employee::findOrFail($id);
        if ($employee->employee_number === auth()->user()->employee_number) {
            $this->toastError('Cannot Change', 'You cannot deactivate your own account.');
            return;
        }
        $employee->update(['is_active' => !$employee->is_active]);
        $this->toastSuccess('Status Changed', 'Employee status has been updated.');
    }

    public function updatedDepartmentId()
    {
        // Clear unit selection when department changes (unit may not belong to new dept)
        $this->unit_id = '';
    }
}
