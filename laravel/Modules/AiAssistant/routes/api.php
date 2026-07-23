<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\AiAssistant\Http\Controllers\AiActionConfirmController;
use Modules\AiAssistant\Http\Controllers\AiChatStreamController;
use Modules\AiAssistant\Http\Controllers\AiProposalsController;
use Modules\AiAssistant\Http\Controllers\AiSttController;
use Modules\AiAssistant\Http\Controllers\AiTtsController;

Route::middleware(['web', 'auth'])->prefix('aiassistant')->group(function () {
    Route::middleware(['throttle:aiassistant-chat'])->get('/chat/stream', [AiChatStreamController::class, 'stream']);
    Route::middleware(['throttle:aiassistant-stt'])->post('/stt', [AiSttController::class, 'transcribe']);
    Route::middleware(['throttle:aiassistant-stt'])->post('/tts', [AiTtsController::class, 'speak']);
    Route::get('/threads/{threadPublicId}/proposals', [AiProposalsController::class, 'index']);
    Route::post('/actions/{proposalPublicId}/confirm', [AiActionConfirmController::class, 'confirm']);
});
