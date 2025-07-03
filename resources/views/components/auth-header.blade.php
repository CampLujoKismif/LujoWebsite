@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $title }}</h2>
    <p class="text-gray-600 dark:text-gray-300">{{ $description }}</p>
</div>
