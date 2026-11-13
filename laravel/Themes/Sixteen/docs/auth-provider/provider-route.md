---
title: "Folio Auth Login — Design Comuni + Volt + Filament Blueprint"
type: note
tags: [design-comuni, folio, volt, filament, auth, bmadd, second-brain]
created: 2026-10-18
updated: 2026-10-18
status: draft
qmd: "auth login   `pages/auth/login.blade.php` sixteen"
---

# Auth Login — `pages/auth/login.blade.php`

## BMAD Analysis

- **B (Benchmark)**: Folio Volt page `name('login')` with `middleware(['guest'])` at `/it/auth/login`
- **M (Model)**: Uses `Volt\Component`, `SocialLoginWidget`, `UserForm` schema; reads CMS JSON via `PageSlugMiddleware` if needed
- **A (Audit)**: Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite (NO Bootstrap Italia); `@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')`; no controllers/web.php/api.php
- **D (Deliver)**: Form (email/password/remember) + SocialLoginWidget (Google) + register/forgot links

## Translation Convention (5 elementi)

- `fixcity::auth.login.email.label`
- `fixcity::auth.login.password.label`
- `fixcity::auth.login.submit.text`
- `user::auth.login_widget.providers.google.label`
- `pub_theme::accessibility.page.title.text`

## References

- Folio docs: `https://laravel.com/framework/docs/13.x/folio`
- Vol docs: `https://github.com/livewire/volt`
- Design Comuni: `https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html`

## Root-Cause Prevention

- Always use `route('oauth.redirect', ['provider' => 'google'])` (no `socialite.` prefix)
- Always set `GOOGLE_REDIRECT_URI=https://fixcity.eu/it/auth/login/google/callback`
- Always use `@vite(..., 'themes/Sixteen')` for theme assets
- Never hardcode locale `/it/` inside route definitions (LaravelLocalization handles)
