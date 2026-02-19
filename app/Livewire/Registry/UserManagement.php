<?php

namespace App\Livewire\Registry;

use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\File;
use App\Models\FileMovement;
use App\Models\Position;
use App\Models\Unit;
use App\Traits\WithToast;
use Illuminate\Support\Facades\DB;
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

    public $isDeleting = false;

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
            'role' => 'required|in:user',
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

        try {
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

                $oldEmpNo = $this->selectedEmployeeId;
                $newEmpNo = $this->employee_number;

                DB::transaction(function () use ($employee, $data, $oldEmpNo, $newEmpNo) {
                    if ($oldEmpNo !== $newEmpNo) {
                        DB::statement('SET FOREIGN_KEY_CHECKS=0');

                        Employee::where('created_by', $oldEmpNo)->update(['created_by' => $newEmpNo]);
                        File::where('current_holder', $oldEmpNo)->update(['current_holder' => $newEmpNo]);
                        File::where('registered_by', $oldEmpNo)->update(['registered_by' => $newEmpNo]);
                        DB::table('files')->where('merged_by', $oldEmpNo)->update(['merged_by' => $newEmpNo]);
                        FileMovement::where('sender_emp_no', $oldEmpNo)->update(['sender_emp_no' => $newEmpNo]);
                        FileMovement::where('intended_receiver_emp_no', $oldEmpNo)->update(['intended_receiver_emp_no' => $newEmpNo]);
                        FileMovement::where('actual_receiver_emp_no', $oldEmpNo)->update(['actual_receiver_emp_no' => $newEmpNo]);
                        DB::table('file_attachments')->where('uploaded_by', $oldEmpNo)->update(['uploaded_by' => $newEmpNo]);
                        AuditLog::where('employee_number', $oldEmpNo)->update(['employee_number' => $newEmpNo]);

                        DB::statement('SET FOREIGN_KEY_CHECKS=1');
                    }

                    $employee->update($data);
                });

                $this->toastSuccess('Employee Updated', $this->name.' has been updated successfully.');
            } else {
                Employee::create($data);
                $this->toastSuccess('Employee Created', $this->name.' has been added successfully.');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            report($e);
            $this->toastError('Operation Failed', 'Something went wrong. Please try again or contact the administrator.');
        }
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
        $this->isDeleting = true;

        $employees = Employee::whereIn('employee_number', $this->selectedEmployees)->get();

        // Block deletion of registry head if they hold files
        foreach ($employees as $employee) {
            if ($employee->isRegistryHead()) {
                $heldCount = File::where('current_holder', $employee->employee_number)
                    ->whereNotIn('status', ['archived', 'merged'])
                    ->count();

                if ($heldCount > 0) {
                    $this->isDeleting = false;
                    $this->toastError(
                        'Cannot Delete Registry Head',
                        'Registry Head (' . $employee->name . ') currently holds ' . $heldCount . ' file(s). All files must be sent and confirmed before deletion.'
                    );
                    return;
                }
            }
        }

        $registryHead = Employee::where('is_registry_head', true)
            ->where('is_active', true)
            ->first();

        $totalTransferred = 0;

        foreach ($employees as $employee) {
            // Skip registry head file transfer (they're only deleted if they have 0 files)
            if ($employee->isRegistryHead()) {
                Employee::where('created_by', $employee->employee_number)->update(['created_by' => null]);
                AuditLog::where('employee_number', $employee->employee_number)->delete();
                $employee->forceDelete();
                continue;
            }

            $heldFiles = File::where('current_holder', $employee->employee_number)
                ->whereNotIn('status', ['archived', 'merged'])
                ->get();

            if ($heldFiles->count() > 0) {
                if (!$registryHead) {
                    $this->isDeleting = false;
                    $this->toastError('No Registry Head', 'Cannot transfer files — no active Registry Head found.');
                    return;
                }

                foreach ($heldFiles as $file) {
                    FileMovement::where('file_id', $file->id)
                        ->where('movement_status', 'sent')
                        ->update([
                            'movement_status' => 'cancelled',
                            'receiver_comments' => 'Auto-cancelled: employee ' . $employee->name . ' was deleted.',
                        ]);

                    FileMovement::create([
                        'file_id' => $file->id,
                        'sender_emp_no' => $employee->employee_number,
                        'intended_receiver_emp_no' => $registryHead->employee_number,
                        'actual_receiver_emp_no' => $registryHead->employee_number,
                        'delivery_method' => 'internal_messenger',
                        'sender_comments' => 'Auto-transferred: employee ' . $employee->name . ' was deleted.',
                        'movement_status' => 'received',
                        'sent_at' => now(),
                        'received_at' => now(),
                        'sla_days' => 0,
                    ]);

                    $file->update([
                        'status' => 'completed',
                        'current_holder' => $registryHead->employee_number,
                    ]);
                }

                $totalTransferred += $heldFiles->count();

                AuditLog::create([
                    'employee_number' => auth()->user()->employee_number,
                    'action' => 'files_auto_transferred',
                    'description' => 'Auto-transferred ' . $heldFiles->count() . ' file(s) from deleted employee ' . $employee->name . ' (' . $employee->employee_number . ') to Registry Head ' . $registryHead->name,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            Employee::where('created_by', $employee->employee_number)->update(['created_by' => null]);
            AuditLog::where('employee_number', $employee->employee_number)->delete();
            $employee->forceDelete();
        }

        $this->isDeleting = false;
        $this->showDeleteModal = false;
        $this->selectedEmployees = [];
        $this->selectAll = false;

        $message = $employees->count() . ' employee(s) have been deleted successfully.';
        if ($totalTransferred > 0) {
            $message .= ' ' . $totalTransferred . ' file(s) were auto-transferred to the Registry Head.';
        }
        $this->toastSuccess('Employees Deleted', $message);
    }

    public function updatedDepartmentId()
    {
        $this->unit_id = '';
    }

    public function render()
    {
        $employees = $this->getQuery()
            ->with(['position', 'departmentRel', 'unitRel'])
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
