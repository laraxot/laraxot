<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Wts\Filament\Pages\WtsMaterialDetailPage;
use Modules\Wts\Models\WtsUnit;

/*
 * Il QR stampato sull'etichetta punta qui: chi lo scansiona in impianto finisce
 * sulla scheda del materiale senza dover cercare l'unita' a mano.
 */
Route::get('/wts/qr/{qrCode}', function (string $qrCode) {
    $unit = WtsUnit::query()->where('qr_code', $qrCode)->first();
    abort_if($unit === null, 404);

    return redirect(WtsMaterialDetailPage::getUrl(panel: 'wts', parameters: ['unit' => $unit->id]));
})->name('wts.qr');
