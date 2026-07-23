<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Email\Http\Controllers\EmailInboundWebhookController;

Route::middleware(['throttle:email-ai-webhook'])->prefix('webhooks/email-ai')->group(function (): void {
    Route::post('/inbound', [EmailInboundWebhookController::class, 'receive'])->name('email-ai.webhook.inbound');
});
