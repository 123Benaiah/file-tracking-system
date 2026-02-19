<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use App\Models\Department;
use App\Models\File;
use App\Models\FileMovement;
use App\Models\AuditLog;
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
    public $gender = '';
    public $role = 'user';
    public $office = '';
    public $position_id = '';
    public $department_id = '';
    public $unit_id = '';
    public $is_active = true;
    public $is_registry_head = false;

    public $originalEmployeeNumber = '';

    protected function rules()
    {
        $rules = [
            'employee_number' => 'required|string|max:50|unique:employees,employee_number',
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:employees,email',
            'gender' => 'nullable|in:male,female',
            'role' => 'required|in:admin,user',
            'office' => 'nullable|string|max:255',
            'position_id' => 'nullable|exists:positions,id',
            'department_id' => 'nullable|exists:departments,id',
            'unit_id' => 'nullable|exists:units,id',
            'is_active' => 'boolean',
            'is_registry_head' => 'boolean',
            'password' => 'required|min:6|confirmed',
        ];

        if ($this->editMode) {
            $rules['employee_number'] = 'required|string|max:50|unique:employees,employee_number,' . $this->originalEmployeeNumber . ',employee_number';
            $rules['email'] = 'required|email|unique:employees,email,' . $this->originalEmployeeNumber . ',employee_number';
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
        $this->reset(['employee_number', 'name', 'gender', 'email', 'password', 'password_confirmation', 'role', 'office', 'position_id', 'department_id', 'unit_id', 'is_active', 'is_registry_head', 'editMode', 'originalEmployeeNumber']);
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->originalEmployeeNumber = $employee->employee_number;
        $this->employee_number = $employee->employee_number;
        $this->name = $employee->name;
        $this->gender = $employee->gender ?? '';
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
            $this->addError('is_registry_head', 'Another active employee ("' . $existingRegistryHead->formal_name . '") is already the Registry Head.');
            return;
        }

        try {
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
                'gender' => $this->gender ?: null,
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
                $employee = Employee::findOrFail($this->originalEmployeeNumber);
                $data['employee_number'] = $this->employee_number;

                $oldEmpNo = $this->originalEmployeeNumber;
                $newEmpNo = $this->employee_number;

                DB::transaction(function () use ($employee, $data, $oldEmpNo, $newEmpNo) {
                    // If employee number changed, update all foreign key references first
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
        } catch (\Exception $e) {
            report($e);
            $this->toastError('Operation Failed', 'Something went wrong. Please try again or contact the administrator.');
        }
    }

    public function confirmDelete($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            if ($employee->employee_number === auth()->user()->employee_number) {
                $this->toastError('Cannot Delete', 'You cannot delete your own account.');
                return;
            }

            // Get all files this employee currently holds
            $heldFiles = File::where('current_holder', $employee->employee_number)
                ->whereNotIn('status', ['archived', 'merged'])
                ->get();

            // If this is the registry head, they must have zero files before deletion
            if ($employee->isRegistryHead() && $heldFiles->count() > 0) {
                $this->toastError(
                    'Cannot Delete Registry Head',
                    'Registry Head currently holds ' . $heldFiles->count() . ' file(s). All files must be sent to other employees and confirmed before deletion.'
                );
                return;
            }

            // For non-registry-head employees, auto-transfer all held files to registry head
            if ($heldFiles->count() > 0) {
                $registryHead = Employee::where('is_registry_head', true)
                    ->where('is_active', true)
                    ->first();

                if (!$registryHead) {
                    $this->toastError('No Registry Head', 'Cannot transfer files — no active Registry Head found in the system.');
                    return;
                }

                foreach ($heldFiles as $file) {
                    FileMovement::where('file_id', $file->id)
                        ->where('movement_status', 'sent')
                        ->update([
                            'movement_status' => 'cancelled',
                            'receiver_comments' => 'Auto-cancelled: employee ' . $employee->name . ' was deleted by admin.',
                        ]);

                    FileMovement::create([
                        'file_id' => $file->id,
                        'sender_emp_no' => $employee->employee_number,
                        'intended_receiver_emp_no' => $registryHead->employee_number,
                        'actual_receiver_emp_no' => $registryHead->employee_number,
                        'delivery_method' => 'internal_messenger',
                        'sender_comments' => 'Auto-transferred: employee ' . $employee->name . ' was deleted by admin.',
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

                AuditLog::create([
                    'employee_number' => auth()->user()->employee_number,
                    'action' => 'files_auto_transferred',
                    'description' => 'Auto-transferred ' . $heldFiles->count() . ' file(s) from deleted employee ' . $employee->name . ' (' . $employee->employee_number . ') to Registry Head ' . $registryHead->name,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }

            // Nullify foreign key references before permanent delete
            $empNo = $employee->employee_number;
            Employee::where('created_by', $empNo)->update(['created_by' => null]);
            AuditLog::where('employee_number', $empNo)->delete();

            $employee->forceDelete();

            $message = 'Employee has been deleted successfully.';
            if ($heldFiles->count() > 0) {
                $message .= ' ' . $heldFiles->count() . ' file(s) were auto-transferred to the Registry Head.';
            }
            $this->toastSuccess('Employee Deleted', $message);
        } catch (\Exception $e) {
            report($e);
            $this->toastError('Delete Failed', 'Something went wrong while deleting the employee. Please try again.');
        }
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

    public function switchToSecurityTab()
    {
        // Validate details before switching
        $detailsRules = [
            'employee_number' => $this->editMode 
                ? 'required|string|max:50|unique:employees,employee_number,' . $this->originalEmployeeNumber . ',employee_number'
                : 'required|string|max:50|unique:employees,employee_number',
            'name' => 'required|string|max:200',
            'email' => $this->editMode 
                ? 'required|email|unique:employees,email,' . $this->originalEmployeeNumber . ',employee_number'
                : 'required|email|unique:employees,email',
            'gender' => 'nullable|in:male,female',
            'role' => 'required|in:admin,user',
            'office' => 'nullable|string|max:255',
            'position_id' => 'nullable|exists:positions,id',
            'department_id' => 'nullable|exists:departments,id',
            'unit_id' => 'nullable|exists:units,id',
            'is_active' => 'boolean',
            'is_registry_head' => 'boolean',
        ];

        $this->validate($detailsRules);

        // Check if another active employee is already registry head
        $existingRegistryHead = Employee::where('is_registry_head', true)
            ->where('is_active', true)
            ->where('employee_number', '!=', $this->employee_number)
            ->first();

        if ($this->is_registry_head && $existingRegistryHead) {
            $this->addError('is_registry_head', 'Another active employee ("' . $existingRegistryHead->formal_name . '") is already the Registry Head.');
            return;
        }

        // Switch to security tab via JS (dispatch on window for Alpine's .window listener)
        $this->js("window.dispatchEvent(new CustomEvent('go-to-security'))");
    }
}
