<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                File Details
            </h2>
            @if($file->canBeSentBy(auth()->user()) || auth()->user()->isRegistryHead())
            <div class="flex flex-wrap gap-2">
                @if($file->canBeSentBy(auth()->user()))
                <a href="{{ route('files.send', $file) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-green-600 hover:to-green-700 focus:from-green-600 focus:to-green-700 active:from-green-700 active:to-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow-lg shadow-green-500/25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    @if(auth()->user()->isRegistryStaff())
                        <span>Resend</span>
                    @else
                        <span>send</span>
                    @endif
                </a>
                @endif
                @if(auth()->user()->isRegistryHead())
                <a href="{{ route('files.edit', $file) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="hidden sm:inline">Edit File</span>
                    <span class="sm:hidden">Edit</span>
                </a>
                <a href="{{ route('files.movements', $file) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-orange-600 hover:to-orange-700 focus:from-orange-600 focus:to-orange-700 active:from-orange-700 active:to-orange-800 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 shadow-lg shadow-orange-500/25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <span class="hidden sm:inline">Manage Movements</span>
                    <span class="sm:hidden">History</span>
                </a>
                @endif
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session()->has('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-4 sm:mb-6">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $file->new_file_no }}</h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Registered on {{ \Carbon\Carbon::parse($file->date_registered)->format('d M Y') }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2 sm:px-3 py-1 inline-flex text-xs sm:text-sm leading-5 font-semibold rounded-full
                                @if($file->status === 'at_registry') bg-green-100 text-green-800
                                @elseif($file->status === 'in_transit') bg-yellow-100 text-yellow-800
                                @elseif($file->status === 'received') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $file->getStatusLabel() }}
                            </span>
                            <span class="px-2 sm:px-3 py-1 inline-flex text-xs sm:text-sm leading-5 font-semibold rounded-full
                                @if($file->priority === 'very_urgent') bg-red-100 text-red-800
                                @elseif($file->priority === 'urgent') bg-orange-100 text-orange-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ ucwords(str_replace('_', ' ', $file->priority)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Subject:</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $file->subject }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">File Title:</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $file->file_title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Old File No:</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $file->old_file_no ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Confidentiality:</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ ucfirst($file->confidentiality) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Current Status</h4>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Holder:</dt>
                                    <dd class="text-sm text-gray-900 mt-1">
                                        {{ $file->currentHolder?->name ?? 'N/A' }}
                                        @if($file->currentHolder)
                                        <span class="text-xs text-gray-500">({{ $file->currentHolder->department }})</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Registered By:</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $file->registeredBy->name }}</dd>
                                </div>
                                @if($file->due_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Due Date:</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::parse($file->due_date)->format('d M Y') }}</dd>
                                </div>
                                @endif
                                @if($file->remarks)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Remarks:</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $file->remarks }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attachments Section -->
            @livewire(\App\Livewire\Files\FileAttachments::class, ['file' => $file])

            <!-- Movement History -->
            <div class="bg-white rounded-xl shadow-lg">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900">Movement History</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Complete tracking of file movements</p>
                </div>
                <div class="px-4 sm:px-6 py-4">
                    @if($file->movements->count() > 0)
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($file->movements->sortBy('sent_at') as $index => $movement)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white
                                                @if($movement->movement_status === 'received') bg-green-500
                                                @elseif($movement->movement_status === 'sent') bg-orange-500
                                                @else bg-gray-400
                                                @endif">
                                                @if($movement->movement_status === 'received')
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                @else
                                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:space-x-4">
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-700">
                                                        <span class="font-medium text-gray-900">{{ $movement->sender->name ?? 'Unknown' }}</span> sent to
                                                        <span class="font-medium text-gray-900">{{ $movement->intendedReceiver->name ?? 'Unknown' }}</span>
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $movement->sender->positionTitle ?? 'N/A' }} &rarr; {{ $movement->intendedReceiver->positionTitle ?? 'N/A' }}
                                                    </p>
                                                    @if($movement->movement_status === 'received')
                                                    <p class="text-xs text-green-600 mt-1">
                                                        &#10003; Received by {{ $movement->actualReceiver->name ?? 'Unknown' }} on {{ $movement->received_at->format('d M Y, h:i A') }}
                                                    </p>
                                                    @endif
                                                    @if($movement->sender_comments)
                                                    <p class="text-xs text-gray-500 mt-1 italic">
                                                        "{{ $movement->sender_comments }}"
                                                    </p>
                                                    @endif
                                                </div>
                                                <div class="text-left sm:text-right text-sm mt-2 sm:mt-0 flex sm:flex-col items-center sm:items-end gap-2 sm:gap-0 text-gray-500">
                                                    <p>{{ $movement->sent_at->format('d M Y') }}</p>
                                                    <p class="text-xs">{{ $movement->sent_at->format('h:i A') }}</p>
                                                    <span class="sm:mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                        @if($movement->movement_status === 'received') bg-green-100 text-green-800
                                                        @elseif($movement->movement_status === 'sent') bg-orange-100 text-orange-800
                                                        @else bg-gray-100 text-gray-800
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
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No movements recorded yet.</p>
                    </div>
                    @endif
                </div>
            </div>

<div class="mt-6 flex items-center justify-between">
    <!-- Back button -->
    <a href="{{ url()->previous() }}"
       class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back
    </a>

    <!-- Registry Dashboard button - shows if user belongs to registry -->
    @if(auth()->user()->isRegistryStaff())
    <a href="{{ route('registry.dashboard') }}"
       class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Registry Dashboard
    </a>
    @endif
</div>


        </div>
    </div>
</x-app-layout>
