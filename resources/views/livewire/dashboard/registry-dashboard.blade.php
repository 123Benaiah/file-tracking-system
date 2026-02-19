<div wire:poll.2s.visible class="py-4 sm:py-8" x-data="{ showFilters: false, showRecentlyReceived: false, showSentPending: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <!-- Total Files -->
            <div class="bg-white rounded-xl shadow-md p-3 sm:p-5 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-lg shadow-blue-500/20">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Total Files</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <!-- At Registry -->
            <div class="bg-white rounded-xl shadow-md p-3 sm:p-5 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-xl bg-gradient-to-br from-green-500 to-green-600 shadow-lg shadow-green-500/20">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">At Registry</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['at_registry'] }}</p>
                    </div>
                </div>
            </div>

            <!-- In Transit -->
            <div class="bg-white rounded-xl shadow-md p-3 sm:p-5 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 shadow-lg shadow-orange-500/20">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Awaiting Receipt</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['in_transit'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Overdue -->
            <div class="bg-white rounded-xl shadow-md p-3 sm:p-5 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 rounded-xl bg-gradient-to-br from-red-500 to-rose-500 shadow-lg shadow-red-500/20">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-500">Overdue</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['overdue'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Receipts -->
        @if($pendingReceipts->count() > 0)
        <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Pending Receipts</h3>
                <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">{{ $stats['pending_receipts'] }}</span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($pendingReceipts as $movement)
                <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-900">{{ $movement->file->new_file_no }}</span>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                    @if($movement->file->priority === 'very_urgent') bg-red-100 text-red-700
                                    @elseif($movement->file->priority === 'urgent') bg-orange-100 text-orange-700
                                    @else bg-green-100 text-green-700
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $movement->file->priority)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($movement->file->subject, 60) }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                From: {{ $movement->sender->formal_name ?? 'Unknown' }}
                                <span class="hidden sm:inline">({{ $movement->sender->departmentRel?->name ?? ($movement->sender->unitRel?->department?->name ?? 'N/A') }})</span>
                                <span class="sm:hidden block">{{ $movement->sent_at->format('d M Y') }}</span>
                                <span class="hidden sm:inline">| Sent: {{ $movement->sent_at->format('d M Y, h:i A') }}</span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <button wire:click="confirmReceipt({{ $movement->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="confirmReceipt({{ $movement->id }})"
                                    class="inline-flex items-center px-3 py-2 sm:py-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 shadow-sm transition-all w-full sm:w-auto justify-center disabled:opacity-75 disabled:cursor-not-allowed">
                                <svg wire:loading wire:target="confirmReceipt({{ $movement->id }})" class="animate-spin -ml-1 mr-1 h-4 w-4 sm:h-3 sm:w-3 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg wire:loading.remove wire:target="confirmReceipt({{ $movement->id }})" class="w-4 h-4 sm:w-3 sm:h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="confirmReceipt({{ $movement->id }})">Confirm</span>
                                <span wire:loading wire:target="confirmReceipt({{ $movement->id }})">...</span>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-4 sm:px-6 py-3 bg-gray-50 border-t border-gray-100">
                <a href="{{ route('files.receive') }}" class="text-sm text-orange-500 hover:text-orange-600 font-medium transition-colors">
                    View all pending receipts &rarr;
                </a>
            </div>
        </div>
        @endif

        <!-- Recently Received Section -->
        @if(isset($recentlyReceived) && $recentlyReceived->count() > 0)
        <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center cursor-pointer hover:bg-gray-50 transition-colors" @click="showRecentlyReceived = !showRecentlyReceived">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex-1">Recently Received</h3>
                <span class="mr-2 text-xs text-gray-500">{{ $recentlyReceived->count() }} files</span>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="showRecentlyReceived ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
            <div class="divide-y divide-gray-100" x-show="showRecentlyReceived" x-cloak x-transition>
                @foreach($recentlyReceived as $movement)
                <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-900">{{ $movement->file->new_file_no }}</span>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                    @if($movement->file->priority === 'very_urgent') bg-red-100 text-red-700
                                    @elseif($movement->file->priority === 'urgent') bg-orange-100 text-orange-700
                                    @else bg-green-100 text-green-700
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $movement->file->priority)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($movement->file->subject, 60) }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                From: {{ $movement->sender->formal_name ?? 'Unknown' }}
                                <span class="hidden sm:inline">({{ $movement->sender->departmentRel?->name ?? ($movement->sender->unitRel?->department?->name ?? 'N/A') }})</span>
                                <span class="hidden sm:inline">| Received: {{ $movement->received_at->format('d M Y, h:i A') }}</span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('files.show', $movement->file) }}"
                               class="inline-flex items-center px-3 py-2 sm:py-1.5 text-xs font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4 sm:w-3 sm:h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View File
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Sent Files Pending Confirmation -->
        @if($sentPendingConfirmation->count() > 0)
        <div id="sent-pending" class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center cursor-pointer hover:bg-gray-50 transition-colors" @click="showSentPending = !showSentPending">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex-1">Sent - Pending Confirmation</h3>
                <span class="mr-2 text-xs text-gray-500">{{ $sentPendingConfirmation->count() }} files</span>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="showSentPending ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
            <div class="divide-y divide-gray-100" x-show="showSentPending" x-cloak x-transition>
                @foreach($sentPendingConfirmation as $movement)
                <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-medium text-gray-900">{{ $movement->file->new_file_no }}</span>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-orange-100 text-orange-700">
                                    Sent
                                </span>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                    @if($movement->file->priority === 'very_urgent') bg-red-100 text-red-700
                                    @elseif($movement->file->priority === 'urgent') bg-orange-100 text-orange-700
                                    @else bg-green-100 text-green-700
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $movement->file->priority)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($movement->file->subject, 60) }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                To: {{ $movement->intendedReceiver->formal_name ?? 'Unknown' }}
                                <span class="hidden sm:inline">({{ $movement->intendedReceiver->departmentRel?->name ?? ($movement->intendedReceiver->unitRel?->department?->name ?? '') }})</span>
                                <span class="sm:hidden block">{{ $movement->sent_at->format('d M Y') }}</span>
                                <span class="hidden sm:inline">| Sent: {{ $movement->sent_at->format('d M Y, h:i A') }}</span>
                            </p>
                        </div>
                        <div class="flex-shrink-0 flex items-center gap-2">
                            <a href="{{ route('files.change-recipient', $movement->id) }}" 
                               class="inline-flex items-center px-3 py-2 sm:py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Change
                            </a>
                            <span class="inline-flex items-center px-3 py-2 sm:py-1.5 text-xs font-medium rounded-lg text-orange-700 bg-orange-50">
                                <svg class="w-4 h-4 sm:w-3 sm:h-3 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Awaiting Receipt
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-4 sm:px-6 py-3 bg-gray-50 border-t border-gray-100" x-show="showSentPending" x-cloak x-transition>
                <p class="text-xs text-gray-500">
                    These files are still in your possession until the recipient confirms receipt.
                </p>
            </div>
        </div>
        @endif

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-md mb-6">
            <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100 flex justify-between items-center cursor-pointer hover:bg-gray-50 transition-colors" @click="showFilters = !showFilters">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filters
                </h3>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>

            <div class="p-4 sm:p-5" x-show="showFilters" x-cloak x-transition>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search"
                               placeholder="File no, subject..."
                               class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <div>
                        <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model.live="statusFilter" id="statusFilter"
                                class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Status</option>
                            <option value="at_registry">At Registry</option>
                            <option value="in_transit">Awaiting Receipt</option>
                            <option value="received">Received</option>
                            <option value="under_review">Under Review</option>
                        </select>
                    </div>

                    <div>
                        <label for="departmentFilter" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select wire:model.live="departmentFilter" id="departmentFilter"
                                class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="priorityFilter" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select wire:model.live="priorityFilter" id="priorityFilter"
                                class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            <option value="">All Priorities</option>
                            <option value="normal">Normal</option>
                            <option value="urgent">Urgent</option>
                            <option value="very_urgent">Very Urgent</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-4">
                    <div>
                        <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" wire:model.live="dateFrom" id="dateFrom"
                               class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>

                    <div>
                        <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" wire:model.live="dateTo" id="dateTo"
                               class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                    </div>
                </div>
            </div>

            <!-- Actions Bar - Always visible -->
            <div class="px-4 sm:px-5 py-3 sm:py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-col sm:flex-row sm:flex-wrap justify-between items-start sm:items-center gap-3">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 w-full sm:w-auto">
                        <div class="text-xs sm:text-sm text-gray-600">
                            Showing {{ $files->firstItem() ?? 0 }} to {{ $files->lastItem() ?? 0 }} of {{ $files->total() }} files
                            @if($this->selectedCount > 0)
                            <span class="ml-2 text-orange-600 font-medium">({{ $this->selectedCount }} selected)</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="perPage" class="text-xs sm:text-sm text-gray-600">Per page:</label>
                            <select wire:model.live="perPage" id="perPage"
                                    class="bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 text-xs sm:text-sm py-1">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
                        <div x-data="{ open: false }" class="relative flex-1 sm:flex-none" @click.away="open = false" @keydown.escape="open = false">
                            <button @click="open = !open" type="button"
                                    class="w-full sm:w-auto px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export
                                <svg class="w-4 h-4 ml-1 sm:ml-2 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute left-0 right-0 sm:left-auto sm:right-0 bottom-full mb-2 w-full sm:w-56 rounded-xl shadow-lg bg-white border border-gray-200 z-50">
                                <div class="py-1">
                                    <button wire:click="exportReport('filtered')" @click="open = false"
                                            class="block w-full text-left px-4 py-3 sm:py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors">
                                        Export Filtered ({{ $files->total() }})
                                    </button>
                                    @if($this->selectedCount > 0)
                                    <button wire:click="exportReport('selected')" @click="open = false"
                                            class="block w-full text-left px-4 py-3 sm:py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 transition-colors">
                                        Export Selected ({{ $this->selectedCount }})
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($this->selectedCount > 0)
                        <button wire:click="confirmDeleteSelected"
                                class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 shadow-lg shadow-red-500/25 transition-all inline-flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Selected ({{ $this->selectedCount }})
                        </button>
                        @endif
                        @if(auth()->user()->isRegistryHead())
                        <a href="{{ route('files.merge') }}"
                           class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg shadow-green-500/25 transition-all duration-300 inline-flex items-center flex-1 sm:flex-none justify-center">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span class="hidden sm:inline">Merge Files</span>
                            <span class="sm:hidden">Merge</span>
                        </a>
                        <a href="{{ route('files.create') }}"
                           class="px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 shadow-lg shadow-orange-500/25 transition-all duration-300 inline-flex items-center flex-1 sm:flex-none justify-center">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="hidden sm:inline">Register New File</span>
                            <span class="sm:hidden">New File</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Files Table - Desktop -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-3 py-4 text-left">
                                <input type="checkbox" wire:model.live="selectAll"
                                       class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">File Details</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Current Holder</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dates</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($files as $file)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-4">
                                <input type="checkbox" wire:model.live="selectedFiles" value="{{ $file->id }}"
                                       class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded">
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $file->new_file_no }}
                                        @if($file->old_file_no)
                                        <span class="text-gray-400 text-xs ml-2">(Old: {{ $file->old_file_no }})</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">{{ Str::limit($file->subject, 60) }}</div>
                                    <div class="flex items-center mt-2 gap-2">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            {{ $file->priority == 'urgent' ? 'bg-orange-100 text-orange-700' :
                                               ($file->priority == 'very_urgent' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') }}">
                                            {{ ucfirst($file->priority) }}
                                        </span>
                                        @if($file->confidentiality != 'public')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-800">
                                            {{ ucfirst($file->confidentiality) }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($file->currentHolder)
                                <div class="text-sm text-gray-900 font-medium">{{ $file->currentHolder->formal_name }}</div>
                                <div class="text-sm text-gray-500">{{ $file->currentHolder->departmentRel?->name ?? ($file->currentHolder->unitRel?->department?->name ?? 'N/A') }}</div>
                                @elseif($file->latestMovement)
                                <div class="text-sm text-orange-600 font-medium">Awaiting Receipt by:</div>
                                <div class="text-sm text-gray-500">{{ $file->latestMovement->intendedReceiver->formal_name ?? 'N/A' }}</div>
                                @else
                                <span class="text-sm text-gray-400">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $file->status == 'at_registry' ? 'bg-green-100 text-green-700' :
                                       ($file->status == 'in_transit' ? 'bg-orange-100 text-orange-700' :
                                       ($file->status == 'received' ? 'bg-gray-800 text-white' :
                                       ($file->status == 'under_review' ? 'bg-gray-200 text-gray-800' : 'bg-gray-100 text-gray-700'))) }}">
                                    {{ $file->getStatusLabel() }}
                                </span>
                                @if($file->isOverdue())
                                <div class="mt-2 text-xs text-red-600 font-medium flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $file->getDaysOverdue() }} day(s) overdue
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">Reg: {{ $file->date_registered->format('d/m/Y') }}</div>
                                @if($file->due_date)
                                <div class="text-sm {{ $file->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                    Due: {{ $file->due_date->format('d/m/Y') }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @if(($file->status == 'at_registry' || ($file->status == 'completed' && auth()->user()->canResendFiles())) && auth()->user()->canResendFiles())
                                    <a href="{{ route('files.send', $file) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-sm transition-all">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        {{ $file->status == 'completed' ? 'Resend' : 'Send' }}
                                    </a>
                                    @endif
                                    <a href="{{ route('files.show', $file) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>
                                    @if(auth()->user()->isRegistryHead() && $file->movements->isNotEmpty())
                                    <a href="{{ route('files.movements', $file) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 shadow-sm transition-all">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        History
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 rounded-full bg-gray-100 mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 text-lg font-medium">No files found</p>
                                    <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or search terms</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $files->links(data: ['scrollTo' => false]) }}
            </div>
        </div>

        <!-- Files Cards - Mobile -->
        <div class="md:hidden space-y-3">
            @forelse($files as $file)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-4">
                    <!-- Header with checkbox and file number -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" wire:model.live="selectedFiles" value="{{ $file->id }}"
                                   class="h-5 w-5 mt-0.5 text-orange-500 focus:ring-orange-500 border-gray-300 rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $file->new_file_no }}</div>
                                @if($file->old_file_no)
                                <div class="text-xs text-gray-400">Old: {{ $file->old_file_no }}</div>
                                @endif
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $file->status == 'at_registry' ? 'bg-green-100 text-green-700' :
                               ($file->status == 'in_transit' ? 'bg-orange-100 text-orange-700' :
                               ($file->status == 'received' ? 'bg-emerald-100 text-emerald-700' :
                               ($file->status == 'under_review' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700'))) }}">
                            {{ $file->getStatusLabel() }}
                        </span>
                    </div>

                    <!-- Subject -->
                    <p class="text-sm text-gray-700 mb-3">{{ Str::limit($file->subject, 80) }}</p>

                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $file->priority == 'urgent' ? 'bg-orange-100 text-orange-700' :
                               ($file->priority == 'very_urgent' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') }}">
                            {{ ucfirst($file->priority) }}
                        </span>
                        @if($file->confidentiality != 'public')
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-800">
                            {{ ucfirst($file->confidentiality) }}
                        </span>
                        @endif
                        @if($file->isOverdue())
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                            {{ $file->getDaysOverdue() }} day(s) overdue
                        </span>
                        @endif
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-3 text-xs mb-3 py-3 border-t border-gray-100">
                        <div>
                            <span class="text-gray-500">Holder:</span>
                            <p class="font-medium text-gray-900">
                                @if($file->currentHolder)
                                    {{ $file->currentHolder->formal_name }}
                                @elseif($file->latestMovement)
                                    <span class="text-orange-600">Awaiting Receipt</span>
                                @else
                                    Not assigned
                                @endif
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-500">Registered:</span>
                            <p class="font-medium text-gray-900">{{ $file->date_registered->format('d/m/Y') }}</p>
                        </div>
                        @if($file->due_date)
                        <div class="col-span-2">
                            <span class="text-gray-500">Due:</span>
                            <span class="{{ $file->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-900 font-medium' }}">
                                {{ $file->due_date->format('d/m/Y') }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">
                        @if(($file->status == 'at_registry' || ($file->status == 'completed' && auth()->user()->canResendFiles())) && auth()->user()->canResendFiles())
                        <a href="{{ route('files.send', $file) }}"
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-sm transition-all">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            {{ $file->status == 'completed' ? 'Resend' : 'Send' }}
                        </a>
                        @endif
                        <a href="{{ route('files.show', $file) }}"
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </a>
                        @if(auth()->user()->isRegistryHead() && $file->movements->isNotEmpty())
                        <a href="{{ route('files.movements', $file) }}"
                           class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 shadow-sm transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <div class="p-4 rounded-full bg-gray-100 mb-4 mx-auto w-fit">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-lg font-medium">No files found</p>
                <p class="text-gray-400 text-sm mt-1">Try adjusting your filters or search terms</p>
            </div>
            @endforelse

            <!-- Pagination for Mobile -->
            @if($files->hasPages())
            <div class="bg-white rounded-xl shadow-md p-4">
                {{ $files->links(data: ['scrollTo' => false]) }}
            </div>
            @endif
        </div>
    </div>

    <!-- Bulk Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="inline-block align-middle h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xs sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Files</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete {{ $this->selectedCount }} file(s)? This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteSelected" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <svg wire:loading wire:target="deleteSelected" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Delete
                    </button>
                    <button wire:click="$set('showDeleteModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
