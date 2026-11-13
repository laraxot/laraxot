<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <x-cms::head />

    <body>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <!-- MIGLIORE SOLUZIONE UI/UX: Responsive ottimizzata per leggibilità -->
            <div class="w-full max-w-sm sm:max-w-md md:max-w-lg mt-6 px-8 py-6 bg-white dark:bg-gray-800 shadow-lg overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                {{ $slot }}
            </div>
        </div>
        {{-- Il CSS lo rende <x-cms::head>; il JS del tema resta a fine body. --}}
        @vite(['resources/js/app.js'], 'themes/Sixteen')
    </body>
</html>
