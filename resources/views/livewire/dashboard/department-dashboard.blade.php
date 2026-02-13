<div wire:poll.10s>
    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Card - White Theme -->
            <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
                <div class="p-4 sm:p-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Department Dashboard</h2>
                        <p class="text-orange-500 mt-1 flex items-center text-sm sm:text-base">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                                                        {{ auth()->user()->departmentRel?->name ?? (auth()->user()->unitRel?->department?->name ?? 'N/A') }}
                        </p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards - White Theme -->
            <div class="grid grid-cols-2 gap-3 sm:gap-6 mb-4 sm:mb-6">
                <!-- My Files -->
                <div class="bg-white rounded-xl shadow-md p-3 sm:p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-4 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 shadow-lg shadow-orange-500/20">
                            <svg class="w-5 h-5 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-5">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">My Files</p>
                            <p class="text-2xl sm:text-4xl font-bold text-gray-900">{{ $stats['my_files'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Receipts -->
                <div class="bg-white rounded-xl shadow-md p-3 sm:p-6 hover:shadow-lg transition-all duration-300 {{ $stats['pending_receipts'] > 0 ? 'ring-2 ring-yellow-400 ring-opacity-50' : '' }}">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-4 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-500 shadow-lg shadow-yellow-500/20 {{ $stats['pending_receipts'] > 0 ? 'animate-pulse' : '' }}">
                            <svg class="w-5 h-5 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-5">
                            <p class="text-xs sm:text-sm font-medium text-gray-500">Pending</p>
                            <p class="text-2xl sm:text-4xl font-bold text-gray-900">{{ $stats['pending_receipts'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Receipts Alert -->
            @if($stats['pending_receipts'] > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 sm:p-5 mb-4 sm:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <div class="flex items-center flex-1">
                        <div class="flex-shrink-0 p-2 bg-yellow-100 rounded-lg">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <p class="ml-3 text-xs sm:text-sm text-yellow-800">
                            You have <strong class="text-yellow-900">{{ $stats['pending_receipts'] }}</strong> file(s) waiting for confirmation.
                        </p>
                    </div>
                    <a href="{{ route('files.receive') }}" wire:navigate class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg hover:from-yellow-600 hover:to-orange-600 shadow-lg shadow-yellow-500/20 transition-all">
                        View Now &rarr;
                    </a>
                </div>
            </div>
            @endif

            <!-- Quick Actions - White Theme -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6 mb-4 sm:mb-6">
                <a href="{{ route('files.receive') }}" wire:navigate class="bg-white rounded-xl shadow-md p-4 sm:p-6 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-xl bg-gradient-to-br from-green-500 to-green-600 shadow-lg shadow-green-500/20 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Receive Files</h3>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Confirm receipt of incoming files</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('files.track') }}" wire:navigate class="bg-white rounded-xl shadow-md p-4 sm:p-6 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">Track Files</h3>
                            <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Search and track file movement</p>
                        </div>
                    </div>
                </a>

                <button wire:click="openAllFilesModal" class="bg-white rounded-xl shadow-md p-4 sm:p-6 hover:shadow-lg transition-all duration-300 text-left w-full group">
                    <div class="flex items-center">
                        <div class="p-2 sm:p-3 rounded-xl bg-gradient-to-br from-green-500 to-green-600 shadow-lg shadow-green-500/20 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                        <div class="ml-3 sm:ml-4">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">All Files</h3>
                            <p class="text-xs sm:text-sm text-gray-500"><span class="hidden sm:inline">View all </span>{{ $stats['all_files'] }} <span class="hidden sm:inline">department</span> files</p>
                        </div>
                    </div>
                </button>
            </div>

            <!-- Recent Pending Receipts -->
            @if($pendingReceipts->count() > 0)
            <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Pending Receipts</h3>
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
                                    From: {{ $movement->sender->name ?? 'Unknown' }}
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

            <!-- Sent Files Pending Confirmation -->
            @if($sentPendingConfirmation->count() > 0)
            <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Sent - Pending Confirmation</h3>
                    <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">{{ $stats['sent_pending'] }}</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($sentPendingConfirmation as $movement)
                    <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $movement->file->new_file_no }}</span>
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
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
                                    To: {{ $movement->intendedReceiver->name ?? 'Unknown' }}
                                    <span class="hidden sm:inline">({{ $movement->intendedReceiver->departmentRel?->name ?? ($movement->intendedReceiver->unitRel?->department?->name ?? '') }})</span>
                                    <span class="sm:hidden block">{{ $movement->sent_at->format('d M Y') }}</span>
                                    <span class="hidden sm:inline">| Sent: {{ $movement->sent_at->format('d M Y, h:i A') }}</span>
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-3 py-2 sm:py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-50">
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
                <div class="px-4 sm:px-6 py-3 bg-gray-50 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        These files are still in your possession until the recipient confirms receipt.
                    </p>
                </div>
            </div>
            @endif

            <!-- Recently Received Section -->
            @if(isset($recentlyReceived) && $recentlyReceived->count() > 0)
            <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center justify-between cursor-pointer" wire:click="toggleRecentlyReceived">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Recently Received</h3>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform {{ $showRecentlyReceived ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="divide-y divide-gray-100 {{ $showRecentlyReceived ? '' : 'hidden' }}">
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
                                    From: {{ $movement->sender->name ?? 'Unknown' }}
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

            <!-- My Current Files -->
            @if($myFiles->count() > 0 || $search)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-500" stroke="current" fill="noneColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">My Current Files</h3>
                    </div>
                    <div class="flex w-full sm:w-auto gap-2">
                        <select wire:model.live="statusFilter" id="statusFilter"
                                class="bg-white border-gray-300 rounded-lg focus:border-orange-500 focus:ring-orange-500 text-sm">
                            <option value="">All Status</option>
                            <option value="at_registry">At Registry</option>
                            <option value="in_transit">In Transit</option>
                            <option value="received">Received</option>
                            <option value="completed">Completed</option>
                        </select>
                        <div class="flex flex-1">
                            <input type="text" wire:model.live="search" id="myFilesSearch" placeholder="Search files..."
                                   class="flex-1 sm:flex-none sm:w-40 border-gray-300 rounded-l-lg focus:border-orange-500 focus:ring-orange-500 text-sm">
                            <button wire:click="$refresh"
                                    class="px-3 py-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white rounded-r-lg hover:from-orange-600 hover:to-amber-600 transition-all text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Desktop Table -->
                <div class="overflow-x-auto hidden sm:block">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">File No</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Received</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($myFiles as $file)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $file->new_file_no }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ Str::limit($file->subject, 50) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($file->priority === 'very_urgent') bg-red-100 text-red-700
                                        @elseif($file->priority === 'urgent') bg-orange-100 text-orange-700
                                        @else bg-green-100 text-green-700
                                        @endif">
                                        {{ ucwords(str_replace('_', ' ', $file->priority)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $file->updated_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('files.show', $file) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                                            View
                                        </a>
                                        <a href="{{ route('files.send', $file) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-sm transition-all">
                                            Send
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination and Per Page -->
                <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-xs sm:text-sm text-gray-600">
                        Showing {{ $myFiles->firstItem() ?? 0 }} to {{ $myFiles->lastItem() ?? 0 }} of {{ $myFiles->total() }} files
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

                <!-- Mobile Cards -->
                <div class="sm:hidden divide-y divide-gray-100">
                    @foreach($myFiles as $file)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <span class="text-sm font-medium text-gray-900">{{ $file->new_file_no }}</span>
                                <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full
                                    @if($file->priority === 'very_urgent') bg-red-100 text-red-700
                                    @elseif($file->priority === 'urgent') bg-orange-100 text-orange-700
                                    @else bg-green-100 text-green-700
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $file->priority)) }}
                                </span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($file->subject, 70) }}</p>
                        <p class="text-xs text-gray-400 mb-3">Received: {{ $file->updated_at->format('d M Y') }}</p>
                        <div class="flex gap-2">
                            <a href="{{ route('files.show', $file) }}"
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                                View
                            </a>
                            <a href="{{ route('files.send', $file) }}"
                               class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-sm transition-all">
                                Send
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- All Files Modal -->
    @if($showAllFilesModal)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-2 sm:px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeAllFilesModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-4xl mx-2 sm:mx-auto">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">All Department Files</h3>
                    <button wire:click="closeAllFilesModal" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="max-h-[60vh] sm:max-h-96 overflow-y-auto">
                    @if($allFiles && $allFiles->count() > 0)
                    <!-- Desktop Table -->
                    <table class="min-w-full hidden sm:table">
                        <thead class="bg-gray-50 border-b border-gray-100 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">File No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Holder</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($allFiles as $file)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $file->new_file_no }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700">{{ Str::limit($file->subject, 40) }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($file->status === 'at_registry') bg-green-100 text-green-700
                                        @elseif($file->status === 'in_transit') bg-orange-100 text-orange-700
                                        @elseif($file->status === 'received') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $file->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    {{ $file->currentHolder->name ?? 'N/A' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Mobile List -->
                    <div class="sm:hidden divide-y divide-gray-100">
                        @foreach($allFiles as $file)
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-1">
                                <span class="text-sm font-medium text-gray-900">{{ $file->new_file_no }}</span>
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                    @if($file->status === 'at_registry') bg-green-100 text-green-700
                                    @elseif($file->status === 'in_transit') bg-orange-100 text-orange-700
                                    @elseif($file->status === 'received') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ $file->getStatusLabel() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">{{ Str::limit($file->subject, 60) }}</p>
                            <p class="text-xs text-gray-400">Holder: {{ $file->currentHolder->name ?? 'N/A' }}</p>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="px-6 py-12 text-center">
                        <p class="text-gray-500">No files found for your department.</p>
                    </div>
                    @endif
                </div>
                <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button wire:click="closeAllFilesModal"
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
