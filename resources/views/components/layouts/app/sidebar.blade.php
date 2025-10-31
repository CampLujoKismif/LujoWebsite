<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="h-screen border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard.home') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <!-- Main Dashboard -->
                <flux:navlist.item icon="home" :href="route('dashboard.home')" :current="request()->routeIs('dashboard.home')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navlist.item>

                <!-- System Admin Section -->
                @role('system-admin')
                    <flux:navlist.group :heading="__('System Administration')" expandable="true" expanded="true" class="grid">
                        <flux:navlist.item icon="shield-check" :href="route('dashboard.admin.index')" :current="request()->routeIs('dashboard.admin.index')" wire:navigate>
                            {{ __('Admin Dashboard') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('dashboard.admin.users')" :current="request()->routeIs('dashboard.admin.users')" wire:navigate>
                            {{ __('User Management') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="user-group" :href="route('dashboard.admin.roles')" :current="request()->routeIs('dashboard.admin.roles')" wire:navigate>
                            {{ __('Role Management') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="building-storefront" :href="route('dashboard.admin.camps')" :current="request()->routeIs('dashboard.admin.camps')" wire:navigate>
                            {{ __('Camp Management') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document-list" :href="route('dashboard.admin.form-templates')" :current="request()->routeIs('dashboard.admin.form-templates')" wire:navigate>
                            {{ __('Form Templates') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document" :href="route('dashboard.admin.form-responses')" :current="request()->routeIs('dashboard.admin.form-responses')" wire:navigate>
                            {{ __('Form Responses') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('dashboard.admin.enrollments')" :current="request()->routeIs('dashboard.admin.enrollments')" wire:navigate>
                            {{ __('Manage Enrollments') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="building-library" :href="route('dashboard.admin.church-congregations')" :current="request()->routeIs('dashboard.admin.church-congregations')" wire:navigate>
                            {{ __('Church Congregations') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="exclamation-triangle" :href="route('dashboard.admin.error-logs')" :current="request()->routeIs('dashboard.admin.error-logs')" wire:navigate>
                            {{ __('Error Logs') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="envelope" :href="route('dashboard.admin.email-templates')" :current="request()->routeIs('dashboard.admin.email-templates')" wire:navigate>
                            {{ __('Email Templates') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="building-office" :href="route('dashboard.admin.rentals')" :current="request()->routeIs('dashboard.admin.rentals')" wire:navigate>
                            {{ __('Rental Management') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="arrow-top-right-on-square" :href="route('dashboard.admin.url-forwards')" :current="request()->routeIs('dashboard.admin.url-forwards')" wire:navigate>
                            {{ __('URL Forwards') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <!-- Back to Public Site -->
                    <flux:navlist.item icon="globe-alt" :href="route('home')" class="mt-4" target="_blank">
                        {{ __('Back to Public Site') }}
                    </flux:navlist.item>
                @endrole

                <!-- Rental Admin Section -->
                @role('rental-admin')
                    <flux:navlist.group :heading="__('Rental Management')" expandable="true" expanded="true" class="grid">
                        <flux:navlist.item icon="building-office" :href="route('dashboard.rental-admin.index')" :current="request()->routeIs('dashboard.rental-admin.*')" wire:navigate>
                            {{ __('Rental Dashboard') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endrole

                <!-- Camp Manager Section -->
                @role('camp-manager')
                    <flux:navlist.group :heading="__('Camp Management')" expandable="true" expanded="true" class="grid">
                        <flux:navlist.item icon="building-storefront" :href="route('dashboard.manager.index')" :current="request()->routeIs('dashboard.manager.index')" wire:navigate>
                            {{ __('Manager Dashboard') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="calendar" :href="route('dashboard.manager.sessions')" :current="request()->routeIs('dashboard.manager.sessions')" wire:navigate>
                            {{ __('Session Management') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('dashboard.manager.enrollments')" :current="request()->routeIs('dashboard.manager.enrollments')" wire:navigate>
                            {{ __('Manage Enrollments') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document-list" :href="route('dashboard.admin.form-templates')" :current="request()->routeIs('dashboard.admin.form-templates')" wire:navigate>
                            {{ __('Form Templates') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document" :href="route('dashboard.admin.form-responses')" :current="request()->routeIs('dashboard.admin.form-responses')" wire:navigate>
                            {{ __('Form Responses') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endrole

                <!-- Parent Portal Section -->
                @role('parent')
                    <flux:navlist.group :heading="__('Parent Portal')" expandable="true" expanded="true" class="grid">
                        <flux:navlist.item icon="home" :href="route('dashboard.parent.index')" :current="request()->routeIs('dashboard.parent.index')" wire:navigate>
                            {{ __('Parent Dashboard') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="user-group" :href="route('dashboard.parent.families')" :current="request()->routeIs('dashboard.parent.families')" wire:navigate>
                            {{ __('Family Management') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('dashboard.parent.campers')" :current="request()->routeIs('dashboard.parent.campers')" wire:navigate>
                            {{ __('Camper Management') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document-list" :href="route('dashboard.parent.enrollments')" :current="request()->routeIs('dashboard.parent.enrollments')" wire:navigate>
                            {{ __('Enrollments') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document" :href="route('dashboard.parent.forms')" :current="request()->routeIs('dashboard.parent.forms')" wire:navigate>
                            {{ __('Fill Forms') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="heart" :href="route('dashboard.parent.medical-records')" :current="request()->routeIs('dashboard.parent.medical-records')" wire:navigate>
                            {{ __('Medical Records') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endrole
            </flux:navlist>

            <flux:spacer />

            <!-- Theme Selector -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                    <span class="flex items-center gap-2">
                        <!-- Light icon -->
                        <svg class="w-4 h-4 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Dark icon -->
                        <svg class="w-4 h-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <span class="text-xs text-zinc-700 dark:text-zinc-300">Theme</span>
                    </span>
                    <svg class="w-4 h-4 text-zinc-500" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700 py-1 z-50">
                    <button @click="$flux.appearance = 'light'; window.setTheme('light'); open = false" 
                            class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Light
                    </button>
                    <button @click="$flux.appearance = 'dark'; window.setTheme('dark'); open = false"
                            class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        Dark
                    </button>
                    <button @click="$flux.appearance = 'system'; window.setTheme('system'); open = false"
                            class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        System
                    </button>
                </div>
            </div>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px] bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal text-zinc-900 dark:text-zinc-100">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold text-zinc-900 dark:text-zinc-100">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs text-zinc-600 dark:text-zinc-400">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate class="text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700">{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <!-- Mobile Theme Selector -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="p-2 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                    <svg class="w-5 h-5 text-zinc-700 dark:text-zinc-300 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="w-5 h-5 text-zinc-300 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute bottom-full right-0 mb-2 w-40 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700 py-1 z-50">
                    <button @click="$flux.appearance = 'light'; window.setTheme('light'); open = false"
                            class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Light
                    </button>
                    <button @click="$flux.appearance = 'dark'; window.setTheme('dark'); open = false"
                            class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        Dark
                    </button>
                    <button @click="$flux.appearance = 'system'; window.setTheme('system'); open = false"
                            class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        System
                    </button>
                </div>
            </div>

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu class="bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal text-zinc-900 dark:text-zinc-100">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold text-zinc-900 dark:text-zinc-100">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs text-zinc-600 dark:text-zinc-400">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate class="text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700">{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-zinc-900 dark:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @vite('resources/js/app.js')
        @fluxScripts
        
        <!-- Ensure theme is applied after all scripts load AND on Livewire navigation -->
        <script>
            function enforceTheme() {
                const theme = localStorage.getItem('flux.appearance') || 'light';
                console.log('Enforcing theme:', theme);
                
                if (theme === 'dark') {
                    document.body.classList.add('dark');
                } else if (theme === 'light') {
                    document.body.classList.remove('dark');
                } else if (theme === 'system') {
                    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.body.classList.toggle('dark', isDark);
                }
                
                console.log('Body has dark class:', document.body.classList.contains('dark'));
            }
            
            // Apply on initial page load
            document.addEventListener('DOMContentLoaded', enforceTheme);
            
            // CRITICAL: Apply on Livewire navigation (admin pages use Livewire)
            document.addEventListener('livewire:navigated', enforceTheme);
            
            // Also apply after any Livewire component loads
            document.addEventListener('livewire:load', enforceTheme);
            document.addEventListener('livewire:init', enforceTheme);
            
            // Listen for storage events (cross-tab sync and settings page changes)
            window.addEventListener('storage', function(e) {
                if (e.key === 'flux.appearance') {
                    console.log('Storage event: theme changed to', e.newValue);
                    enforceTheme();
                }
            });
            
            // Poll localStorage every 500ms to catch same-tab changes (fallback)
            setInterval(function() {
                const currentTheme = localStorage.getItem('flux.appearance') || 'light';
                const hasLight = !document.body.classList.contains('dark');
                const shouldBeLight = (currentTheme === 'light') || (currentTheme === 'system' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
                
                if (hasLight !== shouldBeLight) {
                    console.log('Theme mismatch detected, enforcing...');
                    enforceTheme();
                }
            }, 500);
        </script>
    </body>
</html>
