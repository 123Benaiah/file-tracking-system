<div class="py-4 sm:py-8">
    <!-- Back Button -->
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
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-base sm:text-lg font-semibold text-gray-900">Change File Recipient</h1>
                        <p class="text-xs sm:text-sm text-gray-500">{{ $movement->file->new_file_no }}</p>
                    </div>
                </div>
            </div>

            <!-- File Details Section -->
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $movement->file->file_title ?? $movement->file->subject }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $movement->file->subject }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Sent by: <span class="font-medium text-gray-700">{{ $movement->sender->formal_name ?? 'Unknown' }}</span></p>
            </div>

            <!-- In Transit Notice -->
            <div class="px-4 sm:px-6 py-3 bg-blue-50 border-b border-blue-100">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-700 font-medium">
                        File is in transit. You can change the recipient before it is received.
                    </p>
                </div>
            </div>

            <!-- Form Section -->
            <form wire:submit.prevent="updateRecipient">
                <div class="px-4 sm:px-6 py-4 sm:py-5 space-y-4 sm:space-y-5">

                    <!-- Recipient Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Change Recipient To <span class="text-red-500">*</span>
                        </label>

                        @if(!empty($selectedRecipient) && isset($selectedRecipient['name']))
                            <!-- Selected Recipient Display -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full {{ ($selectedRecipient['is_registry'] ?? false) ? 'bg-green-100' : 'bg-gray-100' }} flex items-center justify-center">
                                        <span class="{{ ($selectedRecipient['is_registry'] ?? false) ? 'text-green-600' : 'text-gray-600' }} font-medium text-sm">
                                            {{ strtoupper(substr($selectedRecipient['name'], 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $selectedRecipient['formal_name'] ?? $selectedRecipient['name'] }}</p>
                                        <p class="text-xs text-gray-500">
                                            <span class="font-medium">ID:</span> {{ $selectedRecipient['employee_number'] }}
                                            @if(($selectedRecipient['position'] ?? 'N/A') !== 'N/A' || ($selectedRecipient['department'] ?? ''))
                                            - {{ $selectedRecipient['position'] ?? 'N/A' }} - {{ $selectedRecipient['department'] }}
                                            @endif
                                        </p>
                                        @if($selectedRecipient['is_registry'] ?? false)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                            Registry
                                        </span>
                                        @endif
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
                                    class="w-full p-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-colors text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">Click to select a recipient</p>
                                </div>
                            </button>
                        @endif
                        @error('intendedReceiverEmpNo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Delivery Method -->
                    <div>
                        <label for="deliveryMethod" class="block text-sm font-medium text-gray-700 mb-2">
                            Delivery Method <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="deliveryMethod" id="deliveryMethod"
                                class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm">
                            <option value="hand_carry">Hand Carry</option>
                            <option value="internal_messenger">Internal Messenger</option>
                            <option value="courier">Courier</option>
                            <option value="email">Email</option>
                        </select>
                        @error('deliveryMethod') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Hand Carried By -->
                    @if($deliveryMethod === 'hand_carry')
                    <div>
                        <label for="handCarriedBy" class="block text-sm font-medium text-gray-700 mb-2">
                            Hand Carried By
                        </label>
                        <input type="text" wire:model="handCarriedBy" id="handCarriedBy"
                               placeholder="Name of person carrying the file"
                               class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm">
                        @error('handCarriedBy') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <!-- Comments -->
                    <div>
                        <label for="senderComments" class="block text-sm font-medium text-gray-700 mb-2">
                            Comments (Optional)
                        </label>
                        <textarea wire:model="senderComments" id="senderComments" rows="3"
                                  placeholder="Add any notes for the recipient..."
                                  class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm"></textarea>
                        @error('senderComments') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gray-50 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                    <button type="button" wire:click="cancelChange"
                            class="w-full sm:w-auto text-center px-4 py-2.5 sm:py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150">
                        Cancel
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 sm:py-2 text-sm font-medium text-white bg-gradient-to-r from-orange-500 to-orange-600 border border-transparent rounded-lg hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 shadow-lg shadow-orange-500/25 disabled:opacity-75 disabled:cursor-not-allowed transition duration-150">
                        <svg wire:loading wire:target="updateRecipient" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="updateRecipient">Update Recipient</span>
                        <span wire:loading wire:target="updateRecipient">Updating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recipient Selection Modal -->
    @if($showRecipientModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:click.self="closeRecipientModal">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-semibold text-gray-900">Select Recipient</h3>
                        <div class="mt-4 space-y-3">
                            <input type="text" wire:model.live.debounce.300ms="recipientSearch"
                                   placeholder="Search by name, employee number, or position..."
                                   class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm">
                            <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                                @if($receivers->count() > 0)
                                <ul class="divide-y divide-gray-100">
                                    @foreach($receivers as $receiver)
                                    <li>
                                        <button type="button" wire:click="selectRecipient('{{ $receiver->employee_number }}')"
                                                class="w-full px-4 py-3 text-left hover:bg-orange-50 transition-colors">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                        <span class="text-gray-600 font-medium text-xs">{{ strtoupper(substr($receiver->name, 0, 2)) }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $receiver->formal_name ?? $receiver->name }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            <span class="font-medium">ID:</span> {{ $receiver->employee_number }}
                                                            @if($receiver->position?->title || $receiver->departmentRel?->name)
                                                            - {{ $receiver->position?->title ?? 'N/A' }} - {{ $receiver->departmentRel?->name ?? $receiver->department }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($receiver->isRegistryStaff())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Registry
                                                </span>
                                                @endif
                                            </div>
                                        </button>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="p-4 text-sm text-gray-500 text-center">No recipients found</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="button" wire:click="closeRecipientModal"
                            class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
