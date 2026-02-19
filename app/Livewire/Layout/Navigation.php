<?php

namespace App\Livewire\Layout;

use App\Models\FileMovement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navigation extends Component
{
    public $isAdmin = false;
    public $isRegistryStaff = false;

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->loadMissing(['position', 'departmentRel', 'unitRel.department']);
            $this->isAdmin = $user->isAdmin();
            $this->isRegistryStaff = $user->isRegistryStaff();
        }
    }

    public function getPendingCountProperty()
    {
        if (!Auth::check()) {
            return 0;
        }
        return FileMovement::where('intended_receiver_emp_no', Auth::user()->employee_number)
            ->where('movement_status', 'sent')
            ->count();
    }

    public function getSentPendingCountProperty()
    {
        if (!Auth::check()) {
            return 0;
        }
        return FileMovement::where('sender_emp_no', Auth::user()->employee_number)
            ->where('movement_status', 'sent')
            ->count();
    }

    public function render()
    {
        return view('livewire.layout.navigation');
    }
}
