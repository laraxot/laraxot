<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Intervention\Http\Controllers\GoogleCalendarOAuthController;
use Modules\Intervention\Http\Controllers\PublicInterventionReportController;

Route::middleware(['web'])->group(function (): void {
    // Public, tokenized client-facing report page (shareable link).
    Route::get('/rapportino/{token}', [PublicInterventionReportController::class, 'show'])
        ->name('interventions.report.public');
    // The downloadable PDF of the same report.
    Route::get('/rapportino/{token}/pdf', [PublicInterventionReportController::class, 'pdf'])
        ->name('interventions.report.public.pdf');
    // The customer signs the report with their finger on the public page.
    Route::post('/rapportino/{token}/firma', [PublicInterventionReportController::class, 'sign'])
        ->name('interventions.report.public.sign');
    // Start a card payment for the report.
    Route::post('/rapportino/{token}/pagamento', [PublicInterventionReportController::class, 'pay'])
        ->name('interventions.report.public.pay');
    // Return URL after the hosted checkout completes.
    Route::get('/rapportino/{token}/grazie', [PublicInterventionReportController::class, 'paymentReturn'])
        ->name('interventions.report.public.return');
});

Route::middleware(['web', 'auth'])->group(function (): void {
    // Per-user Google Calendar OAuth (two-way intervention sync).
    Route::get('/interventions/google-calendar/connect', [GoogleCalendarOAuthController::class, 'redirect'])
        ->name('interventions.google-calendar.connect');
    Route::get('/interventions/google-calendar/callback', [GoogleCalendarOAuthController::class, 'callback'])
        ->name('interventions.google-calendar.callback');
    Route::get('/interventions/google-calendar/disconnect', [GoogleCalendarOAuthController::class, 'disconnect'])
        ->name('interventions.google-calendar.disconnect');
});
