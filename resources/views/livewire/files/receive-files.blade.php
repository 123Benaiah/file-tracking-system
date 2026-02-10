<div class="py-4 sm:py-8" wire:poll.5s>
    <!-- Back Button - Left aligned -->
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
            <div class="p-4 sm:p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Receive Files</h2>
                    <p class="text-gray-500 mt-1 text-sm sm:text-base">Confirm receipt of files sent to you</p>
                </div>
                <div class="hidden sm:block">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-green-700">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-red-700">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Search/Filter Section -->
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-5 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:items-center">
                <div class="flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by file number or subject..."
                           class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 text-sm sm:text-base">
                </div>
                <div class="flex items-center gap-2">
                    <label for="perPage" class="text-xs sm:text-sm text-gray-600 whitespace-nowrap">Per page:</label>
                    <select wire:model.live="perPage" id="perPage"
                            class="bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 text-xs sm:text-sm py-2">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Pending Receipts -->
        <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Pending Receipts</h3>
                    <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Files waiting for your confirmation</p>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="overflow-x-auto hidden md:block">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">File Number</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">From</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">To Department</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sent At</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Delivery Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendingMovements as $movement)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $movement->file->new_file_no }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div>{{ Str::limit($movement->file->subject, 50) }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($movement->file->file_title, 40) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    @if($movement->file->priority === 'very_urgent') bg-red-100 text-red-700
                                    @elseif($movement->file->priority === 'urgent') bg-orange-100 text-orange-700
                                    @else bg-green-100 text-green-700
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $movement->file->priority)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="text-gray-900 font-medium">{{ $movement->sender->name }}</div>
                                <div class="text-xs text-gray-500">{{ $movement->sender->position }}</div>
                                <div class="text-xs text-gray-500">{{ $movement->sender->department }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="font-medium text-gray-900">{{ $movement->intendedReceiver->department }}</div>
                                <div class="text-xs text-gray-500">{{ $movement->intendedReceiver->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $movement->sent_at->format('d M Y') }}</div>
                                <div class="text-xs">{{ $movement->sent_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ucwords(str_replace('_', ' ', $movement->delivery_method)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button wire:click="confirmReceipt({{ $movement->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmReceipt({{ $movement->id }})"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 shadow-lg shadow-green-500/20 transition-all disabled:opacity-75 disabled:cursor-not-allowed">
                                    <svg wire:loading wire:target="confirmReceipt({{ $movement->id }})" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg wire:loading.remove wire:target="confirmReceipt({{ $movement->id }})" class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span wire:loading.remove wire:target="confirmReceipt({{ $movement->id }})">Confirm</span>
                                    <span wire:loading wire:target="confirmReceipt({{ $movement->id }})">Confirming...</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 rounded-full bg-gray-100 mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 text-lg font-medium">No pending files to receive</p>
                                    <p class="text-gray-400 text-sm mt-1">All incoming files have been confirmed</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="md:hidden">
                @forelse($pendingMovements as $movement)
                <div class="p-4 border-b border-gray-100 last:border-b-0">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <span class="text-sm font-semibold text-gray-900">{{ $movement->file->new_file_no }}</span>
                            <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full
                                @if($movement->file->priority === 'very_urgent') bg-red-100 text-red-700
                                @elseif($movement->file->priority === 'urgent') bg-orange-100 text-orange-700
                                @else bg-green-100 text-green-700
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $movement->file->priority)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Subject -->
                    <p class="text-sm text-gray-700 mb-2">{{ Str::limit($movement->file->subject, 70) }}</p>
                    @if($movement->file->file_title)
                    <p class="text-xs text-gray-500 mb-3">{{ Str::limit($movement->file->file_title, 50) }}</p>
                    @endif

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3 py-2 border-t border-gray-100">
                        <div>
                            <span class="text-gray-500">From:</span>
                            <p class="font-medium text-gray-900">{{ $movement->sender->name }}</p>
                            <p class="text-gray-500">{{ $movement->sender->department }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Sent:</span>
                            <p class="font-medium text-gray-900">{{ $movement->sent_at->format('d M Y') }}</p>
                            <p class="text-gray-500">{{ $movement->sent_at->format('h:i A') }}</p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button wire:click="confirmReceipt({{ $movement->id }})"
                            wire:loading.attr="disabled"
                            wire:target="confirmReceipt({{ $movement->id }})"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 shadow-lg shadow-green-500/20 transition-all disabled:opacity-75 disabled:cursor-not-allowed">
                        <svg wire:loading wire:target="confirmReceipt({{ $movement->id }})" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg wire:loading.remove wire:target="confirmReceipt({{ $movement->id }})" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="confirmReceipt({{ $movement->id }})">Confirm Receipt</span>
                        <span wire:loading wire:target="confirmReceipt({{ $movement->id }})">Confirming...</span>
                    </button>
                </div>
                @empty
                <div class="p-8 text-center">
                    <div class="p-4 rounded-full bg-gray-100 mb-4 mx-auto w-fit">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 text-base font-medium">No pending files to receive</p>
                    <p class="text-gray-400 text-sm mt-1">All incoming files have been confirmed</p>
                </div>
                @endforelse
            </div>

            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-100 bg-gray-50">
                {{ $pendingMovements->links(data: ['scrollTo' => false]) }}
            </div>
        </div>

        <!-- Recently Received -->
        @if($recentlyReceived->count() > 0)
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Recently Received</h3>
                    <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Last 2 files you received</p>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($recentlyReceived as $movement)
                <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-900">{{ $movement->file->new_file_no }}</span>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    Received
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($movement->file->subject, 60) }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                <span class="block sm:inline">From: {{ $movement->sender->name }}</span>
                                <span class="hidden sm:inline"> | </span>
                                <span class="block sm:inline">{{ $movement->received_at->format('d M Y, h:i A') }}</span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('files.show', $movement->file) }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium transition-colors">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</div>
