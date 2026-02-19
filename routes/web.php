<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\RegistryDashboard;
use App\Livewire\Dashboard\DepartmentDashboard;
use App\Livewire\Files\CreateFile;
use App\Livewire\Files\SendFile;
use App\Livewire\Files\ReceiveFiles;
use App\Livewire\Files\TrackFile;
use App\Livewire\Files\EditFile;
use App\Livewire\Files\ManageMovements;
use App\Livewire\Files\ConfirmFiles;
use App\Livewire\Files\MergeFiles;
use App\Livewire\Registry\UserManagement;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\EmployeeManagement;
use App\Livewire\Admin\DepartmentManagement;
use App\Livewire\Admin\UnitManagement;
use App\Livewire\Admin\PositionManagement;
use App\Livewire\Admin\DepartmentHeadManagement;
use App\Livewire\Admin\UnitHeadManagement;
use App\Livewire\Profile\Profile;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isRegistryHead()) {
            return redirect()->route('registry.dashboard');
        } elseif ($user->isRegistryStaff()) {
            return redirect()->route('registry.dashboard');
        } else {
            return redirect()->route('department.dashboard');
        }
    })->name('dashboard');

    Route::get('/profile', Profile::class)->name('profile');

    // Admin-only routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/employees', EmployeeManagement::class)->name('employees');
        Route::get('/departments', DepartmentManagement::class)->name('departments');
        Route::get('/units', UnitManagement::class)->name('units');
        Route::get('/positions', PositionManagement::class)->name('positions');
        Route::get('/department-heads', DepartmentHeadManagement::class)->name('department-heads');
        Route::get('/unit-heads', UnitHeadManagement::class)->name('unit-heads');
    });

    // Registry Head-only routes
    Route::middleware(['registry.head'])->group(function () {
        Route::get('/registry/users', UserManagement::class)->name('registry.users');
        Route::get('/files/create', CreateFile::class)->name('files.create');
        Route::get('/files/merge', MergeFiles::class)->name('files.merge');
        Route::get('/files/{file}/edit', EditFile::class)->name('files.edit');
        Route::get('/files/{file}/movements', ManageMovements::class)->name('files.movements');
    });

    // Registry Staff routes (registry dashboard)
    Route::middleware(['registry.staff'])->group(function () {
        Route::get('/registry', RegistryDashboard::class)->name('registry.dashboard');
    });

    // Department dashboard (department users only — not admin, not registry head)
    Route::middleware(['department.access'])->group(function () {
        Route::get('/department', DepartmentDashboard::class)->name('department.dashboard');
    });

    // File operations for all non-admin users (registry head, registry staff, department users)
    Route::middleware(['non.admin'])->group(function () {
        Route::get('/files/receive', ReceiveFiles::class)->name('files.receive');
        Route::get('/files/{file}/send', SendFile::class)->name('files.send');
        Route::get('/files/confirm', ConfirmFiles::class)->name('files.confirm');
        Route::get('/files/sent-pending', \App\Livewire\Files\SentPendingFiles::class)->name('files.sent-pending');
        Route::get('/files/movement/{movementId}/change-recipient', \App\Livewire\Files\ChangeRecipient::class)->name('files.change-recipient');
    });

    // Read-only routes accessible to all authenticated users
    Route::get('/files/track', TrackFile::class)->name('files.track');
    Route::get('/files/{file}', function (\App\Models\File $file) {
        return view('files.show', compact('file'));
    })->name('files.show');
});
