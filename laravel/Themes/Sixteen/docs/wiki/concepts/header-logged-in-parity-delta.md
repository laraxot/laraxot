# Header slim — utente loggato vs Design Comuni (delta)

## Scopo

Quando l’utente è autenticato, lo **slim header** (barra verde in alto) deve essere visivamente identico al reference Design Comuni (`graduatoria-area-personale.html`, `segnalazione-area-personale.html`). Oggi il toggle utente usa markup e classi diverse dal kit PA.

## Evidenza screenshot (2026-06-05)

| File | Stato |
|------|--------|
| `logged-original.png` | Atteso: avatar foto in bottone verde slim, ITA a sinistra |
| `logged.png` (pre-fix) | Bug: cerchio blu + icona power da `nav-link` / classi `header-user-avatar` |

Post-fix markup (2026-06-05): `user-dropdown` = `cmp-header.hbs`.

### Verifica live Playwright (2026-06-05, login FO `/it`)

| Check | Risultato |
|-------|-----------|
| Toggle `background` | `var(--dc-green)` / `rgb(0, 122, 82)` ✅ |
| Markup | `btn btn-primary btn-icon btn-full` + `rounded-icon` ✅ |
| Nome desktop | visibile (`d-none d-lg-block`) ✅ |
| Avatar senza media | Gravatar `?d=mp` da email utente ✅ |

Residui vs `logged-original.png` (UX §11 in `docs/stories/STORY-147-ux-design-header-logged-in.md`):

1. **CSS cascade** — chiuso (`13-final-runtime-overrides.css`, toggle `var(--dc-green)` su slim `#00402b`).
2. **Avatar foto** — reference `picsum` 20×20; backlog upload Spatie `avatar`.
3. **Iniziale** — chiuso (`<strong.user-initial-fallback>`, colore `#00402b`).
4. **Gravatar FO** — fallback da email se assente media locale.
5. **Dropdown voci** — icone extra su servizi/pratiche/notifiche ≠ DC (rimuovere).
6. **Visual diff** — Playwright vs `logged-original.png` (STORY-148).

### Causa secondaria avatar «blu/power»

`Profile::getAvatarUrl()` con email profilo vuota genera Gravatar SHA256('') → immagine generica non conforme al reference. Fix header `v1.blade.php`: se hash vuoto → `$headerAvatarUrl = null` → iniziale in `rounded-icon` (come fallback DC).

## SSoT implementazione

| Ruolo | Path |
|-------|------|
| Header owner | `resources/views/components/sections/header/v1.blade.php` |
| Guest CTA (corretto) | `partials/personal-area-guest-cta.blade.php` |
| Logged toggle (da correggere) | `partials/user-dropdown.blade.php` |
| Reference HTML | `docs/visual-comparison/structure-analysis/ticket-area-personale-reference-body.html` (righe 37–80) |
| CSS parity | `resources/css/app/08-layout-type-and-header.css` (`.btn-icon.btn-full`) |

## Delta strutturale (reference vs attuale)

### 1. Trigger (pulsante utente)

| | Reference Design Comuni | Implementazione (2026-06-05) |
|---|------------------------|------------------------------|
| Elemento | `<a class="btn btn-primary btn-icon btn-full" data-bs-toggle="dropdown">` | ✅ `user-dropdown.blade.php` |
| Aspetto | Bottone `#007a52` su fascia slim `#00402b` (D1) | Layer 13: `var(--dc-green)`, no bordo bianco |
| Coerenza guest | Stesso pattern CTA «Area personale» | ✅ stesso `btn-icon btn-full` |

### 2. Avatar

| | Reference | Attuale |
|---|-----------|---------|
| Wrapper | `<span class="rounded-icon">` | `<span class="header-user-avatar">` |
| Immagine | `img.border.rounded-circle.icon-white` 20×20 nel rounded-icon | `img.rounded-circle.border.border-2` o iniziale in `header-user-avatar-initial` |
| CSS target | `.it-user-wrapper .rounded-icon` (già in `08-layout-type-and-header.css`) | Regole su `.header-user-avatar` non allineate al reference |

### 3. Nome utente + chevron

| | Reference | Implementazione (2026-06-05) |
|---|-----------|------------------------------|
| Nome | `<span class="d-none d-lg-block">` | ✅ `user-dropdown` — `d-none d-lg-block` |
| Chevron | `<svg class="icon icon-white d-none d-lg-block">` | ✅ stesso pattern |
| Mobile | Solo avatar in toggle | ⚠️ Verificare CSS `ticket-parity.css` ~5543 (`display:inline-block !important` su span) |

Target screenshot: `logged-dropdown.png` (avatar-only + menu aperto). UX: `docs/stories/STORY-147-ux-design-header-logged-in.md` §7.

### 4. Pannello dropdown

| | Reference | Attuale |
|---|-----------|---------|
| Container | `<div class="dropdown-menu"><div class="row"><div class="col-12"><div class="link-list-wrapper">` | `<div class="dropdown-menu bg-white text-gray-800 rounded-md px-3 py-2">` |
| Stile | Bootstrap Italia / token DC | Utility Tailwind (non previste nel reference) |
| Voci | Testo in `<span>` senza icona (tranne Esci) | Icona sprite su ogni voce (briefcase, file, bell, settings, logout) |

### 5. Logout

| | Reference | Attuale |
|---|-----------|---------|
| Markup | `<a class="list-item left-icon">` + `it-external-link` + `fw-bold` «Esci» | `<form POST>` + `button.dropdown-item.text-danger` + `it-logout` |
| Semantica | Link visivo (demo statica) | POST CSRF (corretto per Laravel) — **mantenere POST**, adattare solo classi/markup |

## Piano di correzione

1. **Riscrivere `user-dropdown.blade.php`** allineando il trigger al reference:
   - `btn btn-primary btn-icon btn-full` + `data-bs-toggle="dropdown"` + `data-focus-mouse="false"`
   - Avatar dentro `rounded-icon`; fallback iniziale con stesse dimensioni del reference
   - Nome e chevron con `d-none d-lg-block`
2. **Dropdown menu**: copiare struttura da `language-switcher.blade.php` (già parity BI).
3. **Voci menu**: testo semplice come reference; icone opzionali solo se richieste da accessibilità (default: no icona per servizi/pratiche/notifiche/impostazioni).
4. **Logout**: form POST invariato; wrapper `list-item left-icon` + classi reference.
5. **CSS**: verificare che `.it-header-slim-wrapper .btn-icon.btn-full` copra il logged state; rimuovere override ridondanti su `.header-auth-toggle` se il markup cambia.
6. **QA**: login FO su `/it` e pagina area personale; confronto visivo slim bar (credenziali solo in ambiente test, mai in file repo).

### Verifica Playwright headless (2026-06-05)

- `Themes/Sixteen/tests/browser/header-logged-dropdown.spec.mjs` — **2/2 passed**
- Desktop 1280px: nome + chevron visibili, dropdown `.show`
- Mobile 375px: nome/chevron `display:none`, solo avatar
- Fix root: `bootstrap-italia.css` `.d-lg-block` globale + Bootstrap 5 `Dropdown` in `bootstrap-italia.js`

## Test

- Utente autenticato: slim bar mostra bottone verde con avatar + nome (desktop) come reference.
- Utente guest: invariato (`personal-area-guest-cta`).
- Dropdown: stesso aspetto del language switcher (bordo, shadow, hover `#17324d`).
- Mobile: solo avatar nel bottone; menu apre correttamente.

## GitHub

- Base issue: https://github.com/laraxot/base_fixcity_fila5/issues/277
- Theme issue: https://github.com/laraxot/theme_sixteen_fila5/issues/63
- Thread agenti (discussion non attiva su repo): https://github.com/laraxot/base_fixcity_fila5/issues/277#issuecomment-4630410417

## Collegamenti

- [Header SSoT](header-ssot.md)
- [STORY-147](../../../../../../docs/stories/STORY-147-header-logged-in-design-comuni-parity.md)
