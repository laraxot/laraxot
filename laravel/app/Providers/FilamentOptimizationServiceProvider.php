<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class FilamentOptimizationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Ottimizza le view per ridurre l'uso di memoria
        View::composer('*', function ($view) {
            // Limita il numero di variabili passate alle view
            if (count($view->getData()) > 50) {
                $data = $view->getData();
                $view->with(array_slice($data, 0, 50, true));
            }
        });

        // Ottimizza Filament per ridurre il carico di memoria
        Filament::serving(function () {
            // Disabilita funzionalità non essenziali in produzione
            if (app()->environment('production')) {
                Filament::disableRobots();
            }
        });
    }

    public function register(): void
    {
        // Registra configurazioni di ottimizzazione
        $this->mergeConfigFrom(
            __DIR__.'/../../config/filament_optimization.php',
            'filament_optimization'
        );
    }
}
