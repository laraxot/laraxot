<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Wts\Http\Controllers\WtsPrintQueueController;

/*
 * Coda di stampa etichette: l'agente sul PC di impianto pesca i job ZPL e ne
 * segna l'esito. Non e' un'API da browser, l'unica credenziale e' il bearer
 * token configurato in wts.print_agent_token.
 */
Route::prefix('wts')->group(function (): void {
    Route::get('/print-jobs', [WtsPrintQueueController::class, 'index'])->name('api.wts.print-jobs.index');
    Route::post('/print-jobs/{printJob}/done', [WtsPrintQueueController::class, 'markPrinted'])->name('api.wts.print-jobs.done');
    Route::post('/print-jobs/{printJob}/failed', [WtsPrintQueueController::class, 'markFailed'])->name('api.wts.print-jobs.failed');
});
