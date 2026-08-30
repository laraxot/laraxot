<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Modules\Signage\Models\SignSpec;

/*
 * Anteprima del disegno insegna: gli .ai sono contenitori PDF, quindi il browser
 * li mostra da solo se glieli serviamo come application/pdf. Serviti da qui e non
 * da /storage, per non esporre i disegni dei clienti a chi non e' loggato.
 */
Route::get('/signage/artwork/{spec}', function (SignSpec $spec) {
    // 403 e non redirect al login: ogni pannello ha la propria pagina di accesso,
    // una rotta di login globale qui non esiste.
    abort_unless(auth()->check(), 403);

    $path = $spec->artwork_path;
    abort_if($path === null || $path === '' || ! Storage::disk('public')->exists($path), 404);

    return response()->file(Storage::disk('public')->path($path), [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.basename((string) $spec->source_file).'"',
    ]);
})->name('signage.artwork');
