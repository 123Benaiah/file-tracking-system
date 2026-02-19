<div class="py-4 sm:py-8" x-data="{ activeTab: 'profile' }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">My Profile</h1>
                    <p class="mt-1 text-sm sm:text-base text-gray-500">Manage your account settings and security preferences</p>
                </div>
                <div class="hidden sm:flex items-center">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center shadow-lg shadow-orange-500/20">
                        <x-icons.user class="w-6 h-6 text-white" />
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Profile Card - Left Sidebar -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden lg:sticky lg:top-6">
                    <!-- Profile Header with Gradient -->
                    <div class="relative px-6 pt-8 pb-16 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
                        <!-- Decorative Pattern -->
                        <div class="absolute inset-0 opacity-10">
                            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                                <defs>
                                    <pattern id="profile-grid" width="10" height="10" patternUnits="userSpaceOnUse">
                                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#profile-grid)"/>
                            </svg>
                        </div>

                        <div class="relative flex flex-col items-center">
                            <!-- Avatar -->
                            <div class="relative">
                                <div class="h-24 w-24 sm:h-28 sm:w-28 rounded-full bg-white flex items-center justify-center shadow-2xl ring-4 ring-white/30">
                                    <span class="text-3xl sm:text-4xl font-bold bg-gradient-to-br from-orange-500 to-amber-600 bg-clip-text text-transparent">
                                        {{ strtoupper(substr($this->name, 0, 2)) }}
                                    </span>
                                </div>
                                <!-- Online Indicator -->
                                <div class="absolute bottom-1 right-1 w-5 h-5 bg-orange-500 border-3 border-white rounded-full shadow-lg"></div>
                            </div>

                            <!-- Name & Role -->
                            <h2 class="mt-4 text-xl sm:text-2xl font-bold text-white text-center">{{ $this->formalName }}</h2>
                            <span class="mt-2 inline-flex items-center px-4 py-1.5 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium text-white border border-white/20 max-w-[200px] justify-center">
                                <x-icons.shield-check class="w-4 h-4 mr-1.5 flex-shrink-0" />
                                <span class="truncate">{{ $this->role }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Profile Details Toggle -->
                    <div class="px-6 py-5 -mt-8 relative" x-data="{ showDetails: false }">
                        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                            <!-- Toggle Button -->
                            <button @click="showDetails = !showDetails" class="w-full px-4 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-semibold text-gray-700">Profile Details</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200" :class="showDetails ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Expandable Details -->
                            <div x-show="showDetails" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="px-4 pb-4 border-t border-gray-100">
                                <div class="grid grid-cols-2 gap-x-6 gap-y-4 pt-3">
                                    <!-- Employee ID -->
                                    <div class="flex items-center gap-3 py-2 border-b border-gray-100 group">
                                        <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center group-hover:bg-orange-100 transition-colors flex-shrink-0">
                                            <x-icons.user class="w-5 h-5 text-orange-500" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs text-gray-500">Employee ID</p>
                                            <p class="text-sm font-semibold text-gray-900 truncate" title="{{ $this->employee_number }}">{{ $this->employee_number }}</p>
                                        </div>
                                    </div>

                                    <!-- Department -->
                                    <div class="flex items-center gap-3 py-2 border-b border-gray-100 group">
                                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors flex-shrink-0">
                                            <x-icons.building-office class="w-5 h-5 text-blue-500" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs text-gray-500">Department</p>
                                            <p class="text-sm font-semibold text-gray-900 truncate" title="{{ $this->department }}">{{ $this->department }}</p>
                                        </div>
                                    </div>

                                    <!-- Position -->
                                    <div class="flex items-center gap-3 py-2 border-b border-gray-100 group">
                                        <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition-colors flex-shrink-0">
                                            <x-icons.briefcase class="w-5 h-5 text-amber-500" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs text-gray-500">Position</p>
                                            <p class="text-sm font-semibold text-gray-900 truncate" title="{{ $this->position }}">{{ $this->position }}</p>
                                        </div>
                                    </div>

                                    <!-- Unit -->
                                    <div class="flex items-center gap-3 py-2 border-b border-gray-100 group">
                                        <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center group-hover:bg-purple-100 transition-colors flex-shrink-0">
                                            <x-icons.map-pin class="w-5 h-5 text-purple-500" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs text-gray-500">Unit</p>
                                            <p class="text-sm font-semibold text-gray-900 truncate" title="{{ $this->unit ?? 'N/A' }}">{{ $this->unit ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="px-6 pb-6">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-4 text-center border border-orange-100">
                                <p class="text-2xl font-bold text-orange-600">Active</p>
                                <p class="text-xs text-gray-500 mt-1">Account Status</p>
                            </div>
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 text-center border border-blue-100">
                                <p class="text-2xl font-bold text-blue-600">Verified</p>
                                <p class="text-xs text-gray-500 mt-1">Email Status</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Right Side -->
            <div class="lg:col-span-7">
                <!-- Tabs Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- Tab Navigation -->
                    <div class="border-b border-gray-200 bg-gray-50/50">
                        <nav class="flex" aria-label="Tabs">
                            <button @click="activeTab = 'profile'"
                                    :class="activeTab === 'profile'
                                        ? 'border-orange-500 text-orange-600 bg-white'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                                    class="flex-1 py-4 px-4 text-center border-b-2 font-medium text-sm transition-all duration-200 relative">
                                <div class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2">
                                    <div :class="activeTab === 'profile' ? 'bg-orange-100' : 'bg-gray-100'"
                                         class="p-2 rounded-lg transition-colors">
                                        <x-icons.user :class="activeTab === 'profile' ? 'text-orange-500' : 'text-gray-400'" class="w-5 h-5" />
                                    </div>
                                    <span class="hidden sm:inline">Profile Info</span>
                                    <span class="sm:hidden text-xs">Profile</span>
                                </div>
                            </button>
                            <button @click="activeTab = 'password'"
                                    :class="activeTab === 'password'
                                        ? 'border-orange-500 text-orange-600 bg-white'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                                    class="flex-1 py-4 px-4 text-center border-b-2 font-medium text-sm transition-all duration-200 relative">
                                <div class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2">
                                    <div :class="activeTab === 'password' ? 'bg-orange-100' : 'bg-gray-100'"
                                         class="p-2 rounded-lg transition-colors">
                                        <x-icons.key :class="activeTab === 'password' ? 'text-orange-500' : 'text-gray-400'" class="w-5 h-5" />
                                    </div>
                                    <span class="hidden sm:inline">Password</span>
                                    <span class="sm:hidden text-xs">Password</span>
                                </div>
                            </button>
                            <button @click="activeTab = 'security'"
                                    :class="activeTab === 'security'
                                        ? 'border-purple-500 text-purple-600 bg-white'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100'"
                                    class="flex-1 py-4 px-4 text-center border-b-2 font-medium text-sm transition-all duration-200 relative">
                                <div class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2">
                                    <div :class="activeTab === 'security' ? 'bg-purple-100' : 'bg-gray-100'"
                                         class="p-2 rounded-lg transition-colors">
                                        <x-icons.shield-check :class="activeTab === 'security' ? 'text-purple-500' : 'text-gray-400'" class="w-5 h-5" />
                                    </div>
                                    <span class="hidden sm:inline">Security</span>
                                    <span class="sm:hidden text-xs">Security</span>
                                </div>
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-5 sm:p-8">
                        <!-- Profile Tab -->
                        <div x-show="activeTab === 'profile'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0">
                            <div class="flex items-center mb-6">
                                <div class="p-3 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl shadow-lg shadow-orange-500/20">
                                    <x-icons.user class="w-6 h-6 text-white" />
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Profile Information</h3>
                                    <p class="text-sm text-gray-500">Update your personal details and contact info</p>
                                </div>
                            </div>

                            <form wire:submit.prevent="updateProfile" class="space-y-5">
                                <div class="grid grid-cols-1 gap-5">
                                    <!-- Full Name -->
                                    <div class="group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icons.user class="w-5 h-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" />
                                            </div>
                                            <input type="text" wire:model="name" required
                                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                                   placeholder="Enter your full name">
                                        </div>
                                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Email Address -->
                                    <div class="group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icons.envelope class="w-5 h-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" />
                                            </div>
                                            <input type="email" wire:model="email" required
                                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                                   placeholder="Enter your email">
                                        </div>
                                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Read-only Fields -->
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Employee ID</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <x-icons.user class="w-5 h-5 text-gray-400" />
                                                </div>
                                                <input type="text" value="{{ $this->employee_number }}" disabled
                                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gender</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <input type="text" value="{{ $this->gender }}" disabled
                                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <x-icons.shield-check class="w-5 h-5 text-gray-400" />
                                                </div>
                                                <input type="text" value="{{ $this->role }}" disabled
                                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 flex flex-col sm:flex-row sm:justify-end gap-3">
                                    <button type="submit" wire:loading.attr="disabled"
                                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-amber-600 focus:ring-4 focus:ring-orange-500/25 transition-all duration-200 shadow-lg shadow-orange-500/25 disabled:opacity-75 disabled:cursor-not-allowed">
                                        <x-icons.check wire:loading.remove wire:target="updateProfile" class="w-5 h-5 mr-2" />
                                        <svg wire:loading wire:target="updateProfile" class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                                        <span wire:loading wire:target="updateProfile">Saving...</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Password Tab -->
                        <div x-show="activeTab === 'password'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             style="display: none;">
                            <div class="flex items-center mb-6">
                                <div class="p-3 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl shadow-lg shadow-orange-500/20">
                                    <x-icons.key class="w-6 h-6 text-white" />
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Change Password</h3>
                                    <p class="text-sm text-gray-500">Keep your account secure with a strong password</p>
                                </div>
                            </div>

                            <form wire:submit.prevent="updatePassword" class="space-y-5">
                                <!-- Current Password -->
                                <div class="group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <x-icons.lock-closed class="w-5 h-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" />
                                        </div>
                                        <input type="password" wire:model="current_password" required
                                               class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                               placeholder="Enter current password">
                                    </div>
                                    @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- New Password Fields -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icons.key class="w-5 h-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" />
                                            </div>
                                            <input type="password" wire:model="new_password" required
                                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                                   placeholder="Enter new password">
                                        </div>
                                        @error('new_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        <p class="text-xs text-gray-400 mt-1">Minimum 6 characters</p>
                                    </div>
                                    <div class="group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icons.check class="w-5 h-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" />
                                            </div>
                                            <input type="password" wire:model="new_password_confirmation" required
                                                   class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all duration-200 text-gray-900 placeholder-gray-400"
                                                   placeholder="Confirm new password">
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Tips -->
                                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-4 border border-amber-100">
                                    <h4 class="text-sm font-semibold text-amber-800 mb-2 flex items-center">
                                        <x-icons.information-circle class="w-4 h-4 mr-2" />
                                        Password Tips
                                    </h4>
                                    <ul class="text-xs text-amber-700 space-y-1">
                                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>Use at least 6 characters</li>
                                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>Mix letters, numbers, and symbols</li>
                                        <li class="flex items-center"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-2"></span>Avoid personal information</li>
                                    </ul>
                                </div>

                                <div class="pt-4 flex flex-col sm:flex-row sm:justify-end gap-3">
                                    <button type="submit" wire:loading.attr="disabled"
                                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-amber-600 focus:ring-4 focus:ring-orange-500/25 transition-all duration-200 shadow-lg shadow-orange-500/25 disabled:opacity-75 disabled:cursor-not-allowed">
                                        <x-icons.key wire:loading.remove wire:target="updatePassword" class="w-5 h-5 mr-2" />
                                        <svg wire:loading wire:target="updatePassword" class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                                        <span wire:loading wire:target="updatePassword">Updating...</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div x-show="activeTab === 'security'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             style="display: none;">
                            <div class="flex items-center mb-6">
                                <div class="p-3 bg-gradient-to-br from-purple-500 to-violet-500 rounded-xl shadow-lg shadow-purple-500/20">
                                    <x-icons.shield-check class="w-6 h-6 text-white" />
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Security Best Practices</h3>
                                    <p class="text-sm text-gray-500">Follow these tips to keep your account safe</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Strong Password -->
                                <div class="group p-5 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border border-orange-100 hover:shadow-md hover:border-orange-200 transition-all duration-300">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 p-2.5 bg-orange-100 rounded-xl group-hover:bg-orange-200 transition-colors">
                                            <x-icons.check-circle class="w-6 h-6 text-orange-600" />
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="font-semibold text-orange-800">Strong Password</h4>
                                            <p class="text-sm text-orange-700 mt-1">Use at least 6 characters with a mix of letters, numbers, and symbols for maximum security.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Keep It Private -->
                                <div class="group p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 hover:shadow-md hover:border-blue-200 transition-all duration-300">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 p-2.5 bg-blue-100 rounded-xl group-hover:bg-blue-200 transition-colors">
                                            <x-icons.no-symbol class="w-6 h-6 text-blue-600" />
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="font-semibold text-blue-800">Keep It Private</h4>
                                            <p class="text-sm text-blue-700 mt-1">Never share your password with anyone, including IT staff or colleagues.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Regular Updates -->
                                <div class="group p-5 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border border-orange-100 hover:shadow-md hover:border-orange-200 transition-all duration-300">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 p-2.5 bg-orange-100 rounded-xl group-hover:bg-orange-200 transition-colors">
                                            <x-icons.arrow-path class="w-6 h-6 text-orange-600" />
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="font-semibold text-orange-800">Regular Updates</h4>
                                            <p class="text-sm text-orange-700 mt-1">Change your password periodically for better protection against threats.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Always Log Out -->
                                <div class="group p-5 bg-gradient-to-br from-red-50 to-rose-50 rounded-xl border border-red-100 hover:shadow-md hover:border-red-200 transition-all duration-300">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 p-2.5 bg-red-100 rounded-xl group-hover:bg-red-200 transition-colors">
                                            <x-icons.arrow-right-on-rectangle class="w-6 h-6 text-red-600" />
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="font-semibold text-red-800">Always Log Out</h4>
                                            <p class="text-sm text-red-700 mt-1">Log out when done, especially on shared or public computers.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Security Info -->
                            <div class="mt-6 p-5 bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl border border-purple-100">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 p-2 bg-purple-100 rounded-lg">
                                        <x-icons.information-circle class="w-5 h-5 text-purple-600" />
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-semibold text-purple-800">Need Help?</h4>
                                        <p class="text-sm text-purple-700 mt-1">If you suspect your account has been compromised, contact your system administrator immediately to secure your account.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
