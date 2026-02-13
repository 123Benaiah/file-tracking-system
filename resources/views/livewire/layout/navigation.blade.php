<?php

use App\Livewire\Actions\Logout;
use App\Models\FileMovement;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    protected $listeners = ['receipt-confirmed' => '$refresh', 'refreshNavigation' => '$refresh', 'fileReceived' => '$refresh'];

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
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
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" wire:navigate>
                            {{ __('Admin Panel') }}
                        </x-nav-link>
                    @elseif(auth()->user()->isRegistryStaff())
                        <x-nav-link :href="route('registry.dashboard')" :active="request()->routeIs('registry.*')" wire:navigate>
                            {{ __('Registry Dashboard') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('department.dashboard')" :active="request()->routeIs('department.*')" wire:navigate>
                            {{ __('Department Dashboard') }}
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('files.track')" :active="request()->routeIs('files.track')" wire:navigate>
                        {{ __('Track File') }}
                    </x-nav-link>

                    @if(!auth()->user()->isAdmin())
                        <x-nav-link :href="route('files.receive')" :active="request()->routeIs('files.receive')"
                            wire:navigate>
                            {{ __('Received Files') }}
                        </x-nav-link>
                        <x-nav-link :href="route('files.confirm')" :active="request()->routeIs('files.confirm')"
                            wire:navigate>
                            {{ __('Confirm Files') }}
                            @if($this->pendingCount > 0)
                                <span
                                    class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-600">
                                    {{ $this->pendingCount }}
                                </span>
                            @endif
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(!auth()->user()->isAdmin())
                    <a href="{{ route('files.confirm') }}" class="relative mr-4 p-2 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        @if($this->pendingCount > 0)
                            <span
                                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-orange-500 text-white">
                                {{ $this->pendingCount }}
                            </span>
                        @endif
                    </a>
                @endif
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:text-gray-900 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/50 transition ease-in-out duration-150">
                            <div>
                                @php
                                    $nameParts = explode(' ', Auth::user()->name);
                                    $initials = '';
                                    foreach ($nameParts as $part) {
                                        $initials .= strtoupper(substr($part, 0, 1));
                                    }
                                @endphp
                                {{ $initials }}
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <a href="{{ route('profile') }}" class="block w-full px-4 py-2 text-left text-sm text-green-700 hover:bg-green-50 font-medium">
                            {{ __('My Profile') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 font-medium">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                    wire:navigate>
                    {{ __('Admin Panel') }}
                </x-responsive-nav-link>
            @elseif(auth()->user()->isRegistryStaff())
                <x-responsive-nav-link :href="route('registry.dashboard')" :active="request()->routeIs('registry.*')"
                    wire:navigate>
                    {{ __('Registry Dashboard') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('department.dashboard')" :active="request()->routeIs('department.*')"
                    wire:navigate>
                    {{ __('Department Dashboard') }}
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('files.track')" :active="request()->routeIs('files.track')"
                wire:navigate>
                {{ __('Track File') }}
            </x-responsive-nav-link>

            @if(!auth()->user()->isAdmin())
                <x-responsive-nav-link :href="route('files.receive')" :active="request()->routeIs('files.receive')"
                    wire:navigate>
                    {{ __('Received Files') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('files.confirm')" :active="request()->routeIs('files.confirm')"
                    wire:navigate>
                    {{ __('Confirm Files') }}
                    @if($this->pendingCount > 0)
                        <span
                            class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-600">
                            {{ $this->pendingCount }}
                        </span>
                    @endif
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-green-700 hover:bg-green-50 font-medium">
                    {{ __('My Profile') }}
                </a>
                <form method="POST" action="{{ route('logout') }}" x-data="{ loggingOut: false }" @submit="loggingOut = true">
                    @csrf
                    <button type="submit" :disabled="loggingOut" class="flex w-full items-center px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 font-medium disabled:opacity-75">
                        <svg x-show="loggingOut" x-cloak class="animate-spin h-4 w-4 mr-2 text-red-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-show="!loggingOut">{{ __('Log Out') }}</span>
                        <span x-show="loggingOut" x-cloak>{{ __('Logging out...') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
