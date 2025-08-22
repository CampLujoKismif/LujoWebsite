<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $user_type = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'string', 'in:parent,camper'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // Assign the appropriate role based on user type
        $roleName = $validated['user_type'] === 'parent' ? 'parent' : 'camper';
        $role = Role::where('name', $roleName)->first();
        
        if ($role) {
            $user->assignRole($role);
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirectIntended(route('dashboard.home', absolute: false), navigate: true);
    }
}; ?>

<style>
    /* Custom radio button styles */
    input[type="radio"]:checked + span {
        border-color: rgb(59 130 246) !important;
        background-color: rgb(239 246 255) !important;
        box-shadow: 0 0 0 3px rgb(59 130 246 / 0.1);
    }
    
    .dark input[type="radio"]:checked + span {
        background-color: rgb(30 58 138 / 0.3) !important;
        border-color: rgb(96 165 250) !important;
        box-shadow: 0 0 0 3px rgb(96 165 250 / 0.2);
    }
    
    input[type="radio"]:checked + span .border-transparent {
        border-color: rgb(59 130 246) !important;
    }
    
    /* Show selected badge when checked */
    input[type="radio"]:checked ~ div {
        opacity: 1 !important;
    }
    
    /* Hover effects */
    label:hover .border-gray-300 {
        border-color: rgb(59 130 246 / 0.5);
    }
    
    .dark label:hover .border-gray-600 {
        border-color: rgb(96 165 250 / 0.5);
    }
    
    /* Selection indicator */
    input[type="radio"]:checked + span::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border: 2px solid rgb(59 130 246);
        border-radius: 0.5rem;
        pointer-events: none;
    }
    
    .dark input[type="radio"]:checked + span::before {
        border-color: rgb(96 165 250);
    }
</style>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Join Camp LUJO-KISMIF')" :description="__('Create your account to access the camp dashboard')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Full Name') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input
                    wire:model="name"
                    id="name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your full name"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
            </div>
            @error('name')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Email address') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input
                    wire:model="email"
                    id="email"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="Enter your email"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
            </div>
            @error('email')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- User Type Selection -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('I am a...') }}
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-4 shadow-sm focus:outline-none hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                    <input
                        wire:model="user_type"
                        type="radio"
                        name="user_type"
                        value="parent"
                        class="sr-only"
                        aria-labelledby="parent-label"
                        aria-describedby="parent-description"
                    />
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span id="parent-label" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Parent/Guardian
                            </span>
                            <span id="parent-description" class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Register children for camp
                            </span>
                        </span>
                    </span>
                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent" aria-hidden="true"></span>
                    <!-- Selected indicator -->
                    <div class="absolute top-2 right-2 opacity-0 transition-opacity duration-200" style="opacity: 0;">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Selected
                        </span>
                    </div>
                </label>

                <label class="relative flex cursor-pointer rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-4 shadow-sm focus:outline-none hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                    <input
                        wire:model="user_type"
                        type="radio"
                        name="user_type"
                        value="camper"
                        class="sr-only"
                        aria-labelledby="camper-label"
                        aria-describedby="camper-description"
                    />
                    <span class="flex flex-1">
                        <span class="flex flex-col">
                            <span id="camper-label" class="block text-sm font-medium text-gray-900 dark:text-white">
                                Camper
                            </span>
                            <span id="camper-description" class="mt-1 flex items-center text-sm text-gray-500 dark:text-gray-400">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                View my camps
                            </span>
                        </span>
                    </span>
                    <span class="pointer-events-none absolute -inset-px rounded-lg border-2 border-transparent" aria-hidden="true"></span>
                    <!-- Selected indicator -->
                    <div class="absolute top-2 right-2 opacity-0 transition-opacity duration-200" style="opacity: 0;">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Selected
                        </span>
                    </div>
                </label>
            </div>
            @error('user_type')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Password') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input
                    wire:model="password"
                    id="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Create a password"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
            </div>
            @error('password')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Confirm password') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input
                    wire:model="password_confirmation"
                    id="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                />
            </div>
        </div>

        <!-- Register Button -->
        <button
            type="submit"
            class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        >
            <div class="flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                {{ __('Create Account') }}
            </div>
        </button>
    </form>

    <!-- Divider -->
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">or</span>
        </div>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">
                {{ __('Sign in') }}
            </a>
        </p>
    </div>

    <!-- Camp Info -->
    <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-green-800 dark:text-green-200">
                <strong>Welcome!</strong> Join our community of camp staff and volunteers
            </p>
        </div>
    </div>
</div>
