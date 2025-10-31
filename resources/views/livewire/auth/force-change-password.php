<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('password', 'password_confirmation');

            throw $e;
        }

        $user = Auth::user();
        
        $user->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        $this->reset('password', 'password_confirmation');

        $this->dispatch('password-updated');
        
        // Redirect to dashboard after password change
        $this->redirect(route('dashboard.home', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                {{ __('Change Your Password') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                {{ __('Your email has been verified. Please change your password to continue.') }}
            </p>
        </div>

        <form wire:submit="updatePassword" class="mt-8 space-y-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <div class="space-y-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('New Password') }}
                    </label>
                    <input
                        wire:model="password"
                        id="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        autofocus
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm bg-white dark:bg-gray-700"
                        placeholder="{{ __('Enter your new password') }}"
                    />
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Confirm Password') }}
                    </label>
                    <input
                        wire:model="password_confirmation"
                        id="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm bg-white dark:bg-gray-700"
                        placeholder="{{ __('Confirm your new password') }}"
                    />
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    {{ __('Change Password') }}
                </button>
            </div>

            <x-action-message class="text-center" on="password-updated">
                {{ __('Password changed successfully. Redirecting...') }}
            </x-action-message>
        </form>
    </div>
</div>

