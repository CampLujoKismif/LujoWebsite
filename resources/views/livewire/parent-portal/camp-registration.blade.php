<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if($registrationComplete)
                    <div class="text-center py-8">
                        <div class="mx-auto h-12 w-12 text-green-600 mb-4">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Registration Successful!</h2>
                        <p class="text-gray-600 mb-6">Your campers have been successfully registered for {{ $campInstance->camp->display_name }}.</p>
                        <div class="space-x-4">
                            <a href="{{ route('dashboard.parent.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Back to Dashboard
                            </a>
                            <a href="{{ route('dashboard.parent.payments') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Process Payments
                            </a>
                        </div>
                    </div>
                @else
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900">Register for {{ $campInstance->camp->display_name }}</h1>
                        <p class="mt-2 text-sm text-gray-600">{{ $campInstance->name }} - {{ $campInstance->start_date->format('M j, Y') }} to {{ $campInstance->end_date->format('M j, Y') }}</p>
                    </div>

                    @if($campInstance->price)
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-blue-800">
                                <strong>Camp Fee:</strong> ${{ number_format($campInstance->price, 2) }} per camper
                            </p>
                        </div>
                    @endif

                    @error('campers')
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ $message }}
                        </div>
                    @enderror

                    @if($availableCampers->isEmpty())
                        <div class="text-center py-8">
                            <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Available Campers</h3>
                            <p class="text-gray-600 mb-4">All your campers are already registered for this camp session, or you need to add campers to your family first.</p>
                            <a href="{{ route('dashboard.parent.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Back to Dashboard
                            </a>
                        </div>
                    @else
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Select Campers to Register</h2>
                            
                            <div class="space-y-3">
                                @foreach($availableCampers as $camper)
                                    <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                        <input 
                                            type="checkbox" 
                                            id="camper_{{ $camper->id }}"
                                            wire:click="toggleCamperSelection({{ $camper->id }})"
                                            {{ in_array($camper->id, $selectedCampers) ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        >
                                        <label for="camper_{{ $camper->id }}" class="ml-3 flex-1 cursor-pointer">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $camper->full_name }}</p>
                                                    <p class="text-sm text-gray-500">
                                                        Age: {{ $camper->date_of_birth ? $camper->date_of_birth->age : 'N/A' }}
                                                        @if($camper->allergies)
                                                            • Allergies: {{ $camper->allergies }}
                                                        @endif
                                                    </p>
                                                </div>
                                                @if($campInstance->price)
                                                    <div class="text-right">
                                                        <p class="text-sm font-medium text-gray-900">${{ number_format($campInstance->price, 2) }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if(!empty($selectedCampers))
                            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Registration Summary</h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    {{ count($selectedCampers) }} camper(s) selected
                                    @if($campInstance->price)
                                        • Total: ${{ number_format(count($selectedCampers) * $campInstance->price, 2) }}
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600">
                                    After registration, you'll need to complete required forms and process payment.
                                </p>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <a href="{{ route('dashboard.parent.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </a>
                            
                            <button 
                                wire:click="registerCampers"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50"
                                {{ empty($selectedCampers) ? 'disabled' : '' }}
                            >
                                <span wire:loading.remove>Register Selected Campers</span>
                                <span wire:loading>Processing...</span>
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
