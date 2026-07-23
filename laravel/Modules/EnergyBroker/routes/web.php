<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\EnergyBroker\Http\Controllers\EnergyBrokerDocumentDownloadController;
use Modules\EnergyBroker\Http\Controllers\EnergyBrokerLargeUploadController;

Route::middleware(['web', 'auth'])->get('/energy-broker/documents/{document}/download', EnergyBrokerDocumentDownloadController::class)
    ->name('energy-broker.documents.download');

Route::middleware(['web', 'auth'])->prefix('energy-broker/large-uploads')->name('energy-broker.large-uploads.')->group(function (): void {
    Route::get('/recent', [EnergyBrokerLargeUploadController::class, 'recent'])->name('recent');
    Route::post('/start', [EnergyBrokerLargeUploadController::class, 'start'])->name('start');
    Route::post('/{publicId}/chunk', [EnergyBrokerLargeUploadController::class, 'chunk'])->name('chunk');
    Route::post('/{publicId}/complete', [EnergyBrokerLargeUploadController::class, 'complete'])->name('complete');
    Route::get('/{publicId}/status', [EnergyBrokerLargeUploadController::class, 'status'])->name('status');
    Route::post('/{publicId}/retry', [EnergyBrokerLargeUploadController::class, 'retry'])->name('retry');
});
