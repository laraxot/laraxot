---
title: "Step 1: Privacy Page Creation"
type: note
status: draft
updated: 2026-10-15
tags: [docs, sixteen, bmd]
qmd: "step 1: privacy page creation sixteen"
---
# Step 1: Privacy Page Creation

## Goal
Create `/privacy` page that works with Italian GDPR requirements and links to disclaimer.

## Files to Create

### 1. `resources/views/pages/privacy.blade.php`

```blade
@extends('themes::Sixteen.layouts.guest')

@section('title', __('privacy.title'))

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <header class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ __('privacy.title') }}</h1>
            <p class="text-xl text-gray-600 dark:text-gray-300">{{ __('privacy.subtitle') }}</p>
        </header>

        <article class="prose dark:prose-invert max-w-none">
            <section class="mb-8">
                <h2>{{ __('privacy.purpose') }}</h2>
                <p>{{ __('privacy.purpose_text') }}</p>
            </section>

            <section class="mb-8">
                <h2>{{ __('privacy.data_collected') }}</h2>
                <ul>
                    <li>{{ __('privacy.technical_cookies') }}</li>
                    <li>{{ __('privacy.analytics_cookies') }}</li>
                    <li>{{ __('privacy.marketing_cookies') }}</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2>{{ __('privacy.legal_basis') }}</h2>
                <p>{{ __('privacy.legal_basis_text') }}</p>
            </section>

            <section class="mb-8">
                <h2>{{ __('privacy.rights') }}</h2>
                <ul>
                    <li>{{ __('privacy.right_access') }}</li>
                    <li>{{ __('privacy.right_rectification') }}</li>
                    <li>{{ __('privacy.right_erasure') }}</li>
                    <li>{{ __('privacy.right_portability') }}</li>
                    <li>{{ __('privacy.right_object') }}</li>
                </ul>
            </section>

            <section class="mb-8">
                <h2>{{ __('privacy.retention') }}</h2>
                <p>{{ __('privacy.retention_text') }}</p>
            </section>

            <section class="mb-8">
                <h2>{{ __('privacy.contact') }}</h2>
                <address class="not-italic">
                    <p><strong>{{ config('app.institution_name', 'Ente di appartenenza') }}</strong></p>
                    <p>{{ config('app.institution_email', 'email@ente.it') }}</p>
                    <p>{{ config('app.institution_phone', '+39 0XX XXXXXXX') }}</p>
                </address>
            </section>
        </article>

        <footer class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('privacy.last_updated', ['date' => now()->format('d/m/Y')]) }}
            </p>
        </footer>
    </div>
</div>
@endsection
```

### 2. Add route in `routes/web.php`

```php
use Illuminate\Support\Facades\Route;

// ... existing routes

// Privacy page
Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

// Disclaimer page
Route::get('/disclaimer', function () {
    return view('disclaimer');
})->name('disclaimer');

// Cookie Policy page
Route::get('/cookie-policy', function () {
    return view('pages.cookie-policy');
})->name('cookie-policy');
```

## Acceptance Criteria
- [x] `/privacy` returns 200 OK
- [x] `/disclaimer` returns 200 OK  
- [x] `/cookie-policy` returns 200 OK
- [x] Links between pages work (privacy ↔ disclaimer ↔ cookiebar)
- [x] All content in Italian (multilingua it/en/de/es)

## Related
- `disclaimer-integration.story.md`
- `design-italia-guidelines-integration.story.md`