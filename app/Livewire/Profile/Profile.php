<?php

namespace App\Livewire\Profile;

use App\Models\Employee;
use App\Traits\WithToast;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    use WithToast;

    public $employee_number;
    public $name;
    public $formalName;
    public $email;
    public $gender;
    public $unit;
    public $role;
    public $department;
    public $position;
    public $isSaving = false;

    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected $rules = [
        'name' => 'required|string|max:200',
        'email' => 'required|email',
    ];

    protected $passwordRules = [
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ];

    public function mount()
    {
        $user = auth()->user();
        $user->loadMissing(['position', 'departmentRel', 'unitRel.department']);

        $this->employee_number = $user->employee_number;
        $this->name = $user->name;
        $this->formalName = $user->formal_name;
        $this->email = $user->email;
        $this->gender = $user->gender ? ucfirst($user->gender) : 'Not set';
        $this->role = ucfirst($user->role);
        $this->department = $user->departmentRel?->name
            ?? $user->unitRel?->department?->name
            ?? 'N/A';
        $this->unit = $user->unitRel?->name ?? 'N/A';
        $this->position = $user->position?->title ?? 'N/A';
    }

    public function updateProfile()
    {
        $this->validate();

        $user = auth()->user();
        
        // Check if email is being changed and if it already exists
        if ($this->email !== $user->email) {
            $existing = Employee::where('email', $this->email)->where('employee_number', '!=', $user->employee_number)->first();
            if ($existing) {
                $this->toastError('Email Taken', 'This email is already registered by another user.');
                return;
            }
        }

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->toastSuccess('Profile Updated', 'Your profile information has been updated successfully.');
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }

    public function updatePassword()
    {
        $this->validate($this->passwordRules);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        // Update password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->toastSuccess('Password Changed', 'Your password has been updated successfully.');
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }

    public function render()
    {
        return view('livewire.profile.profile');
    }
}
