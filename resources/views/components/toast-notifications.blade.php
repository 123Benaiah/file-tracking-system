<!-- Toast Notifications Container -->
<div x-data="toastNotifications()"
     x-on:toast.window="addToast(Array.isArray($event.detail) ? $event.detail[0] : $event.detail)"
     x-init="
        // Check for session flash toast on page load
        @if(session('toast'))
            addToast(@json(session('toast')));
        @endif
     "
     class="fixed z-[100] pointer-events-none top-4 inset-x-0"
     style="perspective: 1000px;">

    <!-- Toast Container -->
    <div class="flex flex-col items-center px-4 space-y-2 sm:space-y-3">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible"
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="-translate-y-4 opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transform transition ease-in duration-200"
                 x-transition:leave-start="translate-y-0 opacity-100"
                 x-transition:leave-end="-translate-y-2 opacity-0"
                 class="pointer-events-auto w-[calc(100%-2rem)] sm:w-full sm:max-w-sm mx-4 sm:mx-0"
                 :class="toast.type === 'success' ? 'toast-success' :
                         toast.type === 'error' ? 'toast-error' :
                         toast.type === 'warning' ? 'toast-warning' : 'toast-info'">

                <!-- Toast Card -->
                <div class="bg-white rounded-xl shadow-lg border overflow-hidden"
                     :class="toast.type === 'success' ? 'border-green-200' :
                             toast.type === 'error' ? 'border-red-200' :
                             toast.type === 'warning' ? 'border-yellow-200' : 'border-blue-200'">

                    <!-- Progress Bar -->
                    <div class="h-1 w-full"
                         :class="toast.type === 'success' ? 'bg-green-100' :
                                 toast.type === 'error' ? 'bg-red-100' :
                                 toast.type === 'warning' ? 'bg-yellow-100' : 'bg-blue-100'">
                        <div class="h-full transition-all ease-linear"
                             :class="toast.type === 'success' ? 'bg-green-500' :
                                     toast.type === 'error' ? 'bg-red-500' :
                                     toast.type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'"
                             :style="`width: ${toast.progress}%; transition-duration: 100ms;`">
                        </div>
                    </div>

                    <!-- Toast Content -->
                    <div class="p-3 sm:p-4">
                        <div class="flex items-start">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <!-- Success Icon -->
                                <template x-if="toast.type === 'success'">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </template>

                                <!-- Error Icon -->
                                <template x-if="toast.type === 'error'">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                </template>

                                <!-- Warning Icon -->
                                <template x-if="toast.type === 'warning'">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                </template>

                                <!-- Info Icon -->
                                <template x-if="toast.type === 'info'">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </template>
                            </div>

                            <!-- Text Content -->
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm sm:text-base font-semibold text-gray-900 truncate" x-text="toast.title"></p>
                                <p class="mt-0.5 text-xs sm:text-sm text-gray-600 line-clamp-2" x-text="toast.message" x-show="toast.message"></p>
                            </div>

                            <!-- Close Button -->
                            <div class="ml-2 flex-shrink-0">
                                <button @click="removeToast(toast.id)"
                                        class="inline-flex rounded-lg p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                                    <span class="sr-only">Dismiss</span>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Action Button (Optional) -->
                        <template x-if="toast.action">
                            <div class="mt-3 flex">
                                <button @click="toast.action.callback(); removeToast(toast.id)"
                                        class="text-sm font-medium rounded-lg px-3 py-1.5 transition-colors"
                                        :class="toast.type === 'success' ? 'text-green-600 hover:bg-green-50' :
                                                toast.type === 'error' ? 'text-red-600 hover:bg-red-50' :
                                                toast.type === 'warning' ? 'text-yellow-600 hover:bg-yellow-50' : 'text-blue-600 hover:bg-blue-50'"
                                        x-text="toast.action.label">
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function toastNotifications() {
    return {
        toasts: [],
        position: 'top',
        maxToasts: 5,

        addToast(options) {
            // Handle if options is an array (Livewire 3 dispatch format)
            if (Array.isArray(options)) {
                options = options[0] || {};
            }

            // Handle if options is not an object
            if (typeof options !== 'object' || options === null) {
                console.warn('Toast: Invalid options received', options);
                options = {};
            }

            const id = Date.now() + Math.random();
            const duration = options.duration || 6000;

            const toast = {
                id,
                type: options.type || 'info',
                title: options.title || this.getDefaultTitle(options.type),
                message: options.message || '',
                action: options.action || null,
                duration,
                progress: 100,
                visible: true,
                interval: null
            };

            // Limit number of toasts
            if (this.toasts.length >= this.maxToasts) {
                this.removeToast(this.toasts[0].id);
            }

            this.toasts.push(toast);

            // Start progress countdown
            const startTime = Date.now();
            toast.interval = setInterval(() => {
                const elapsed = Date.now() - startTime;
                toast.progress = Math.max(0, 100 - (elapsed / duration * 100));

                if (toast.progress <= 0) {
                    this.removeToast(toast.id);
                }
            }, 100);
        },

        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index > -1) {
                const toast = this.toasts[index];
                if (toast.interval) {
                    clearInterval(toast.interval);
                }
                toast.visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            }
        },

        getDefaultTitle(type) {
            switch(type) {
                case 'success': return 'Success';
                case 'error': return 'Error';
                case 'warning': return 'Warning';
                default: return 'Notice';
            }
        }
    };
}
</script>
