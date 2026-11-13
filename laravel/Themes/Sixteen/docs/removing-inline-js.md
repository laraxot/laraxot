---
title: "REMOVING INLINE JS"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "REMOVING INLINE JS"
issues: []
discussions: []
status: draft
---

# Rimozione JS inline da Blade — COMPLETATO

**STATUS**: ✅ Pulito. Solo inline bootstrapping strutturale necessario.

## Cosa è stato eliminato

- Rimosso `<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.18.0/dist/css/bootstrap-italia.min.css">` da tutti i layout.
- Rimosse classi CSS da `<body>` in tutti i layout (ora semplice `<body>` come reference Design Comuni).
- Rimosso `@section('js')` vuoto da `ticket-create-wizard.blade.php`.
- Rimosso `<form wire:submit="submit">` wrappante da `ticket-create-wizard.blade.php`.
- **IN USE** `resources/js/theme/header-mobile-nav.js` — 189 righe con selector `[data-mobile-nav-*]` canonical (usato da `app.js:26,164,173,209,264-266,384,407,441`).
  - **RIMOSSO** `resources/js/theme/header-mobile-nav-boot.js` — entry Vite separato, non più presente su disco.
- Rimossi i relativi `.lock` files.

## Cosa è stato corretto

- `@vite()` in tutti i layout ora ha **un solo entry**: `@vite(['resources/js/app.js'], 'themes/Sixteen')`
- Alpine `headerMobileNav` → **eliminato** (nessun template lo usava mai)
- Alpine `mobileMenu()` → registrato via inline `<script>` su `alpine:init` **PRIMA** di `@livewireScripts`
- `app.js` — import `initHeaderMobileNav, isDesignComuniPage` da `./theme/header-mobile-nav.js` attivo (linea 26); `initHeaderMobileNav()` chiamato su `DOMContentLoaded`, `livewire:navigated`, `livewire:update` (linee 264-266) e inizializzazione immediata (linea 164).
- CSS morto per gli hook proprietari della navigazione mobile rimosso da `app.css`

## Pattern attuale (canonico)

- **Single entry Vite**: `@vite(['resources/js/app.js'], 'themes/Sixteen')` — unico punto di ingresso.
- **Alpine.data boot**: inline `<script>` in `<head>` con listener `alpine:init` (25 righe). Necessario perché Vite emette `type="module"` defer che arriva dopo Alpine.
- **app.js**: registra `Alpine.data('mobileMenu', mobileMenu)` come fallback per navigazioni Livewire successive. `Alpine.data` è idempotente, la doppia registrazione è innocua.

## Perché c'è ancora inline JS

Il `<script>` in `<head>` che registra su `alpine:init` **non è JS legacy**. È bootstrapping strutturale, esattamente come il dark mode anti-FOUC:
- Vite ESM defer non può eseguire prima di Alpine
- L'inline è l'unico modo per catturare `alpine:init` che scatta dentro `@livewireScripts`
- Pattern **canonico** Alpine, documentato ufficialmente
- 25 righe, zero dipendenze, testato con ogni Alpine v3.x

## Cosa resta da fare (next)

- **Migrazione BS Italia → Alpine**: 8-9 template usano ancora `data-bs-toggle="navbarcollapsible"` con shim vanilla JS in `app.js`. Separare in nuova storia.
