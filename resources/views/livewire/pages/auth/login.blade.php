<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full max-w-[260px] sm:max-w-xs mx-auto">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Employee Number -->
        <div>
            <x-input-label for="employee_number" :value="__('Employee Number')" class="text-gray-700 font-semibold text-sm" />
            <x-text-input wire:model="form.employee_number" id="employee_number" class="block mt-1 w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg text-sm py-2.5 sm:py-1.5" type="text" name="employee_number" required autofocus autocomplete="username" placeholder="e.g., REGHEAD001" />
            <x-input-error :messages="$errors->get('form.employee_number')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-3 sm:mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold text-sm" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg text-sm py-2.5 sm:py-1.5"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-3 sm:mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between mt-5 sm:mt-6 gap-3">
            @if (Route::has('password.request'))
                <a class="text-sm text-orange-600 hover:text-orange-800 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot password?') }}
                </a>
            @endif

            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75 disabled:cursor-not-allowed">
                <svg wire:loading wire:target="login" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="login">{{ __('Log in') }}</span>
                <span wire:loading wire:target="login">{{ __('Logging in...') }}</span>
            </button>
        </div>
    </form>
</div>
