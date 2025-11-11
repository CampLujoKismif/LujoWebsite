@extends('components.layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="bg-white dark:bg-zinc-900 shadow rounded-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                Session Report Preview
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
                There are no enrollments for
                <span class="font-medium">
                    {{ $campInstance->camp?->display_name ?? 'this camp' }}
                    — {{ $campInstance->name ?? $campInstance->year }}
                </span>
                yet, so the report is empty.
            </p>
            <a href="{{ route('dashboard.manager.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Back to Manager Dashboard
            </a>
        </div>
    </div>
@endsection


