---
id: "sixteen-auth-components-regression-restore"
type: "bugfix-story"
title: "Restore auth Blade components deleted in 6cb55f0d (login 500)"
description: "Il commit 6cb55f0d ha cancellato components/auth/{error-summary,field,check}.blade.php ancora referenziati da filament/widgets/auth/* e pages/auth/*: /it/auth/login ritornava 500 'Unable to locate a class or view for component [auth.error-summary]'."
frontmatter:
  severity: "blocker"
  root_cause_commit: "6cb55f0d"
links:
  error: "ViewException: Unable to locate a class or view for component [auth.error-summary]"
---

# Restore auth components — Dev Agent Record

## Perché

`6cb55f0d` (2026-11-11, reorg auth) ha rimosso sei file da `resources/views/components/`:

- `auth/error-summary.blade.php` — usato da login, register, forgot-password, reset-password widget
- `auth/field.blade.php` — usato da register widget
- `auth/check.blade.php` — usato da register widget
- `authentication-card-logo.blade.php` + `authentication-card.blade.php` — servono a `resources/views/auth/*.blade.php` (view legacy non referenziate da route, ma `view:cache` le compila comunque)
- `auth/authentication-card-logo.blade.php` — nessun caller `<x-auth.authentication-card-logo>`

Le classi CSS (`auth-login-alert`, `auth-summary__*`, `auth-field__*`, `auth-check*`) sono rimaste in `components/auth/assets.blade.php`: il vestito c'è, mancava solo il markup.

## Cosa si fa (forward-only)

Re-inserire i tre componenti con il contratto invariato (stesso markup/A11y del DESIGN precedente, verificato contro i caller):

- `<x-auth.error-summary />` — `role="alert"`, `trans_choice('pub_theme::auth_form.summary')`, lista errori `$errors->all()` unique
- `<x-auth.field name label type autocomplete hint required revealable>` — label vera, `aria-invalid`/`aria-describedby`, eye toggle per password
- `<x-auth.check name required>` — checkbox con label slot, errore legato

### Estensione: gate `view:cache`

`php artisan view:cache` compila OGNI `.blade.php` sotto i view path registrati, incluse le view legacy non referenziate da route. Dopo il fix auth il gate falliva ancora su `pub_theme::blocks.services.service-card` (usato da `pages_old1/services/index.blade.php`).

Attraverso scan di compilazione per-file (blade.compiler->compile su tutti i views) sono stati censiti i componenti mancanti: 26 file Sixteen referenziavano ~50 componenti cancellati dallo stesso `6cb55f0d`. Ripristinati da `6cb55f0d^` tutti i file sotto `resources/views/components/` necessari alla compilazione (badge, cards, services, ui/*, accessibility/*, layouts/design-comuni-bare, form-section, action-section, section-border, danger-button, ecc. — 56 file staged con `git add -f` perché `.gitignore` ha il pattern `components`/`layouts` troppo largo e li ignora).

Da notare: `components/` e `layouts/` in `.gitignore` sono pattern senza slash iniziale — matchano `resources/views/components/` e rischiano di far sparire nuovi file dal tracking. Le 246 view component già tracciate lo dimostrano. Da valutare l'ancoraggio `/components` `/layouts` in un task dedicato.

## Verifica

- `curl -s -o /dev/null -w '%{http_code}' http://192.168.1.40:8000/it/auth/login` → 200
- `php artisan view:cache` exit 0 ("Blade templates cached successfully")
- `curl /it/tests` → 200 (era 500 per `layouts.design-comuni-bare` mancante)
- `curl /it/auth/register` → 200
- `vendor/bin/pest Themes/Sixteen/tests/Unit/PartsIncludesResolveTest.php` → passed
- `NoSemanticFolioPageDirectoriesTest` fallisce su `pages/area-personale/` + `pages/pages/` preesistenti — fuori scope, da decidere se le pagine vanno spostate o il test va aggiornato

## Seconda passata (sessione parallela, 2026-11-11): famiglie dinamiche, `.gitignore`, guardia

La scansione statica non vede gli include il cui nome si compone a runtime (`layout-'.$d('layout')`,
`sections.'.$section['type']`). Li ha trovati una scansione HTTP: sitemap per forma di URL (76 su 2108) piu' tutte le
pagine `/it/tests/<slug>` dei JSON (83). Prima: FAQ ×4 lingue in 500 e 20 pagine `/it/tests/*` in 500
(`appuntamento-*`, `segnalazione-*`, `*-area-personale`). Dopo il ripristino: **0 errori 500 sulle 83 pagine tests**, FAQ 200 in it/en/de/es.

Ripristinate da `6cb55f0d^` (solo viste con chiamante vivo o referenziate dal JSON): famiglie `content-page/*` (7),
`report-step/*` (7), `report-area/*` (3), `appointment-step/*` (11), `data-display/*` (7), `layouts/*` (4),
`blocks/geo/mappa-static`, `report-partials/{rating,stepper}`, `wizard/summary-{shell,callout,sections}`.

**`.gitignore` ancorato** (era l'aperto di sopra): `components` → `/components`, `layouts` → `/layouts`. Prima 34 viste
ripristinate stavano su disco ma fuori da git (un commit di sync non le avrebbe incluse, e la sync successiva le avrebbe
perse di nuovo). Ora sono visibili come `??`. Le righe le aveva aggiunte la stessa sync `6cb55f0d`.

**Guardia** `tests/Unit/PartsIncludesResolveTest.php` estesa: la variabile (`$parts`, `$wizard`) si risolve nel file o nelle
cartelle antenate (`report-step/layout-*` eredita `$parts` dal padre); piu' un controllo che `.gitignore` non contenga
`components`/`layouts` senza ancora. Era ROSSA su 4 + 4 + 3 hit prima dei ripristini. Pint ok, Pest 2/2, PHPStan 0.

Ancora aperto: gli include a nome composto a runtime restano coperti solo dalla scansione HTTP (non da un test);
audit dell'area loggata; revisione UI/UX e docs di moduli/temi (vedi i task successivi in questa story).
