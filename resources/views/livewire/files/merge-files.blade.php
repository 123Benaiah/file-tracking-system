<div class="py-4 sm:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
            <div class="p-4 sm:p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Merge File Copies</h2>
                    <p class="text-gray-500 mt-1 text-sm sm:text-base">Search for an original file and merge its copies into it</p>
                </div>
                <div class="hidden sm:block">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Original File -->
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">Step 1: Search Original File</h3>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" wire:model.live.debounce.500ms="originalFileNo"
                           placeholder="Enter original file number (e.g., FTS-20260209-0001)"
                           class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 text-sm sm:text-base">
                    @error('originalFileNo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <button wire:click="searchOriginalFile"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-75 cursor-not-allowed"
                        class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 shadow-lg shadow-green-500/25 transition-all disabled:opacity-75 disabled:cursor-not-allowed">
                    <svg wire:loading.remove wire:target="searchOriginalFile" class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <svg wire:loading wire:target="searchOriginalFile" class="animate-spin h-4 w-4 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="searchOriginalFile">Search File</span>
                    <span wire:loading wire:target="searchOriginalFile">Searching...</span>
                </button>
            </div>
        </div>

        <!-- Original File Details -->
        @if($originalFile)
        <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-semibold text-gray-800">Original File Details</h3>
            </div>
            <div class="p-4 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-start gap-4">
                    <div class="flex-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">File Number</p>
                                <p class="text-lg font-bold text-gray-900">{{ $originalFile->new_file_no }}</p>
                                @if($originalFile->old_file_no)
                                    <p class="text-sm text-gray-500">Old: {{ $originalFile->old_file_no }}</p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($originalFile->status === 'at_registry') bg-green-100 text-green-700
                                    @elseif($originalFile->status === 'in_transit') bg-orange-100 text-orange-700
                                    @elseif($originalFile->status === 'received') bg-gray-800 text-white
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $originalFile->status)) }}
                                </span>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Subject</p>
                                <p class="text-gray-900">{{ $originalFile->subject }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">File Title</p>
                                <p class="text-gray-700">{{ $originalFile->file_title }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Priority</p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                    @if($originalFile->priority === 'very_urgent') bg-red-100 text-red-700
                                    @elseif($originalFile->priority === 'urgent') bg-orange-100 text-orange-700
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $originalFile->priority)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copies Selection -->
        <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Step 2: Select Copies to Merge</h3>
                </div>
                @php $selectableCopies = $copies->whereIn('status', ['completed', 'at_registry']); @endphp
                @if($selectableCopies->count() > 0)
                <div class="flex gap-2">
                    <button wire:click="selectAllCopies"
                            class="px-3 py-1.5 text-xs font-medium text-green-700 bg-green-50 border border-green-300 rounded-lg hover:bg-green-100 transition-colors">
                        Select All ({{ $selectableCopies->count() }})
                    </button>
                    <button wire:click="clearSelection"
                            class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors">
                        Clear Selection
                    </button>
                </div>
                @endif
            </div>

            @if($copies->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">TJ Number</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($copies as $copy)
                        @php $canMerge = in_array($copy->status, ['completed', 'at_registry']); @endphp
                        <tr class="hover:bg-gray-50 transition-colors {{ in_array($copy->id, $selectedCopies) ? 'bg-green-50' : '' }} {{ !$canMerge ? 'opacity-60' : '' }}">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <input type="checkbox"
                                       wire:click="toggleCopySelection({{ $copy->id }})"
                                       {{ !$canMerge ? 'disabled' : '' }}
                                       {{ in_array($copy->id, $selectedCopies) ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-500 focus:ring-green-500 border-gray-300 rounded cursor-pointer {{ !$canMerge ? 'cursor-not-allowed' : '' }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $copy->new_file_no }}</div>
                                <div class="text-xs text-gray-500">TJ #{{ $copy->tj_number }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div>{{ Str::limit($copy->subject, 50) }}</div>
                                @if(!$canMerge)
                                    <span class="text-xs text-orange-600 mt-1 block">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        Only completed or at registry files can be merged
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($copy->status === 'at_registry') bg-green-100 text-green-700
                                    @elseif($copy->status === 'in_transit') bg-orange-100 text-orange-700
                                    @elseif($copy->status === 'received') bg-gray-800 text-white
                                    @elseif($copy->status === 'completed') bg-gray-400 text-white
                                    @else bg-gray-100 text-gray-700
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $copy->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $copy->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="p-4 rounded-full bg-gray-100 mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <p class="text-gray-600 text-lg font-medium">No copies found</p>
                    <p class="text-gray-400 text-sm mt-1">This file has no copies to merge</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Merge Action -->
        @if(count($selectedCopies) > 0)
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800">Step 3: Confirm Merge</h3>
                    <p class="text-gray-500 text-sm mt-1">{{ count($selectedCopies) }} copy/copies selected to merge into {{ $originalFile->new_file_no }}</p>
                </div>
                <button wire:click="openMergeModal"
                        class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 rounded-lg hover:from-green-600 hover:to-green-700 shadow-lg shadow-green-500/25 transition-all inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Merge {{ count($selectedCopies) }} TJ file(s)
                </button>
            </div>
        </div>
        @endif
        @endif

        <!-- Empty State -->
        @if(!$originalFile && empty($originalFileNo))
        <div class="bg-white rounded-xl shadow-md p-8 sm:p-12">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Search for Original File</h3>
                <p class="text-gray-500 max-w-md">Enter the original file number above to find it and view all its copies. You can then select which copies to merge into the original.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Merge Confirmation Modal -->
    @if($showMergeModal && $originalFile)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeMergeModal"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirm File Merge
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    You are about to merge <strong>{{ count($selectedCopies) }}</strong> copy/copies into the original file:
                                </p>
                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm font-medium text-gray-900">{{ $originalFile->new_file_no }}</p>
                                    <p class="text-xs text-gray-500">{{ $originalFile->subject }}</p>
                                </div>
                                <p class="text-sm text-gray-500 mt-3">
                                    The following copies will be merged:
                                </p>
                                <ul class="mt-2 text-sm text-gray-600 list-disc list-inside max-h-32 overflow-y-auto">
                                    @foreach($copies->whereIn('id', $selectedCopies) as $copy)
                                    <li>{{ $copy->new_file_no }} (TJ #{{ $copy->tj_number }})</li>
                                    @endforeach
                                </ul>

                                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-xs text-yellow-700">
                                        <strong>Warning:</strong> This action cannot be undone. All movements and attachments from merged copies will be transferred to the original file. Merged copies will be permanently deleted and can no longer be tracked.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="mergeFiles" type="button"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-75 cursor-not-allowed"
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm disabled:opacity-75 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="mergeFiles">Confirm Merge</span>
                        <span wire:loading wire:target="mergeFiles">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Merging...
                        </span>
                    </button>
                    <button wire:click="closeMergeModal" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
