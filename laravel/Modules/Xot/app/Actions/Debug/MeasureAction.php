<?php

declare(strict_types=1);

namespace Modules\Xot\Actions\Debug;

use Closure;
use Filament\Notifications\Notification;

/**
 * Classe per misurare le performance di esecuzione di un blocco di codice.
 */
class MeasureAction
{
    /**
     * Executes a closure while measuring execution time and memory usage.
     *
     * Returns mixed because it's a generic closure executor that captures and returns
     * the closure's result, which can be any type. The @template T parameter ensures
     * type inference for the actual closure return type when called with a typed closure.
     *
     * @template T
     *
     * @param  Closure():T  $closure  The closure to execute and measure
     * @param  string  $label  Optional label to identify the measurement in notifications
     * @return T The result of the closure execution, type-preserved via template
     */
    public function execute(Closure $closure, string $label = ''): mixed
    {
        $start = microtime(true);
        $memory_start = memory_get_usage();

        // Eseguiamo la closure e otteniamo il risultato
        $result = $closure();

        $end = microtime(true);
        $memory_end = memory_get_usage();

        // Calcoliamo le metriche di performance
        $execution_time = ($end - $start) * 1000; // Conversione in millisecondi
        $memory_usage = ($memory_end - $memory_start) / 1024; // Conversione in KB

        $metrics = [
            'label' => $label,
            'execution_time' => round($execution_time, 2).' ms',
            'memory_usage' => round($memory_usage, 2).' KB',
            // 'peak_memory' => round(memory_get_peak_usage() / 1024 / 1024, 2).' MB',
        ];

        // Mostriamo una notifica con le metriche
        Notification::make()
            ->title('Performance Metrics '.($label !== '' ? $label : 'Unnamed'))
            ->body($metrics['execution_time'].'  '.$metrics['memory_usage'])
            ->success()
            ->persistent()
            ->send();

        // Log::debug('Performance Metrics', $metrics);

        /* @var T $result */
        return $result;
    }
}
