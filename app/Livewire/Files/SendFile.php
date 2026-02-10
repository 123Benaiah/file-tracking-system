<?php

namespace App\Livewire\Files;

use App\Models\Department;
use App\Models\Employee;
use App\Models\File;
use App\Models\FileMovement;
use App\Models\Unit;
use App\Traits\WithToast;
use Livewire\Component;

class SendFile extends Component
{
    use WithToast;

    public $file;

    public $intendedReceiverEmpNo;

    public $deliveryMethod = 'hand_carry';

    public $senderComments;

    public $handCarriedBy;

    public $isReturningToRegistry = false;

    public $showRecipientModal = false;

    public $selectedRecipient = null;

    public $recipientSearch = '';

    public $filterByDepartment = '';

    public function mount(File $file)
    {
        $this->file = $file;

        if (! $this->file->canBeSentBy(auth()->user())) {
            abort(403, 'You cannot send this file.');
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
                'position' => $receiver->position?->title,
                'department' => $receiver->department?->name,
                'unit' => $receiver->unit?->name,
                'is_registry' => $receiver->isRegistryStaff(),
            ];
            $this->isReturningToRegistry = $receiver->isRegistryStaff() && ! auth()->user()->isRegistryStaff();
        }

        $this->showRecipientModal = false;
    }

    public function clearRecipient()
    {
        $this->intendedReceiverEmpNo = null;
        $this->selectedRecipient = null;
        $this->isReturningToRegistry = false;
    }

    public function updatedIntendedReceiverEmpNo($value)
    {
        if ($value) {
            $receiver = Employee::find($value);
            $this->isReturningToRegistry = $receiver && $receiver->isRegistryStaff();
        } else {
            $this->isReturningToRegistry = false;
        }
    }

    public function send()
    {
        $this->validate([
            'intendedReceiverEmpNo' => 'required|exists:employees,employee_number',
            'deliveryMethod' => 'required|in:internal_messenger,hand_carry,courier,email',
            'senderComments' => 'nullable|string|max:500',
            'handCarriedBy' => 'nullable|string|max:100',
        ]);

        $receiver = Employee::find($this->intendedReceiverEmpNo);
        $sender = auth()->user();

        $isReturningToRegistry = $receiver->isRegistryStaff() && ! $sender->isRegistryStaff();

        $movement = FileMovement::create([
            'file_id' => $this->file->id,
            'sender_emp_no' => $sender->employee_number,
            'intended_receiver_emp_no' => $this->intendedReceiverEmpNo,
            'delivery_method' => $this->deliveryMethod,
            'sender_comments' => $this->senderComments,
            'hand_carried_by' => $this->handCarriedBy,
            'movement_status' => 'sent',
            'sent_at' => now(),
            'sla_days' => 3,
        ]);

        if ($isReturningToRegistry) {
            $newStatus = 'Completed';
        } else {
            $newStatus = 'in_transit';
        }

        $this->file->update([
            'status' => $newStatus,
        ]);

        \App\Models\AuditLog::create([
            'employee_number' => $sender->employee_number,
            'action' => $isReturningToRegistry ? 'file_returned' : 'file_sent',
            'description' => $isReturningToRegistry
                ? 'Returned file '.$this->file->new_file_no.' to registry ('.$this->intendedReceiverEmpNo.')'
                : 'Sent file '.$this->file->new_file_no.' to '.$this->intendedReceiverEmpNo,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'new_data' => $movement->toArray(),
        ]);

        $this->toastSuccess(
            $isReturningToRegistry ? 'File Returned' : 'File Sent',
            $isReturningToRegistry
                ? 'File '.$this->file->new_file_no.' has been returned to registry.'
                : 'File '.$this->file->new_file_no.' has been sent to '.$receiver->name.'.'
        );

        if ($sender->isRegistryStaff()) {
            return redirect()->route('registry.dashboard');
        } else {
            return redirect()->route('department.dashboard');
        }
    }

    public function render()
    {
        $user = auth()->user();

        $query = Employee::where('is_active', true)
            ->where('employee_number', '!=', $user->employee_number);

        if (! empty($this->filterByDepartment)) {
            $query->where(function ($q) {
                $q->where('department_id', $this->filterByDepartment)
                  ->orWhereHas('unit', function ($q2) {
                      $q2->where('department_id', $this->filterByDepartment);
                  });
            });
        }

        if (! empty($this->recipientSearch)) {
            $searchTerms = explode(' ', trim($this->recipientSearch));
            foreach ($searchTerms as $term) {
                $term = trim($term);
                if (! empty($term)) {
                    $query->where(function ($q) use ($term) {
                        $q->where('name', 'like', "%{$term}%")
                            ->orWhere('employee_number', 'like', "%{$term}%")
                            ->orWhereHas('position', function ($q2) use ($term) {
                                $q2->where('title', 'like', "%{$term}%");
                            })
                            ->orWhereHas('department', function ($q2) use ($term) {
                                $q2->where('name', 'like', "%{$term}%");
                            })
                            ->orWhereHas('unitRel', function ($q2) use ($term) {
                                $q2->where('name', 'like', "%{$term}%");
                            });
                    });
                }
            }
        }

        $receivers = $query->with(['position', 'departmentRel', 'unitRel'])
            ->orderBy('name')
            ->get();

        $registryStaff = $receivers->filter(fn ($e) => $e->isRegistryStaff());
        $otherStaff = $receivers->filter(fn ($e) => ! $e->isRegistryStaff());

        $departments = Department::orderBy('name')->pluck('name', 'id');

        return view('livewire.files.send-file', [
            'receivers' => $receivers,
            'registryStaff' => $registryStaff,
            'otherStaff' => $otherStaff,
            'isUserFromRegistry' => $user->isRegistryStaff(),
            'departments' => $departments,
        ]);
    }
}
