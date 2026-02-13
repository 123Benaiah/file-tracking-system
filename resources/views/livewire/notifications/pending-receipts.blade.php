<div wire:poll.5s>
    <!-- Notification Popup -->
    @php
        $user = auth()->user();
        $isDismissed = $user ? session('pending_receipts_dismissed_' . $user->employee_number, false) : false;
    @endphp
    @if($this->pendingReceipts->count() > 0 && !$isDismissed)
        <div x-data="{ shown: true }" x-show="shown"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-8"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-8"
             class="fixed inset-x-0 top-0 sm:top-4 z-50 sm:mx-auto sm:max-w-2xl sm:rounded-xl">

            <div class="bg-white shadow-xl sm:shadow-2xl border-2 border-green-500 overflow-hidden mx-2 sm:mx-0">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3 sm:px-5 sm:py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 rounded-full p-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-base sm:text-lg">
                                Pending File Receipts
                            </h3>
                            <p class="text-green-100 text-xs hidden sm:block">Action required - files waiting for you</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $pendingReceipts->count() }}
                        </span>
                        <button wire:click="dismissPopup"
                                class="text-white/80 hover:text-white hover:bg-white/20 rounded-full p-1.5 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="max-h-[60vh] sm:max-h-80 overflow-y-auto">
                    @foreach($pendingReceipts as $movement)
                        <div class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150">
                            <div class="p-4 sm:p-4">
                                <div class="flex flex-col gap-3">
                                    <div>
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm sm:text-base font-bold text-gray-900 truncate">
                                                    {{ $movement->file->file_title }}
                                                </p>
                                                <p class="text-xs font-mono text-gray-500 mt-0.5">{{ $movement->file->new_file_no }}</p>
                                            </div>
                                            @if($movement->file->priority === 'urgent' || $movement->file->priority === 'very_urgent')
                                                <span class="flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">
                                                    {{ strtoupper(str_replace('_', ' ', $movement->file->priority)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="font-medium text-gray-700">{{ $movement->sender->name ?? 'Unknown' }}</span>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $movement->sent_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    @if($movement->sender_comments)
                                        <p class="text-xs text-gray-400 italic bg-gray-50 rounded px-2 py-1">
                                            "{{ $movement->sender_comments }}"
                                        </p>
                                    @endif

                                    <div class="flex gap-2 pt-1">
                                        <a href="{{ route('files.show', $movement->file) }}"
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs sm:text-sm font-medium rounded-lg text-white bg-gray-800 hover:bg-gray-900 transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <button wire:click="quickConfirm({{ $movement->id }})"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs sm:text-sm font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Confirm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="bg-gray-100 px-4 py-3 flex items-center justify-between border-t border-gray-200">
                    <a href="{{ route('files.confirm') }}"
                       class="text-xs sm:text-sm font-medium text-green-700 hover:text-green-800 flex items-center gap-1">
                        View All Receipts
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <button wire:click="dismissPopup"
                            class="text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-800">
                        Hide
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Show Again Button -->
    @php
        $user = auth()->user();
        $isDismissed = $user ? session('pending_receipts_dismissed_' . $user->employee_number, false) : false;
    @endphp
    @if($isDismissed && $pendingReceipts->count() > 0)
        <button wire:click="showPopupAgain"
                class="fixed top-4 right-4 z-40 bg-green-600 hover:bg-green-700 text-white rounded-full p-3 shadow-lg transition-all duration-150 hover:scale-110 hover:shadow-xl"
                title="{{ $pendingReceipts->count() }} pending receipt(s)">
            <div class="relative">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center shadow">
                    {{ $pendingReceipts->count() }}
                </span>
            </div>
        </button>
    @endif
</div>
