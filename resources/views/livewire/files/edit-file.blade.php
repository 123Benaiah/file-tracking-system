<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Edit File</h2>
                        <p class="text-gray-600 mt-1">{{ $file->new_file_no }}</p>
                    </div>
                    <a href="{{ route('files.show', $file) }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                </div>
            </div>

            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="update">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">File Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subject -->
                        <div class="md:col-span-2">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Subject *</label>
                            <input type="text" wire:model="subject" id="subject"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            @error('subject') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- File Title -->
                        <div class="md:col-span-2">
                            <label for="file_title" class="block text-sm font-medium text-gray-700">File Title *</label>
                            <input type="text" wire:model="file_title" id="file_title"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            @error('file_title') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Old File No -->
                        <div>
                            <label for="old_file_no" class="block text-sm font-medium text-gray-700">Old File Number</label>
                            <input type="text" wire:model="old_file_no" id="old_file_no"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            @error('old_file_no') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- New File No -->
                        <div>
                            <label for="new_file_no" class="block text-sm font-medium text-gray-700">New File Number *</label>
                            <input type="text" wire:model="new_file_no" id="new_file_no"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            @error('new_file_no') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700">Priority *</label>
                            <select wire:model="priority" id="priority"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                                <option value="very_urgent">Very Urgent</option>
                            </select>
                            @error('priority') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                            <select wire:model="status" id="status"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="at_registry">At Registry</option>
                                <option value="in_transit">Awaiting Receipt</option>
                                <option value="received">Received</option>
                                <option value="under_review">Under Review</option>
                                <option value="action_required">Action Required</option>
                                <option value="completed">Completed</option>
                                <option value="returned_to_registry">Returned to Registry</option>
                                <option value="archived">Archived</option>
                            </select>
                            @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confidentiality -->
                        <div>
                            <label for="confidentiality" class="block text-sm font-medium text-gray-700">Confidentiality *</label>
                            <select wire:model="confidentiality" id="confidentiality"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="public">Public</option>
                                <option value="confidential">Confidential</option>
                                <option value="secret">Secret</option>
                            </select>
                            @error('confidentiality') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Current Holder -->
                        <div>
                            <label for="current_holder" class="block text-sm font-medium text-gray-700">Current Holder</label>
                            <select wire:model="current_holder" id="current_holder"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                                <option value="">-- None --</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->employee_number }}">
                                    {{ $employee->name }} ({{ $employee->employee_number }})
                                </option>
                                @endforeach
                            </select>
                            @error('current_holder') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                            <input type="date" wire:model="due_date" id="due_date"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            @error('due_date') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Remarks -->
                        <div class="md:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea wire:model="remarks" id="remarks" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"></textarea>
                            @error('remarks') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center">
                    <button type="button" wire:click="deleteFile" wire:confirm="Are you sure you want to delete this file? This will also delete all file movements." wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75 disabled:cursor-not-allowed">
                        <svg wire:loading wire:target="deleteFile" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg wire:loading.remove wire:target="deleteFile" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span wire:loading.remove wire:target="deleteFile">Delete File</span>
                        <span wire:loading wire:target="deleteFile">Deleting...</span>
                    </button>

                    <div class="flex gap-4">
                        <a href="{{ route('files.show', $file) }}"
                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75 disabled:cursor-not-allowed">
                            <svg wire:loading wire:target="update" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg wire:loading.remove wire:target="update" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span wire:loading.remove wire:target="update">Update File</span>
                            <span wire:loading wire:target="update">Updating...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
