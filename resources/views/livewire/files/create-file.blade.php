<div class="py-4 sm:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-xl">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Register New File</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Complete all required fields to register a new file</p>
            </div>

            <div class="px-4 sm:px-6 py-4 sm:py-6">
                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- File Creation Type -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                File Type <span class="text-red-500">*</span>
                            </label>
                            <div class="flex flex-wrap gap-2 sm:gap-4 mb-3">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="fileCreationType" value="new"
                                           class="form-radio h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">New File</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="fileCreationType" value="copy"
                                           class="form-radio h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Create Copy of Existing File</span>
                                </label>
                            </div>
                        </div>

                        <!-- Copy of File Number -->
                        @if($fileCreationType === 'copy')
                        <div class="md:col-span-2">
                            <label for="copyOfFileNo" class="block text-sm font-medium text-gray-700 mb-2">
                                Original File Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.live.debounce.300ms="copyOfFileNo" id="copyOfFileNo"
                                   placeholder="Enter original file number (e.g., FTS-20260209-0001)"
                                   class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            @error('copyOfFileNo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                            @if($copyOfFileData)
                            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm font-medium text-green-800">Original File Found:</p>
                                <p class="text-sm text-green-700"><span class="font-medium">File No:</span> {{ $copyOfFileData['new_file_no'] }}</p>
                                <p class="text-sm text-green-700"><span class="font-medium">Subject:</span> {{ $copyOfFileData['subject'] }}</p>
                                <p class="text-sm text-green-700"><span class="font-medium">Status:</span> {{ ucfirst(str_replace('_', ' ', $copyOfFileData['status'])) }}</p>
                                <p class="text-sm text-green-700 mt-1"><span class="font-medium">New Copy Number:</span> {{ $copyOfFileData['new_file_no'] }}-copy{{ $copyOfFileData['next_copy_number'] }}</p>
                            </div>
                            @elseif(!empty($copyOfFileNo) && strlen($copyOfFileNo) >= 3)
                            <p class="text-xs text-red-500 mt-1">No original file found with this number</p>
                            @endif
                        </div>
                        @endif

                        <!-- Subject Type Selection -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Subject <span class="text-red-500">*</span>
                            </label>

                            <!-- Subject Type Toggle -->
                            <div class="flex flex-wrap gap-2 sm:gap-4 mb-3">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="subjectType" value="existing"
                                           class="form-radio h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Select Existing Subject</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" wire:model.live="subjectType" value="new"
                                           class="form-radio h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Enter New Subject</span>
                                </label>
                            </div>

                            <!-- Existing Subject Dropdown -->
                            @if($subjectType === 'existing')
                            <select wire:model="subject" id="subject"
                                    class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="">Select Subject</option>
                                @foreach($existingSubjects as $existingSubject)
                                <option value="{{ $existingSubject }}">{{ $existingSubject }}</option>
                                @endforeach
                            </select>
                            @error('subject') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            @else
                            <!-- New Subject Input -->
                            <input type="text" wire:model="newSubject" id="newSubject"
                                   placeholder="Enter new subject name..."
                                   class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            @error('newSubject') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">Enter a new subject name that doesn't exist in the list above.</p>
                            @endif
                        </div>

                        <!-- File Title -->
                        <div class="md:col-span-2">
                            <label for="file_title" class="block text-sm font-medium text-gray-700 mb-2">
                                File Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="file_title" id="file_title"
                                   class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            @error('file_title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- New File Number -->
                        <div>
                            <label for="new_file_no" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $fileCreationType === 'copy' ? 'Copy File Number' : 'New File Number' }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="new_file_no" id="new_file_no"
                                   {{ $fileCreationType === 'copy' ? 'readonly' : '' }}
                                   class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 @if($fileCreationType === 'copy') bg-gray-100 @endif">
                            @error('new_file_no') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            @if($fileCreationType === 'copy')
                            <p class="text-xs text-blue-600 mt-1">Auto-generated copy number</p>
                            @else
                            <p class="text-xs text-gray-500 mt-1">Auto-generated file number (can be edited)</p>
                            @endif
                            @if($newFileNoExists)
                            <p class="text-xs text-red-500 mt-1">This file number already exists!</p>
                            @endif
                        </div>

                        <!-- Old File Number -->
                        <div>
                            <label for="old_file_no" class="block text-sm font-medium text-gray-700 mb-2">
                                Old File Number (if any)
                            </label>
                            <input type="text" wire:model="old_file_no" id="old_file_no"
                                   class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            @error('old_file_no') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                Priority <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="priority" id="priority"
                                    class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                                <option value="very_urgent">Very Urgent</option>
                            </select>
                            @error('priority') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Confidentiality -->
                        <div>
                            <label for="confidentiality" class="block text-sm font-medium text-gray-700 mb-2">
                                Confidentiality <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="confidentiality" id="confidentiality"
                                    class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="public">Public</option>
                                <option value="confidential">Confidential</option>
                                <option value="secret">Secret</option>
                            </select>
                            @error('confidentiality') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Due Date (Optional)
                            </label>
                            <input type="date" wire:model="due_date" id="due_date"
                                   class="w-full bg-white border-gray-300 text-gray-900 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            @error('due_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Remarks -->
                        <div class="md:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                                Remarks
                            </label>
                            <textarea wire:model="remarks" id="remarks" rows="3"
                                      class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500"></textarea>
                            @error('remarks') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                        <a href="{{ route('registry.dashboard') }}"
                           class="w-full sm:w-auto text-center px-4 py-2.5 sm:py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150">
                            Cancel
                        </a>
                        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 sm:py-2 text-sm font-medium text-white bg-gradient-to-r from-orange-500 to-orange-600 border border-transparent rounded-lg hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 shadow-lg shadow-orange-500/25 disabled:opacity-75 disabled:cursor-not-allowed transition duration-150">
                            <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="save">{{ $fileCreationType === 'copy' ? 'Create Copy' : 'Register File' }}</span>
                            <span wire:loading wire:target="save">{{ $fileCreationType === 'copy' ? 'Creating Copy...' : 'Registering...' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
