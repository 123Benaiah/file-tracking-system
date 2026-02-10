<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Manage File Movements</h2>
                        <p class="text-gray-600 mt-1">{{ $file->new_file_no }} - {{ $file->subject }}</p>
                    </div>
                    <a href="{{ route('files.show', $file) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100">
                        Back to File
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by sender or receiver..."
                               class="border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                        <select wire:model.live="statusFilter"
                                class="border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                            <option value="">All Statuses</option>
                            <option value="sent">Sent</option>
                            <option value="delivered">Delivered</option>
                            <option value="received">Received</option>
                            <option value="acknowledged">Acknowledged</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <select wire:model.live="perPage"
                                class="border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 text-sm">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                    @if($this->selectedCount > 0)
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">{{ $this->selectedCount }} selected</span>
                        <button wire:click="confirmDeleteSelected"
                                class="inline-flex items-center px-3 py-2 border border-red-600 text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Selected
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">All Movements ({{ $movements->total() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-3 text-left">
                                    <input type="checkbox" wire:model.live="selectAll"
                                           class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded cursor-pointer">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">To</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sent At</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received At</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($movements as $movement)
                            <tr>
                                <td class="px-2 py-4 whitespace-nowrap">
                                    <input type="checkbox" value="{{ $movement->id }}" wire:model.live="selectedMovements"
                                           class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded cursor-pointer">
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="font-medium text-gray-900">{{ $movement->sender->name }}</div>
                                    <div class="text-gray-500">{{ $movement->sender->employee_number }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="font-medium text-gray-900">{{ $movement->intendedReceiver->name }}</div>
                                    <div class="text-gray-500">{{ $movement->intendedReceiver->employee_number }}</div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($movement->movement_status === 'received') bg-green-100 text-green-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ ucfirst($movement->movement_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900">{{ $movement->sent_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-4 text-sm text-gray-900">{{ $movement->received_at?->format('d M Y H:i') ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-sm font-medium space-x-2">
                                    <button wire:click="openEditModal({{ $movement->id }})" class="text-gray-800 hover:text-gray-900">Edit</button>
                                    <button wire:click="deleteMovement({{ $movement->id }})" wire:confirm="Are you sure you want to delete this movement?" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">No movements found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t">{{ $movements->links(data: ['scrollTo' => false]) }}</div>
            </div>
        </div>
    </div>

    @if($showEditModal)
    <div class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            <div class="relative bg-white rounded-lg max-w-2xl w-full p-6">
                <h3 class="text-lg font-medium mb-4">Edit Movement</h3>
                <form wire:submit.prevent="updateMovement">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sender *</label>
                            <select wire:model="sender_emp_no" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($employees as $emp)
                                <option value="{{ $emp->employee_number }}">{{ $emp->name }} ({{ $emp->employee_number }})</option>
                                @endforeach
                            </select>
                            @error('sender_emp_no') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Intended Receiver *</label>
                            <select wire:model="intended_receiver_emp_no" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($employees as $emp)
                                <option value="{{ $emp->employee_number }}">{{ $emp->name }} ({{ $emp->employee_number }})</option>
                                @endforeach
                            </select>
                            @error('intended_receiver_emp_no') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Actual Receiver</label>
                            <select wire:model="actual_receiver_emp_no" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- None --</option>
                                @foreach($employees as $emp)
                                <option value="{{ $emp->employee_number }}">{{ $emp->name }} ({{ $emp->employee_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status *</label>
                            <select wire:model="movement_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="sent">Sent</option>
                                <option value="delivered">Delivered</option>
                                <option value="received">Received</option>
                                <option value="acknowledged">Acknowledged</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sent At *</label>
                            <input type="datetime-local" wire:model="sent_at" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('sent_at') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Received At</label>
                            <input type="datetime-local" wire:model="received_at" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Sender Comments</label>
                            <textarea wire:model="sender_comments" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Receiver Comments</label>
                            <textarea wire:model="receiver_comments" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Update Movement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Bulk Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="inline-block align-middle h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xs sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Movements</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete {{ $this->selectedCount }} movement(s)? This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteSelected" wire:loading.attr="disabled"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <svg wire:loading wire:target="deleteSelected" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Delete
                    </button>
                    <button wire:click="$set('showDeleteModal', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
