<div wire:poll.2s.visible class="py-4 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Confirm Files</h1>
            <p class="mt-2 text-gray-600">Files sent to you that need confirmation</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium">Pending Confirmations</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['pending_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search files by file number or subject..."
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500">
            </div>
        </div>

        <!-- Files Table - Desktop -->
        <div class="bg-white rounded-lg shadow hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pendingReceipts as $movement)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $movement->file->new_file_no }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($movement->file->subject, 50) }}</div>
                                @if($movement->sender_comments)
                                <div class="text-xs text-gray-400 mt-1">
                                    <span class="font-medium">Note:</span> {{ Str::limit($movement->sender_comments, 100) }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $movement->sender->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $movement->sender->positionTitle ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->sent_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Pending
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="confirmReceipt({{ $movement->id }})"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-75 cursor-not-allowed"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors disabled:opacity-75 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="confirmReceipt({{ $movement->id }})">Confirm Receipt</span>
                                    <span wire:loading wire:target="confirmReceipt({{ $movement->id }})">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Confirming...
                                    </span>
                                </button>
                                <a href="{{ route('files.show', $movement->file) }}"
                                   class="ml-2 text-gray-600 hover:text-gray-900 text-sm font-medium">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-2 text-gray-600">No files pending confirmation</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pendingReceipts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendingReceipts->links(data: ['scrollTo' => false]) }}
            </div>
            @endif
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @forelse($pendingReceipts as $movement)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $movement->file->new_file_no }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($movement->file->subject, 60) }}</div>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                        Pending
                    </span>
                </div>
                <div class="text-sm text-gray-500 mb-2">
                    <p><span class="font-medium text-gray-700">From:</span> {{ $movement->sender->name ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-700">Sent:</span> {{ $movement->sent_at->format('M d, Y h:i A') }}</p>
                </div>
                @if($movement->sender_comments)
                <div class="text-xs text-gray-400 mb-3 p-2 bg-gray-50 rounded">
                    {{ $movement->sender_comments }}
                </div>
                @endif
                <div class="flex gap-2">
                    <button wire:click="confirmReceipt({{ $movement->id }})"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75 cursor-not-allowed"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors disabled:opacity-75 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="confirmReceipt({{ $movement->id }})">Confirm Receipt</span>
                        <span wire:loading wire:target="confirmReceipt({{ $movement->id }})">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Confirming...
                        </span>
                    </button>
                    <a href="{{ route('files.show', $movement->file) }}"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        View Details
                    </a>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-2 text-gray-600">No files pending confirmation</p>
            </div>
            @endforelse

            @if($pendingReceipts->hasPages())
            <div class="bg-white rounded-lg shadow p-4">
                {{ $pendingReceipts->links(data: ['scrollTo' => false]) }}
            </div>
            @endif
        </div>
    </div>
</div>
