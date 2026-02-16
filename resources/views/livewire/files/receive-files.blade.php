<div class="py-4 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
            <div class="p-4 sm:p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Received Files</h2>
                    <p class="text-gray-500 mt-1 text-sm sm:text-base">History of files you have received</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('files.create') }}" wire:navigate class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-sm transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Send File
                    </a>
                    <div class="hidden sm:block">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <!-- Received Files Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Received Files</h3>
                    <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Files you have confirmed receipt</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">File Number</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">From</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Received At</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($receivedFiles as $movement)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $movement->file->new_file_no }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div>{{ Str::limit($movement->file->subject, 50) }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($movement->file->file_title, 40) }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="text-gray-900 font-medium">{{ $movement->sender->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-500">{{ $movement->sender->departmentRel?->name ?? ($movement->sender->unitRel?->department?->name ?? 'N/A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $movement->received_at->format('d M Y') }}</div>
                                <div class="text-xs">{{ $movement->received_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if((($movement->file->status == 'completed' || $movement->file->status == 'received') && auth()->user()->canResendFiles()))
                                <a href="{{ route('files.send', $movement->file) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-sm transition-all">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    {{ $movement->file->status == 'completed' ? 'Resend' : 'Send' }}
                                </a>
                                @else
                                <a href="{{ route('files.show', $movement->file) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg text-orange-600 bg-orange-50 hover:bg-orange-100 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 0 11-6 0 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-4 rounded-full bg-gray-100 mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 text-lg font-medium">No received files found</p>
                                    <p class="text-gray-400 text-sm mt-1">Files you receive will appear here</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-100 bg-gray-50">
                {{ $receivedFiles->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>
