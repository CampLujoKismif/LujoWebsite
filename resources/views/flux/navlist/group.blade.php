@props([
    'expandable' => false,
    'expanded' => true,
    'heading' => null,
])

@php
    // Convert expandable to boolean
    $expandable = filter_var($expandable, FILTER_VALIDATE_BOOLEAN);
    // Convert expanded to boolean
    $expanded = filter_var($expanded, FILTER_VALIDATE_BOOLEAN);
    // Generate unique ID for this disclosure
    $disclosureId = 'disclosure-' . uniqid();
@endphp

<?php if ($expandable && $heading): ?>

<div {{ $attributes->merge(['class' => 'group/disclosure']) }} data-flux-navlist-group x-data="{ open: {{ $expanded ? 'true' : 'false' }} }">
    <button
        type="button"
        @click="open = !open"
        class="group/disclosure-button mb-[2px] flex h-10 w-full items-center rounded-lg text-zinc-500 hover:bg-zinc-800/5 hover:text-zinc-800 lg:h-8 dark:text-white/80 dark:hover:bg-white/[7%] dark:hover:text-white"
    >
        <div class="ps-3 pe-4 flex items-center">
            <svg 
                class="size-3 transition-transform duration-200"
                :class="{'rotate-0': open, '-rotate-90': !open}"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>

        <span class="text-sm font-medium leading-none">{{ $heading }}</span>
    </button>

    <div 
        class="relative space-y-[2px] ps-4 overflow-hidden transition-all duration-200"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 max-h-0"
        x-transition:enter-end="opacity-100 max-h-screen"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 max-h-screen"
        x-transition:leave-end="opacity-0 max-h-0"
    >
        <div class="absolute inset-y-[3px] start-0 ms-3 w-px bg-zinc-200 dark:bg-white/30"></div>

        {{ $slot }}
    </div>
</div>

<?php elseif ($heading): ?>

<div {{ $attributes->class('block space-y-[2px]') }}>
    <div class="px-1 py-2">
        <div class="text-xs leading-none text-zinc-400">{{ $heading }}</div>
    </div>

    <div>
        {{ $slot }}
    </div>
</div>

<?php else: ?>

<div {{ $attributes->class('block space-y-[2px]') }}>
    {{ $slot }}
</div>

<?php endif; ?>
