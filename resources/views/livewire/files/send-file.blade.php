<div class="py-4 sm:py-8">
    <!-- Back Button - Left aligned outside the centered container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-orange-600 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>
    </div>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">


        <!-- Single Card Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- Header Section -->
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base sm:text-lg font-semibold text-gray-900">Send File</h1>
                        <p class="text-xs sm:text-sm text-gray-500">{{ $file->new_file_no }}</p>
                    </div>
                </div>
            </div>

            <!-- File Details Section -->
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $file->file_title ?? $file->subject }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $file->subject }}</p>
                    </div>
                    <div class="flex items-center space-x-2 flex-shrink-0">
                        <span class="px-2 py-1 text-xs font-medium rounded-lg
                            {{ $file->priority == 'urgent' ? 'bg-orange-100 text-orange-700' :
                               ($file->priority == 'very_urgent' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst(str_replace('_', ' ', $file->priority)) }}
                        </span>
                        <span class="px-2 py-1 text-xs font-medium rounded-lg
                            {{ $file->confidentiality == 'secret' ? 'bg-red-100 text-red-700' :
                               ($file->confidentiality == 'confidential' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                            {{ ucfirst($file->confidentiality) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Return to Registry Notice -->
            @if($isReturningToRegistry)
                <div class="px-6 py-3 bg-green-50 border-b border-green-100">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-green-700 font-medium">
                            This file will be marked as <span class="font-bold">Completed</span> when returned to Registry
                        </p>
                    </div>
                </div>
            @endif

            <!-- Form Section -->
            <form wire:submit.prevent="send">
                <div class="px-4 sm:px-6 py-4 sm:py-5 space-y-4 sm:space-y-5">

                    <!-- Recipient Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Recipient <span class="text-red-500">*</span>
                        </label>

                        @if($selectedRecipient)
                            <!-- Selected Recipient Display -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full {{ $selectedRecipient['is_registry'] ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center">
                                        <span class="{{ $selectedRecipient['is_registry'] ? 'text-green-600' : 'text-gray-600' }} font-medium text-sm">
                                            {{ strtoupper(substr($selectedRecipient['name'], 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $selectedRecipient['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $selectedRecipient['position'] }} - {{ $selectedRecipient['department'] }}</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="clearRecipient" class="text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <!-- Select Recipient Button -->
                            <button type="button" wire:click="openRecipientModal"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm text-gray-500 hover:border-green-500 hover:bg-green-50 transition-colors">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>Click to select recipient...</span>
                                </span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        @endif

                        @error('intendedReceiverEmpNo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Delivery Method - Static Hand Carry -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Delivery Method
                        </label>
                        <div class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700">
                            Hand Carry
                        </div>
                        <input type="hidden" wire:model="deliveryMethod" value="hand_carry">
                    </div>

                    <!-- Hand Carried By -->
                    <div>
                        <label for="handCarriedBy" class="block text-sm font-medium text-gray-700 mb-2">
                            Hand Carried By <span class="text-gray-400 font-normal">(if applicable)</span>
                        </label>
                        <input type="text" wire:model="handCarriedBy" id="handCarriedBy"
                               placeholder="Name of person carrying the file"
                               class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors">
                        @error('handCarriedBy')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comments -->
                    <div>
                        <label for="senderComments" class="block text-sm font-medium text-gray-700 mb-2">
                            Comments / Instructions <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea wire:model="senderComments" id="senderComments" rows="3"
                                  placeholder="Any special instructions or comments..."
                                  class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors resize-none"></textarea>
                        @error('senderComments')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Actions Section -->
                <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                    <a href="{{ $isUserFromRegistry ? route('registry.dashboard') : route('department.dashboard') }}"
                       class="w-full sm:w-auto text-center px-4 py-2.5 sm:py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 sm:py-2 text-sm font-medium text-white rounded-lg transition-colors bg-orange-500 hover:bg-orange-600 disabled:opacity-75 disabled:cursor-not-allowed">
                        <svg wire:loading wire:target="send" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="send">{{ $isReturningToRegistry ? 'Return to Registry' : 'Send File' }}</span>
                        <span wire:loading wire:target="send">Sending...</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Recipient Selection Modal -->
    @if($showRecipientModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
         x-data="{ init() { this.$nextTick(() => { if (this.$refs.searchInput) this.$refs.searchInput.focus(); }); } }"
         x-init="init()">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeRecipientModal"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-2 sm:p-4">
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden" @click.stop>
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Select Recipient</h3>
                        <button type="button" wire:click="closeRecipientModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Search Input - Server Side -->
                    <div class="mt-4 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text"
                               x-ref="searchInput"
                               wire:model.live.debounce.300ms="recipientSearch"
                               placeholder="Search by name, position, or department..."
                               class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors">
                        @if($recipientSearch)
                        <button type="button"
                                wire:click="$set('recipientSearch', '')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        @endif
                        <!-- Loading indicator -->
                        <div wire:loading wire:target="recipientSearch" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Employee List -->
                <div class="max-h-[50vh] sm:max-h-96 overflow-y-auto" id="employee-list">
                    @if($receivers->count() === 0)
                        <div class="px-6 py-12 text-center">
                            @if($recipientSearch)
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No employees found matching "{{ $recipientSearch }}"</p>
                                <button type="button" wire:click="$set('recipientSearch', '')" class="mt-2 text-sm text-green-600 hover:text-green-700 font-medium">
                                    Clear search
                                </button>
                            @else
                                <p class="text-sm text-gray-500">No employees available to send to.</p>
                            @endif
                        </div>
                    @else
                        <!-- Show all employees grouped by department -->
                        @php
                            $groupedReceivers = $receivers->groupBy(function($employee) {
                                return $employee->isRegistryStaff() ? 'Registry' : $employee->department;
                            });
                        @endphp

                        @foreach($groupedReceivers as $department => $employees)
                            <div class="employee-group">
                                <div class="px-4 py-2 {{ $department === 'Registry' ? 'bg-green-50 border-b border-green-100' : 'bg-gray-100 border-b border-gray-200' }} sticky top-0 z-10">
                                    <p class="text-xs font-semibold {{ $department === 'Registry' ? 'text-green-700' : 'text-gray-600' }} uppercase tracking-wider">
                                        {{ $department }}
                                        @if($department === 'Registry' && !$isUserFromRegistry)
                                            <span class="font-normal">(Return File)</span>
                                        @endif
                                    </p>
                                </div>
                                @foreach($employees as $receiver)
                                    <button type="button"
                                            wire:click="selectRecipient('{{ $receiver->employee_number }}')"
                                            class="employee-item w-full px-4 py-3 flex items-center space-x-3 hover:bg-{{ $department === 'Registry' ? 'green' : 'gray' }}-50 transition-colors border-b border-gray-100">
                                        <div class="w-10 h-10 rounded-full {{ $department === 'Registry' ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                                            <span class="{{ $department === 'Registry' ? 'text-green-600' : 'text-gray-600' }} font-medium text-sm">{{ strtoupper(substr($receiver->name, 0, 2)) }}</span>
                                        </div>
                                        <div class="flex-1 text-left">
                                            <p class="text-sm font-medium text-gray-900">{{ $receiver->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $receiver->position }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">
                            {{ $receivers->count() }} employee(s) {{ $recipientSearch ? 'found' : 'available' }}
                        </p>
                        <button type="button" wire:click="closeRecipientModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
