<div wire:poll.5s="loadPendingReceipts">
    <!-- Notification Popup -->
    @if($showPopup && !$dismissed && $pendingReceipts->count() > 0)
        <div x-data="{ open: true }"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-4"
             class="fixed bottom-2 sm:bottom-4 left-2 right-2 sm:left-auto sm:right-4 z-50 sm:max-w-md sm:w-full">

            <div class="bg-white rounded-lg shadow-2xl border-2 border-green-500 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <h3 class="text-white font-semibold text-lg">
                            Pending File Receipts
                        </h3>
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $pendingReceipts->count() }}
                        </span>
                    </div>
                    <button wire:click="dismissPopup"
                            class="text-white hover:text-gray-200 focus:outline-none"
                            title="Hide notification">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="max-h-[40vh] sm:max-h-96 overflow-y-auto">
                    @foreach($pendingReceipts as $movement)
                        <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-150">
                            <div class="px-4 py-3">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $movement->file->file_title }}
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1">
                                            File No: {{ $movement->file->new_file_no }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            From: <span class="font-medium">{{ $movement->sender->name }}</span>
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            Sent: {{ $movement->sent_at->diffForHumans() }}
                                        </p>

                                        @if($movement->file->priority === 'urgent' || $movement->file->priority === 'very_urgent')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-2">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ strtoupper(str_replace('_', ' ', $movement->file->priority)) }}
                                            </span>
                                        @endif
                                    </div>

                                    <button wire:click="selectMovement({{ $movement->id }})"
                                            class="ml-3 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                                        Confirm
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200">
                    <a href="{{ route('files.receive') }}"
                       class="text-sm text-green-600 hover:text-green-800 font-medium">
                        View All Pending Receipts →
                    </a>
                    <button wire:click="dismissPopup"
                            class="text-sm text-gray-600 hover:text-gray-800">
                        Hide
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Confirmation Modal -->
    @if($this->selectedMovement)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-2 sm:px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeConfirmation"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full mx-2 sm:mx-0 sm:max-w-lg">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Confirm File Receipt
                                </h3>
                                <div class="mt-4">
                                    <dl class="space-y-2">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">File Title</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $this->selectedMovement->file->file_title }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">File Number</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $this->selectedMovement->file->new_file_no }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">From</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $this->selectedMovement->sender->name }} ({{ $this->selectedMovement->sender->position }})</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Sent At</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $this->selectedMovement->sent_at->format('d M Y, h:i A') }}</dd>
                                        </div>
                                        @if($this->selectedMovement->sender_comments)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Sender Comments</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $this->selectedMovement->sender_comments }}</dd>
                                            </div>
                                        @endif
                                    </dl>

                                    <div class="mt-4">
                                        <label for="receiverComments" class="block text-sm font-medium text-gray-700">
                                            Comments (Optional)
                                        </label>
                                        <textarea wire:model="receiverComments"
                                                  id="receiverComments"
                                                  rows="3"
                                                  class="mt-1 shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                  placeholder="Add any comments about receiving this file..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-col-reverse sm:flex-row-reverse gap-2 sm:gap-0">
                        <button type="button"
                                wire:click="confirmReceipt"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2.5 sm:py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Receipt
                        </button>
                        <button type="button"
                                wire:click="closeConfirmation"
                                class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2.5 sm:py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Hidden button to show popup again (optional) -->
    @if($dismissed && $pendingReceipts->count() > 0)
        <button wire:click="showPopupAgain"
                class="fixed bottom-2 right-2 sm:bottom-4 sm:right-4 z-40 bg-green-600 hover:bg-green-700 text-white rounded-full p-3 shadow-lg transition-all duration-150 hover:scale-110"
                title="Show pending receipts ({{ $pendingReceipts->count() }})">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            @if($pendingReceipts->count() > 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                    {{ $pendingReceipts->count() }}
                </span>
            @endif
        </button>
    @endif
</div>
