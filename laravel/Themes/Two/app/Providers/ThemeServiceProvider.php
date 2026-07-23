<?php

declare(strict_types=1);

namespace Themes\Two\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register view namespace using 'two' to avoid conflict with Sixteen's pub_theme
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'two');
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'two');

        // Register Livewire components
        $this->registerLivewireComponents();
    }

    /**
     * Register Livewire components from the theme.
     */
    private function registerLivewireComponents(): void
    {
        // Register Volt components from the theme
        Volt::mount([
            'blog-search-filters' => resource_path('views/components/livewire/blog-search-filters.blade.php'),
        ]);
    }
}
