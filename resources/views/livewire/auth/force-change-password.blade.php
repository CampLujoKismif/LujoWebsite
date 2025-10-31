<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
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

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Change Your Password')" :description="__('Your email has been verified. Please change your password to continue.')" />

    <!-- Success Message -->
    @if (session('message'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded text-center">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="updatePassword" class="flex flex-col gap-6">
        <div class="space-y-2">
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
                placeholder="{{ __('Enter your new password') }}"
                class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            />
            @error('password')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Confirm Password') }}
            </label>
            <input
                wire:model="password_confirmation"
                id="password_confirmation"
                type="password"
                required
                autocomplete="new-password"
                placeholder="{{ __('Confirm your new password') }}"
                class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            />
            @error('password_confirmation')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
            {{ __('Change Password') }}
        </button>

        <x-action-message class="text-center" on="password-updated">
            {{ __('Password changed successfully. Redirecting...') }}
        </x-action-message>
    </form>
</div>

