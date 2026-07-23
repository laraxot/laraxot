<?php

use Modules\Cms\Models\Page;
use function Laravel\Folio\name;
use function Laravel\Folio\render;
use Illuminate\Http\Request;
use Illuminate\View\View;

name('market.detail');

render(function (View $view, Request $request, string $slug) {
    $locale = app()->getLocale();
    $page = Page::firstWhere(['slug' => $slug]);

    return $view->with('page', $page)->with('slug', $slug);
});

?>

<x-layouts.app>
    <div class="min-h-screen bg-gray-50 dark:bg-slate-900">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <a href="/it" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            ← Back to Markets
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <button id="theme-toggle" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 rounded-lg">
                            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        @if($page)
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @if(!empty($page->sidebar_blocks))
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="xl:col-span-2 space-y-8">
                            {{ $_theme->showPageContent($page->slug) }}
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-8">
                            {{ $_theme->showPageSidebarContent($page->slug) }}
                        </div>
                    </div>
                @else
                    <div class="space-y-8">
                        {{ $_theme->showPageContent($page->slug) }}
                    </div>
                @endif
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-8 space-y-4">
                <div class="space-y-2 text-center">
                    <h2 class="text-2xl font-bold md:text-4xl lg:text-6xl">Market not found!</h2>
                    <p class="text-gray-400">The market you're looking for doesn't exist.</p>
                    <a href="/it" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        ← Back to Markets
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Dark mode toggle
        const themeToggle = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;
        
        // Initialize theme
        const currentTheme = localStorage.getItem('theme') || 'light';
        htmlElement.classList.toggle('dark', currentTheme === 'dark');
        
        themeToggle?.addEventListener('click', () => {
            htmlElement.classList.toggle('dark');
            const isDark = htmlElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });

        // Simulate real-time price updates
        function updatePrices() {
            const yesPrice = document.querySelector('.text-green-900 .text-2xl');
            const noPrice = document.querySelector('.text-red-900 .text-2xl');
            
            if (yesPrice && noPrice) {
                // Small random price movements
                const yesChange = (Math.random() - 0.5) * 0.004; // ±0.2¢ max
                const currentYes = parseFloat(yesPrice.textContent.replace('¢', '')) / 100;
                const newYes = Math.max(0.01, Math.min(0.99, currentYes + yesChange));
                const newNo = Math.max(0.01, Math.min(0.99, 1 - newYes));
                
                yesPrice.textContent = (newYes * 100).toFixed(1) + '¢';
                noPrice.textContent = (newNo * 100).toFixed(1) + '¢';
            }
        }
        
        // Update prices every 5 seconds for demo
        setInterval(updatePrices, 5000);
    </script>
</x-layouts.app>