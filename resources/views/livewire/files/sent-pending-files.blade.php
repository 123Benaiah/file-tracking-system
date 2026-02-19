<div wire:poll.2s.visible class="py-4 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Sent Pending Files</h1>
            <p class="mt-2 text-gray-600">Files you have sent that are awaiting receipt confirmation</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 font-medium">Sent Pending</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['sent_pending_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-4">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search files by file number or subject..."
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
        </div>

        <!-- Files Table - Desktop -->
        <div class="bg-white rounded-lg shadow hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent To</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sentPending as $movement)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="text-sm font-medium text-gray-900">{{ $movement->file->new_file_no }}</div>
                                    @if($movement->file->is_tj)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">
                                        TJ {{ $movement->file->tj_number }}
                                    </span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">{{ Str::limit($movement->file->subject, 50) }}</div>
                                @if($movement->file->file_title)
                                <div class="text-xs text-gray-400 mt-1">{{ Str::limit($movement->file->file_title, 80) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $movement->intendedReceiver->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $movement->intendedReceiver->positionTitle ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $movement->sent_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($movement->file->priority === 'very_urgent') bg-red-100 text-red-700
                                    @elseif($movement->file->priority === 'urgent') bg-orange-100 text-orange-700
                                    @else bg-blue-100 text-blue-700
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $movement->file->priority)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Pending
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('files.change-recipient', $movement->id) }}"
                                   class="text-blue-600 hover:text-blue-900 font-medium">
                                    Change Recipient
                                </a>
                                <a href="{{ route('files.show', $movement->file) }}"
                                   class="ml-2 text-gray-600 hover:text-gray-900">
                                    View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-2 text-gray-600">No sent files pending confirmation</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sentPending->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $sentPending->links(data: ['scrollTo' => false]) }}
            </div>
            @endif
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @forelse($sentPending as $movement)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="text-sm font-medium text-gray-900">{{ $movement->file->new_file_no }}</div>
                            @if($movement->file->is_tj)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">
                                TJ {{ $movement->file->tj_number }}
                            </span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">{{ Str::limit($movement->file->subject, 60) }}</div>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                        Pending
                    </span>
                </div>
                <div class="text-sm text-gray-500 mb-2">
                    <p><span class="font-medium text-gray-700">To:</span> {{ $movement->intendedReceiver->name ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-700">Sent:</span> {{ $movement->sent_at->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('files.change-recipient', $movement->id) }}"
                       class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        Change Recipient
                    </a>
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
                <p class="mt-2 text-gray-600">No sent files pending confirmation</p>
                <a href="{{ route('dashboard') }}" class="mt-4 inline-block px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                    Go to Dashboard
                </a>
            </div>
            @endforelse

            @if($sentPending->hasPages())
            <div class="bg-white rounded-lg shadow p-4">
                {{ $sentPending->links(data: ['scrollTo' => false]) }}
            </div>
            @endif
        </div>
    </div>
</div>
