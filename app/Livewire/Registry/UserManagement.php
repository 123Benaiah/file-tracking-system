<?php

namespace App\Livewire\Registry;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Unit;
use App\Traits\WithToast;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination, WithToast;

    public $search = '';

    public $departmentFilter = '';

    public $roleFilter = '';

    public $showModal = false;

    public $editMode = false;

    public $perPage = 10;

    public $selectedEmployees = [];

    public $showDeleteModal = false;

    public $selectAll = false;

    public function getSelectedCountProperty()
    {
        return count($this->selectedEmployees);
    }

    public $employee_number = '';

    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $position_id = '';

    public $department_id = '';

    public $unit_id = '';

    public $office = '';

    public $role = 'user';

    public $is_active = true;

    public $selectedEmployeeId = null;

    public $employment_type = 'permanent';

    protected $queryString = [
        'search' => ['except' => ''],
        'departmentFilter' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    protected function rules()
    {
        return [
            'employee_number' => $this->editMode
                ? 'required|string|max:50|unique:employees,employee_number,'.$this->selectedEmployeeId.',employee_number'
                : 'required|string|max:50|unique:employees,employee_number',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:employees,email,'.($this->selectedEmployeeId ?? 'NULL').',employee_number',
            'password' => $this->editMode ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'unit_id' => 'nullable|exists:units,id',
            'office' => 'nullable|string|max:255',
            'role' => 'required|in:registry_head,registry_clerk,department_head,user',
            'is_active' => 'boolean',
            'employment_type' => 'required|in:permanent,contract,temporary,intern',
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($employeeNumber)
    {
        $employee = Employee::where('employee_number', $employeeNumber)->firstOrFail();

        $this->editMode = true;
        $this->selectedEmployeeId = $employee->employee_number;
        $this->employee_number = $employee->employee_number;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->position_id = $employee->position_id;
        $this->department_id = $employee->department_id ?? '';
        $this->unit_id = $employee->unit_id ?? '';
        $this->office = $employee->office ?? '';
        $this->role = $employee->role;
        $this->is_active = $employee->is_active;
        $this->employment_type = $employee->position?->employment_type ?? 'permanent';

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'employee_number' => $this->employee_number,
            'name' => $this->name,
            'email' => $this->email,
            'position_id' => $this->position_id,
            'department_id' => $this->department_id,
            'unit_id' => $this->unit_id ?: null,
            'office' => $this->office,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'created_by' => auth()->user()->employee_number,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editMode) {
            $employee = Employee::where('employee_number', $this->selectedEmployeeId)->firstOrFail();
            $employee->update($data);
            $this->toastSuccess('Employee Updated', $this->name.' has been updated successfully.');
        } else {
            Employee::create($data);
            $this->toastSuccess('Employee Created', $this->name.' has been added successfully.');
        }

        $this->closeModal();
    }

    public function toggleActive($employeeNumber)
    {
        $employee = Employee::where('employee_number', $employeeNumber)->firstOrFail();
        $employee->update(['is_active' => ! $employee->is_active]);

        $status = $employee->is_active ? 'activated' : 'deactivated';
        $this->toastSuccess('Status Changed', $employee->name.' has been '.$status.'.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'employee_number', 'name', 'email', 'password', 'password_confirmation',
            'position_id', 'department_id', 'unit_id', 'office', 'role', 'is_active',
            'employment_type', 'selectedEmployeeId', 'editMode',
        ]);
        $this->is_active = true;
        $this->role = 'user';
        $this->employment_type = 'permanent';
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedEmployees = $this->getQuery()->pluck('employee_number')->toArray();
        } else {
            $this->selectedEmployees = [];
        }
    }

    public function updatedSelectedEmployees()
    {
        $this->selectAll = false;
    }

    private function getQuery()
    {
        return Employee::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('employee_number', 'like', '%'.$this->search.'%')
                        ->orWhere('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhereHas('position', function ($q2) {
                            $q2->where('title', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->departmentFilter, function ($query) {
                $query->where(function ($q) {
                    $q->where('department_id', $this->departmentFilter)
                      ->orWhereHas('unit', function ($q2) {
                          $q2->where('department_id', $this->departmentFilter);
                      });
                });
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            });
    }

    public function confirmDeleteSelected()
    {
        if (empty($this->selectedEmployees)) {
            $this->toastError('No Selection', 'Please select at least one employee to delete.');

            return;
        }
        $this->showDeleteModal = true;
    }

    public function deleteSelected()
    {
        $count = Employee::whereIn('employee_number', $this->selectedEmployees)->count();
        Employee::whereIn('employee_number', $this->selectedEmployees)->delete();

        $this->toastSuccess('Employees Deleted', "$count employee(s) have been deleted successfully.");
        $this->showDeleteModal = false;
        $this->selectedEmployees = [];
        $this->selectAll = false;
    }

    public function updatedDepartmentId()
    {
        $this->unit_id = '';
    }

    public function render()
    {
        $employees = $this->getQuery()
            ->with(['position', 'department', 'unit'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $departments = Department::orderBy('name')->pluck('name', 'id');
        $positions = Position::orderBy('title')->pluck('title', 'id');
        $units = collect();

        if ($this->department_id) {
            $units = Unit::where('department_id', $this->department_id)
                ->orderBy('name')
                ->pluck('name', 'id');
        }

        return view('livewire.registry.user-management', [
            'employees' => $employees,
            'departments' => $departments,
            'positions' => $positions,
            'units' => $units,
        ]);
    }
}
