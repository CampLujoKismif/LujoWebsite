<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
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
                    <flux:navlist.group :heading="__('System Administration')" class="grid">
                        <flux:navlist.item icon="shield-check" :href="route('dashboard.admin.index')" :current="request()->routeIs('dashboard.admin.index')" wire:navigate>
                            {{ __('Admin Dashboard') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('dashboard.admin.users')" :current="request()->routeIs('dashboard.admin.users')" wire:navigate>
                            {{ __('User Management') }}
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
                        <flux:navlist.item icon="building-office" :href="route('dashboard.admin.rentals')" :current="request()->routeIs('dashboard.admin.rentals')" wire:navigate>
                            {{ __('Rental Management') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endrole

                <!-- Camp Manager Section -->
                @role('camp-manager')
                    <flux:navlist.group :heading="__('Camp Management')" class="grid">
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
                    <flux:navlist.group :heading="__('Parent Portal')" class="grid">
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

                <!-- General Camp Management (for all roles) -->
                <flux:navlist.group :heading="__('Camp Management')" class="grid">
                    <flux:navlist.item icon="calendar" :href="route('dashboard.home')" :current="request()->routeIs('dashboard.*')" wire:navigate>
                        {{ __('Camp Sessions') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="chart-bar" :href="route('dashboard.home')" :current="request()->routeIs('dashboard.*')" wire:navigate>
                        {{ __('Reports') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
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

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @vite('resources/js/app.js')
        @fluxScripts
    </body>
</html>
