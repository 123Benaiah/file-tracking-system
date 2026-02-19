<?php

namespace App\Livewire\Files;

use App\Models\Department;
use App\Models\Employee;
use App\Models\FileMovement;
use App\Traits\WithToast;
use Livewire\Component;

class ChangeRecipient extends Component
{
    use WithToast;

    public $movement;
    public $movementId;

    public $intendedReceiverEmpNo;
    public $deliveryMethod = 'hand_carry';
    public $senderComments = '';
    public $handCarriedBy = '';

    public $showRecipientModal = false;
    public $selectedRecipient = [];
    public $recipientSearch = '';
    public $filterByDepartment = '';

    public function mount($movementId)
    {
        $this->movementId = $movementId;
        $this->movement = FileMovement::with(['file', 'intendedReceiver', 'sender'])->findOrFail($movementId);

        if ($this->movement->movement_status !== 'sent') {
            abort(403, 'This file has already been received and cannot have its recipient changed.');
        }

        $currentUser = auth()->user();
        if ($this->movement->sender_emp_no !== $currentUser->employee_number && !$currentUser->isRegistryStaff()) {
            abort(403, 'You are not authorized to change the recipient of this file.');
        }

        $this->intendedReceiverEmpNo = $this->movement->intended_receiver_emp_no;
        $this->deliveryMethod = $this->movement->delivery_method;
        $this->senderComments = $this->movement->sender_comments;
        $this->handCarriedBy = $this->movement->hand_carried_by ?: $currentUser->name;

        if ($this->intendedReceiverEmpNo) {
            $receiver = Employee::find($this->intendedReceiverEmpNo);
            if ($receiver) {
                $this->selectedRecipient = [
                    'employee_number' => $receiver->employee_number,
                    'name' => $receiver->name,
                    'formal_name' => $receiver->formal_name,
                    'position' => $receiver->position?->title ?? 'N/A',
                    'department' => $receiver->departmentRel?->name ?? $receiver->department ?? 'N/A',
                    'unit' => $receiver->unitRel?->name ?? $receiver->unit ?? 'N/A',
                    'is_registry' => $receiver->isRegistryStaff(),
                ];
            } else {
                $this->selectedRecipient = [];
            }
        } else {
            $this->selectedRecipient = [];
        }
    }

    public function openRecipientModal()
    {
        $this->recipientSearch = '';
        $this->filterByDepartment = '';
        $this->showRecipientModal = true;
    }

    public function closeRecipientModal()
    {
        $this->showRecipientModal = false;
        $this->recipientSearch = '';
        $this->filterByDepartment = '';
    }

    public function selectRecipient($employeeNumber)
    {
        $this->intendedReceiverEmpNo = $employeeNumber;
        $receiver = Employee::find($employeeNumber);

        if ($receiver) {
            $this->selectedRecipient = [
                'employee_number' => $receiver->employee_number,
                'name' => $receiver->name,
                'formal_name' => $receiver->formal_name,
                'position' => $receiver->position?->title,
                'department' => $receiver->departmentRel?->name ?? $receiver->department,
                'unit' => $receiver->unitRel?->name ?? $receiver->unit,
                'is_registry' => $receiver->isRegistryStaff(),
            ];
        } else {
            $this->selectedRecipient = [];
        }

        $this->showRecipientModal = false;
    }

    public function clearRecipient()
    {
        $this->intendedReceiverEmpNo = '';
        $this->selectedRecipient = [];
    }

    public function updateRecipient()
    {
        $this->validate([
            'intendedReceiverEmpNo' => 'required|exists:employees,employee_number',
            'deliveryMethod' => 'required|in:internal_messenger,hand_carry,courier,email',
            'senderComments' => 'nullable|string|max:500',
            'handCarriedBy' => 'nullable|string|max:100',
        ]);

        $oldReceiver = $this->movement->intendedReceiver?->formal_name ?? $this->movement->intended_receiver_emp_no;
        $newReceiver = Employee::find($this->intendedReceiverEmpNo);

        $this->movement->update([
            'intended_receiver_emp_no' => $this->intendedReceiverEmpNo,
            'delivery_method' => $this->deliveryMethod,
            'sender_comments' => $this->senderComments,
            'hand_carried_by' => $this->handCarriedBy,
        ]);

        \App\Models\AuditLog::create([
            'employee_number' => auth()->user()->employee_number,
            'action' => 'recipient_changed',
            'description' => 'Changed recipient of file ' . $this->movement->file->new_file_no . ' from ' . $oldReceiver . ' to ' . $newReceiver->formal_name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'new_data' => $this->movement->toArray(),
        ]);

        $this->toastSuccess(
            'Recipient Updated',
            'File ' . $this->movement->file->new_file_no . ' will now be sent to ' . $newReceiver->formal_name . '.'
        );

        return redirect()->route('files.sent-pending');
    }

    public function cancelChange()
    {
        return redirect()->route('files.sent-pending');
    }

    public function render()
    {
        $user = auth()->user();

        $query = Employee::where('is_active', true)
            ->where('employee_number', '!=', $user->employee_number)
            ->where('role', '!=', 'admin');

        if (! empty($this->filterByDepartment)) {
            $query->where(function ($q) {
                $q->where('department_id', $this->filterByDepartment)
                  ->orWhereHas('unit', function ($q2) {
                      $q2->where('department_id', $this->filterByDepartment);
                  });
            });
        }

        if (! empty($this->recipientSearch)) {
            $search = '%' . trim($this->recipientSearch) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('employee_number', 'like', $search)
                    ->orWhereHas('position', function ($q2) use ($search) {
                        $q2->where('title', 'like', $search);
                    })
                    ->orWhereHas('departmentRel', function ($q2) use ($search) {
                        $q2->where('name', 'like', $search);
                    })
                    ->orWhereHas('unitRel', function ($q2) use ($search) {
                        $q2->where('name', 'like', $search);
                    });
            });
        }

        $receivers = $query->with(['position', 'departmentRel', 'unitRel'])
            ->orderBy('name')
            ->get();

        $departments = Department::orderBy('name')->pluck('name', 'id');

        return view('livewire.files.change-recipient', [
            'receivers' => $receivers,
            'departments' => $departments,
        ]);
    }
}
