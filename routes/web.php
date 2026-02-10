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
use App\Livewire\Registry\UserManagement;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isRegistryStaff()) {
            return redirect()->route('registry.dashboard');
        } else {
            return redirect()->route('department.dashboard');
        }
    })->name('dashboard');

    // ==== Registry Head Exclusive Routes ====
    Route::middleware(['registry.head'])->group(function () {
        Route::get('/registry/users', UserManagement::class)->name('registry.users');
        Route::get('/files/create', CreateFile::class)->name('files.create');
        Route::get('/files/{file}/edit', EditFile::class)->name('files.edit');
        Route::get('/files/{file}/movements', ManageMovements::class)->name('files.movements');
    });

    // ==== Registry Staff Routes ====
    Route::middleware(['registry.staff'])->group(function () {
        Route::get('/registry', RegistryDashboard::class)->name('registry.dashboard');
    });

    // ==== Send File - Available to all authenticated users ====
    Route::get('/files/{file}/send', SendFile::class)->name('files.send');

    // ==== Department Routes ====
    Route::middleware(['department.access'])->group(function () {
        Route::get('/department', DepartmentDashboard::class)->name('department.dashboard');
        Route::get('/files/receive', ReceiveFiles::class)->name('files.receive');
    });

    // ==== Common Routes for All Users ====
    Route::get('/files/track', TrackFile::class)->name('files.track');
    Route::get('/files/{file}', function (\App\Models\File $file) {
        return view('files.show', compact('file'));
    })->name('files.show');
});
