<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            .auth-gradient {
                background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            }
            .auth-card {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .dark .auth-card {
                background: rgba(17, 24, 39, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .camp-logo {
                background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
            }
        </style>
    </head>
    <body class="min-h-screen auth-gradient antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-md flex-col gap-6">
                <!-- Camp Logo and Branding -->
                <div class="flex flex-col items-center gap-4 text-center">
                    <div class="camp-logo flex h-20 w-64 items-center justify-center rounded-xl bg-white shadow-xl border border-gray-200">
                        <x-app-logo-icon class="h-12 w-auto" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white mb-1">Camp LUJO-KISMIF</h1>
                        <p class="text-blue-100 text-sm">Keep It Spiritual, Make It Fun!</p>
                    </div>
                </div>

                <!-- Auth Card -->
                <div class="auth-card rounded-2xl p-8 shadow-2xl">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="text-center">
                    <p class="text-blue-100 text-sm">
                        © 2024 Camp LUJO-KISMIF. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
