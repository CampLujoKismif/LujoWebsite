<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Family Management</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your family information and members</p>
        </div>

        <!-- Add Family Button -->
        <div class="mb-6">
            <button wire:click="openAddFamilyModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Family
            </button>
        </div>

        <!-- Families List -->
        @if($families->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No families found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first family.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($families as $family)
                    <div class="bg-white dark:bg-zinc-900 shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <!-- Family Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $family->name }}</h3>
                                    @if($family->owner_user_id === auth()->id())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Family Owner
                                        </span>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click="openEditFamilyModal({{ $family->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Family Details -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                @if($family->phone)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone:</span>
                                        <p class="text-sm text-gray-900 dark:text-white">{{ $family->phone }}</p>
                                    </div>
                                @endif
                                @if($family->address)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Address:</span>
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ $family->address }}<br>
                                            {{ $family->city }}, {{ $family->state }} {{ $family->zip_code }}
                                        </p>
                                    </div>
                                @endif
                                @if($family->emergency_contact_name)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Emergency Contact:</span>
                                        <p class="text-sm text-gray-900 dark:text-white">
                                            {{ $family->emergency_contact_name }}<br>
                                            {{ $family->emergency_contact_phone }}<br>
                                            {{ $family->emergency_contact_relationship }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Family Members -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Family Members ({{ $family->users->count() }})</h4>
                                    <button wire:click="openAddMemberModal({{ $family->id }})" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Add Member
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    @foreach($family->users as $member)
                                        <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->email }}</p>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucfirst($member->pivot->role_in_family) }}
                                                </span>
                                            </div>
                                            @if($family->owner_user_id === auth()->id() && $member->id !== $family->owner_user_id)
                                                <button wire:click="removeFamilyMember({{ $member->id }})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Campers -->
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Campers ({{ $family->campers->count() }})</h4>
                                    <a href="#" class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Manage Campers</a>
                                </div>
                                @if($family->campers->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($family->campers->take(3) as $camper)
                                            <div class="flex items-center p-2 bg-gray-50 dark:bg-gray-800 rounded">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $camper->full_name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Grade {{ $camper->grade }}</p>
                                                </div>
                                                @if($camper->hasMedicalAlerts())
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Medical Alert
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if($family->campers->count() > 3)
                                            <p class="text-xs text-gray-500 text-center">+{{ $family->campers->count() - 3 }} more campers</p>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">No campers added yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Add Family Modal -->
    @if($showAddFamilyModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add New Family</h3>
                    <form wire:submit.prevent="createFamily">
                        <div class="space-y-4">
                            <div>
                                <label for="familyName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Family Name</label>
                                <input type="text" wire:model="familyName" id="familyName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('familyName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                <input type="tel" wire:model="phone" id="phone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                                <input type="text" wire:model="address" id="address" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                    <input type="text" wire:model="city" id="city" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                                    <input type="text" wire:model="state" id="state" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    @error('state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="zipCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ZIP</label>
                                    <input type="text" wire:model="zipCode" id="zipCode" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    @error('zipCode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="emergencyContactName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Contact Name</label>
                                <input type="text" wire:model="emergencyContactName" id="emergencyContactName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('emergencyContactName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="emergencyContactPhone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Contact Phone</label>
                                <input type="tel" wire:model="emergencyContactPhone" id="emergencyContactPhone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('emergencyContactPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="emergencyContactRelationship" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Relationship</label>
                                <input type="text" wire:model="emergencyContactRelationship" id="emergencyContactRelationship" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('emergencyContactRelationship') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showAddFamilyModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Create Family
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Family Modal -->
    @if($showEditFamilyModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Edit Family</h3>
                    <form wire:submit.prevent="updateFamily">
                        <div class="space-y-4">
                            <div>
                                <label for="editFamilyName" class="block text-sm font-medium text-gray-700">Family Name</label>
                                <input type="text" wire:model="familyName" id="editFamilyName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('familyName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editPhone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="tel" wire:model="phone" id="editPhone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editAddress" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" wire:model="address" id="editAddress" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label for="editCity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                    <input type="text" wire:model="city" id="editCity" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="editState" class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                                    <input type="text" wire:model="state" id="editState" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    @error('state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="editZipCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ZIP</label>
                                    <input type="text" wire:model="zipCode" id="editZipCode" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    @error('zipCode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="editEmergencyContactName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Contact Name</label>
                                <input type="text" wire:model="emergencyContactName" id="editEmergencyContactName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('emergencyContactName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editEmergencyContactPhone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Contact Phone</label>
                                <input type="tel" wire:model="emergencyContactPhone" id="editEmergencyContactPhone" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('emergencyContactPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="editEmergencyContactRelationship" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Relationship</label>
                                <input type="text" wire:model="emergencyContactRelationship" id="editEmergencyContactRelationship" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                @error('emergencyContactRelationship') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showEditFamilyModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Update Family
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Add Member Modal -->
    @if($showAddMemberModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add Family Member</h3>
                    <form wire:submit.prevent="addFamilyMember">
                        <div class="space-y-4">
                            <div>
                                <label for="memberEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                                <input type="email" wire:model="memberEmail" id="memberEmail" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white" placeholder="Enter email address">
                                @error('memberEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="memberRole" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role in Family</label>
                                <select wire:model="memberRole" id="memberRole" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    <option value="parent">Parent</option>
                                    <option value="guardian">Guardian</option>
                                    <option value="emergency_contact">Emergency Contact</option>
                                </select>
                                @error('memberRole') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="memberName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Name</label>
                                <input type="text" wire:model="memberName" id="memberName" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white" placeholder="Enter display name">
                                @error('memberName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" wire:click="$set('showAddMemberModal', false)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Add Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
