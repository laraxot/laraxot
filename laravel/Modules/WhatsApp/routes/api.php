<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\WhatsApp\Http\Controllers\WhatsAppWebhookController;

Route::middleware(['throttle:whatsapp-webhook'])->prefix('webhooks/whatsapp')->group(function (): void {
    Route::get('/meta', [WhatsAppWebhookController::class, 'verify'])->name('whatsapp.webhook.verify');
    Route::post('/meta', [WhatsAppWebhookController::class, 'receive'])->name('whatsapp.webhook.receive');
    Route::post('/ycloud', [WhatsAppWebhookController::class, 'receiveYCloud'])->name('whatsapp.webhook.ycloud');
});
