<?php

namespace App\Livewire\Layout;

use App\Models\FileMovement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navigation extends Component
{
    public function getPendingCountProperty()
    {
        if (!Auth::check()) {
            return 0;
        }
        return FileMovement::where('intended_receiver_emp_no', Auth::user()->employee_number)
            ->where('movement_status', 'sent')
            ->count();
    }

    public function render()
    {
        return view('livewire.layout.navigation');
    }
}
