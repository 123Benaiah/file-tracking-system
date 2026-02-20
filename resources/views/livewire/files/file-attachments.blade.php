<div class="bg-white overflow-hidden shadow-lg rounded-xl mb-4 sm:mb-6">
    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Attachments</h3>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">File attachments and documents</p>
        </div>
    </div>

    <div class="p-4 sm:p-6">
        @if($movementDropdowns->count() > 0)
            @php $fileNumber = 0; @endphp
            @foreach($movementDropdowns as $key => $dropdown)
            @php
                $attachments = $dropdown['attachments'];
                $sender = $dropdown['sender'];
                $movement = $dropdown['movement'];
                $movementNumber = $dropdown['movement_number'];

                $senderName = $sender ? $sender->name : 'Unknown';
                $position = $sender ? $sender->positionTitle : '';
                $department = $sender ? $sender->department : '';
                $unit = $sender ? $sender->unit : '';
                $departmentUnit = $department ? $department . ($unit ? ' - ' . $unit : '') : '';

                $sentAt = $movement && $movement->sent_at ? \Carbon\Carbon::parse($movement->sent_at)->format('M d, Y h:i A') : null;
            @endphp
            <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden">
                <button wire:click="toggleGroup('{{ $key }}')"
                        class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-7 h-7 mr-3 text-xs font-medium text-white bg-green-600 rounded-full">{{ $movementNumber }}</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $senderName }}
                            </p>
                            @if($position || $departmentUnit)
                                <p class="text-xs text-gray-500">
                                    {{ $position }}{{ $position && $departmentUnit ? ' | ' : '' }}{{ $departmentUnit }}
                                </p>
                            @endif
                            @if($sentAt)
                                <p class="text-xs text-gray-400 mt-0.5">{{ $sentAt }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded-full">
                            {{ $attachments->count() }} {{ $attachments->count() == 1 ? 'file' : 'files' }}
                        </span>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform {{ in_array($key, $openGroups) ? 'rotate-180' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>

                @if(in_array($key, $openGroups))
                <div class="border-t border-gray-200 divide-y divide-gray-100">
                    @if($attachments->count() > 0)
                        @foreach($attachments as $attachment)
                        @php $fileNumber = $fileNumber + 1; @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 gap-2">
                            <div class="flex items-center min-w-0 flex-1">
                                <span class="inline-flex items-center justify-center w-6 h-6 mr-3 text-xs font-medium text-white bg-gray-400 rounded-full">{{ $fileNumber }}</span>
                                <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $attachment->original_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ number_format($attachment->size / 1024, 1) }} KB
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 sm:flex-shrink-0 ml-9 sm:ml-0">
                                <a href="{{ route('files.attachment.download', $attachment) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-green-600 bg-green-50 hover:bg-green-100 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download
                                </a>
                                @if($canAdd)
                                    @if(auth()->user()->isRegistryHead() || $attachment->uploaded_by === auth()->user()->employee_number)
                                        <button wire:click="confirmDelete({{ $attachment->id }})"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-4 text-center">
                            <p class="text-sm text-gray-400">No attachments for this movement</p>
                        </div>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        @else
            <div class="text-center py-6 mb-6 bg-gray-50 rounded-lg">
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">No attachments yet</p>
            </div>
        @endif

        @if($canAdd)
            <div class="border-t border-gray-200 pt-6">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Add New Attachment</h4>
                <form wire:submit.prevent="uploadAttachments">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <input type="file" wire:model="newAttachments" multiple
                                   class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-l-lg file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <p class="text-xs text-gray-500 mt-1">Select multiple files (max 100MB each)</p>
                            @error('newAttachments.*')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex-shrink-0">
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    {{ count($newAttachments) === 0 ? 'disabled' : '' }}
                                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition ease-in-out duration-150">
                                <svg wire:loading wire:target="uploadAttachments" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Upload
                            </button>
                        </div>
                    </div>

                    @if(count($newAttachments) > 0)
                        <div class="mt-3 space-y-2">
                            @foreach($newAttachments as $index => $attachment)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg gap-2">
                                    <span class="text-sm text-gray-700 truncate">{{ $attachment->getClientOriginalName() }}</span>
                                    <button type="button" wire:click="removeNewAttachment({{ $index }})" class="text-red-500 hover:text-red-700 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </form>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal()"></div>
            <span class="inline-block align-middle h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Attachment</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this attachment? This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteAttachment()" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <svg wire:loading wire:target="deleteAttachment" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Delete
                    </button>
                    <button wire:click="closeModal()" wire:loading.attr="disabled"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
