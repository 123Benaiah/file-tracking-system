<div>
    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Card -->
            <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
                <div class="p-4 sm:p-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Track File</h2>
                        <p class="text-gray-500 mt-1 text-sm sm:text-base">Search and track file movement history</p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            @if (session()->has('info'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 sm:mb-6">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-700">{{ session('info') }}</span>
                    </div>
                </div>
            @endif

            <!-- Search Form -->
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
                <form wire:submit.prevent="search">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <div class="flex-1">
                            <label for="fileNumber" class="block text-sm font-medium text-gray-700 mb-2">
                                Enter File Number, Subject, or Title
                            </label>
                            <input type="text" wire:model="fileNumber" id="fileNumber"
                                   placeholder="e.g., MHA/1/1/1 or search by subject..."
                                   class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                   required>
                            @error('fileNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed"
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 shadow-lg shadow-orange-500/20 transition-all disabled:opacity-75 disabled:cursor-not-allowed">
                                <svg wire:loading.remove wire:target="search" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="search">Search</span>
                                <span wire:loading wire:target="search">Searching...</span>
                            </button>
                            @if($searchResults || $selectedFile)
                            <button type="button" wire:click="clearSearch"
                                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-700 bg-gray-100 border border-gray-300 hover:bg-gray-200 transition-colors">
                                Clear
                            </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Search Results (Multiple Files) -->
            @if($searchResults && $searchResults->count() > 1 && !$selectedFile)
            <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Search Results ({{ $searchResults->count() }} files found)</h3>
                    <p class="text-sm text-gray-500 mt-1">Select a file to view details</p>
                </div>

                <!-- Desktop Table -->
                <div class="overflow-x-auto hidden md:block">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">File Number</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subject</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Current Holder</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($searchResults as $file)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $file->getDisplayFileNo() }}
                                    @if($file->is_tj)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">
                                        TJ {{ $file->tj_number }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ Str::limit($file->subject, 60) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($file->status === 'at_registry') bg-green-100 text-green-700
                                        @elseif($file->status === 'in_transit') bg-orange-100 text-orange-700
                                        @elseif($file->status === 'received') bg-gray-800 text-white
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $file->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($file->currentHolder)
                                    {{ $file->currentHolder->formal_name }}
                                    <span class="text-xs text-gray-500 block">
                                        {{ $file->currentHolder->department }}
                                        @if($file->currentHolder->unit)
                                        - {{ $file->currentHolder->unit }}
                                        @endif
                                    </span>
                                    @else
                                    N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button wire:click="viewDetails({{ $file->id }})"
                                            class="text-orange-500 hover:text-orange-600 font-medium transition-colors">
                                        View Details &rarr;
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($searchResults as $file)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <span class="text-sm font-semibold text-gray-900">{{ $file->getDisplayFileNo() }}</span>
                                @if($file->is_tj)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">
                                        TJ {{ $file->tj_number }}
                                    </span>
                                @endif
                            </div>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                @if($file->status === 'at_registry') bg-green-100 text-green-700
                                @elseif($file->status === 'in_transit') bg-orange-100 text-orange-700
                                @elseif($file->status === 'received') bg-gray-800 text-white
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ $file->getStatusLabel() }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($file->subject, 70) }}</p>
                        @if($file->currentHolder)
                        <p class="text-xs text-gray-400 mb-3">
                            Holder: {{ $file->currentHolder->formal_name }}
                            <span class="text-gray-500">
                                ({{ $file->currentHolder->department }}
                                @if($file->currentHolder->unit)
                                - {{ $file->currentHolder->unit }}
                                @endif
                                )
                            </span>
                        </p>
                        @else
                        <p class="text-xs text-gray-400 mb-3">Holder: N/A</p>
                        @endif
                        <button wire:click="viewDetails({{ $file->id }})"
                                class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 shadow-sm transition-all">
                            View Details
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- No Results -->
            @if($searchResults !== null && $searchResults->count() === 0)
            <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="mt-3 text-sm font-semibold text-gray-900">No files found</h3>
                    <p class="mt-1 text-sm text-gray-500">No files matched your search for "<span class="font-medium text-gray-700">{{ $fileNumber }}</span>"</p>
                    <p class="mt-3 text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-lg inline-flex items-center px-3 py-1.5">
                        <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Try removing any extra spaces from your search term.
                    </p>
                </div>
            </div>
            @endif

            <!-- File Details -->
            @if($selectedFile)
            <div class="bg-white rounded-xl shadow-md mb-4 sm:mb-6 overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">File Details</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $selectedFile->getDisplayFileNo() }}</p>
                        @if($selectedFile->is_tj)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700 mt-1">
                            TJ {{ $selectedFile->tj_number }} of {{ $selectedFile->original_file_no }}
                        </span>
                        @endif
                    </div>
                    <button wire:click="clearDetails"
                            class="p-2 text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="px-4 sm:px-6 py-4 sm:py-5">
                    @if($selectedFile->is_tj && $selectedFile->original_file_no)
                    <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <span class="font-medium">This is a TJ of:</span>
                            <a href="#" wire:click="fileNumber = '{{ $selectedFile->original_file_no }}'; search()"
                               class="font-medium text-blue-700 hover:underline ml-1">
                                {{ $selectedFile->original_file_no }}
                            </a>
                        </p>
                    </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-3">Basic Information</h4>
                            <dl class="space-y-2 sm:space-y-3">
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">Subject:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $selectedFile->subject }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">File Title:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $selectedFile->file_title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">Old File No:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $selectedFile->old_file_no ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">File Number:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $selectedFile->new_file_no }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-3">Status & Priority</h4>
                            <dl class="space-y-2 sm:space-y-3">
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">Status:</dt>
                                    <dd class="mt-1">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            @if($selectedFile->status === 'at_registry') bg-green-100 text-green-700
                                            @elseif($selectedFile->status === 'in_transit') bg-orange-100 text-orange-700
                                            @elseif($selectedFile->status === 'received') bg-gray-800 text-white
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ $selectedFile->getStatusLabel() }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">Priority:</dt>
                                    <dd class="mt-1">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            @if($selectedFile->priority === 'very_urgent') bg-red-100 text-red-700
                                            @elseif($selectedFile->priority === 'urgent') bg-orange-100 text-orange-700
                                            @else bg-green-100 text-green-700
                                            @endif">
                                            {{ ucwords(str_replace('_', ' ', $selectedFile->priority)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">Confidentiality:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ ucfirst($selectedFile->confidentiality) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">Current Holder:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ $selectedFile->currentHolder?->formal_name ?? 'N/A' }}
                                        @if($selectedFile->currentHolder)
                                        <span class="text-xs text-gray-500">
                                            ({{ $selectedFile->currentHolder->department }}
                                            @if($selectedFile->currentHolder->unit)
                                            - {{ $selectedFile->currentHolder->unit }}
                                            @endif
                                            )
                                        </span>
                                        @endif
                                    </dd>
                                </div>
                                @if($selectedFile->status === 'in_transit')
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">Awaiting Receipt By:</dt>
                                    <dd class="text-sm font-medium text-orange-700">
                                        @if($selectedFile->movements->first()?->intendedReceiver)
                                            {{ $selectedFile->movements->first()->intendedReceiver->formal_name }}
                                            <span class="text-xs text-gray-500">
                                                ({{ $selectedFile->movements->first()->intendedReceiver->department }}
                                                @if($selectedFile->movements->first()->intendedReceiver->unit)
                                                - {{ $selectedFile->movements->first()->intendedReceiver->unit }}
                                                @endif
                                                )
                                            </span>
                                        @else
                                            <span class="text-gray-500">N/A</span>
                                        @endif
                                    </dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">Registered By:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $selectedFile->registeredBy->formal_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs sm:text-sm text-gray-400">Date Registered:</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($selectedFile->date_registered)->format('d M Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if($selectedFile->is_tj)
                    @php $tjFiles = $selectedFile->getTjFiles() @endphp
                    @if($tjFiles->count() > 0)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 mb-3">Other TJ Files of This File</h4>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <ul class="divide-y divide-gray-200">
                                @foreach($tjFiles as $tjFile)
                                <li class="py-2 flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">{{ $tjFile->new_file_no }}</span>
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">
                                            TJ {{ $tjFile->tj_number }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ ucfirst($tjFile->status) }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            <!-- Movement History -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <div>
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Movement History</h3>
                        <p class="text-xs sm:text-sm text-gray-500 hidden sm:block">Complete tracking of file movements</p>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-4">
                    @if($selectedFile->movements->count() > 0)
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($selectedFile->movements as $index => $movement)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white
                                                @if($movement->movement_status === 'received') bg-gradient-to-br from-green-500 to-emerald-600
                                                @elseif($movement->movement_status === 'sent') bg-gradient-to-br from-orange-500 to-amber-600
                                                @else bg-gradient-to-br from-gray-400 to-gray-500
                                                @endif">
                                                @if($movement->movement_status === 'received')
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                @else
                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:space-x-4">
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-700">
                                                        <span class="font-medium text-gray-900">{{ $movement->sender->formal_name ?? 'Unknown' }}</span> sent to
                                                        <span class="font-medium text-gray-900">{{ $movement->intendedReceiver->formal_name ?? 'Unknown' }}</span>
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $movement->sender->positionTitle ?? 'N/A' }} &rarr; {{ $movement->intendedReceiver->positionTitle ?? 'N/A' }}
                                                    </p>
                                                    @if($movement->movement_status === 'received')
                                                    <p class="text-xs text-green-600 mt-1">
                                                        &#10003; Received by {{ $movement->actualReceiver->formal_name ?? 'Unknown' }} on {{ $movement->received_at->format('d M Y, h:i A') }}
                                                    </p>
                                                    @endif
                                                    @if($movement->sender_comments)
                                                    <p class="text-xs text-gray-400 mt-1 italic">
                                                        "{{ $movement->sender_comments }}"
                                                    </p>
                                                    @endif
                                                </div>
                                                <div class="text-left sm:text-right text-sm mt-2 sm:mt-0 flex sm:flex-col items-center sm:items-end gap-2 sm:gap-0">
                                                    <p class="text-gray-500">{{ $movement->sent_at->format('d M Y') }}</p>
                                                    <p class="text-xs text-gray-400">{{ $movement->sent_at->format('h:i A') }}</p>
                                                    <span class="sm:mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                        @if($movement->movement_status === 'received') bg-green-100 text-green-700
                                                        @elseif($movement->movement_status === 'sent') bg-orange-100 text-orange-700
                                                        @else bg-gray-100 text-gray-700
                                                        @endif">
                                                        {{ ucfirst($movement->movement_status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="p-4 rounded-full bg-gray-100 mx-auto w-fit mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 text-lg font-medium">No movements recorded yet</p>
                        <p class="text-gray-400 text-sm mt-1">This file hasn't been sent to anyone</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
