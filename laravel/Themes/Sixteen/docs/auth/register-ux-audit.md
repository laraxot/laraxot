---
title: "register ux audit"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-10-20
qmd: "register ux audit"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/572"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/573"
status: draft
---

# Register `/it/auth/register` — audit UX/WCAG/funzionamento (2026-06-04)

## Problemi trovati (prima)

| Area | Issue |
|------|--------|
| UI | Password + conferma affiancate in `Grid::make(2)` (layout stretto) |
| UI | Widget in `user::widgets.auth.register-widget` (no tema Sixteen) |
| UI | Submit `bg-primary-600` → rischio blu legacy |
| UI | Password senza bordo (suffix reveal) |
| UX | Label/placeholder grezzi se cache traduzioni stale (`user::user_form`) |
| UI | Password + conferma affiancate (`Grid::make(2)`) — layout stretto |
| UI | Label/placeholder = nome campo (`first_name`) — `user_form.php` auto-seed |
| WCAG | Nessun `role="alert"` / `aria-live` su errori |
| WCAG | Pagina senza `data-page`, no `id` heading form |
| Funzione | `user::auth.registration.success` / `error_occurred` assenti |
| Funzione | `RuntimeException` su errore generico |

## Fix applicati

- `UserForm::getRegisterFormSchema()`: password e `password_confirmation` in colonna (`columnSpanFull`, no grid 2 colonne)
- `14-auth-login.css`: helper `fi-sc-dense` leggibile; password full-width su `auth-register`
- `user_form.php` (it): label/placeholder/helper campi register
- CTA submit `w-full min-h-[44px]`; nav secondaria con `aria-label` coerente
- `pages/auth/register.blade.php`: `bodyPage="auth-register"`, `auth-register-card`, meta, `aria-labelledby`
- `filament/widgets/auth/register.blade.php` (nome da `GetViewByClassAction`, parity `login.blade.php`)
- Rimosso `primaryColor()` da `XotBaseMainPanelProvider` (metodo assente Filament v5)
- `RegisterWidget`: view tema via `GetViewByClassAction`, `revealable()` password, notifiche + redirect sicuro
- Register allineato a login: `fo-filament-form-shell`, Filament button primary, link `italia-blue`
- CTA: solo `color="primary"` (no classe `auth-register-submit` per colore)
- **2026-06-04 UX pass:** password + conferma in colonna singola (`UserForm::getRegisterFormSchema` senza grid 2 col)
- **`user::user_form` IT:** label/placeholder/helper_text leggibili (WCAG 3.3.2)
- Submit full-width `min-h-11`; link login sotto CTA con `focus-visible:ring`
- Sidebar hint con `id` per `auth-register-hint-email` / `auth-register-hint-password`

## WCAG checklist

| Criterio | Stato |
|----------|--------|
| Un H1 | OK |
| Label Filament (LangServiceProvider) | OK |
| Touch target ≥44px submit | OK |
| Focus visible | OK (`outline` verde PA) |
| Errori annunciati (`aria-live="assertive"`) | OK |
| Contrasto CTA verde/bianco | OK (~4.5:1) |
| Skip link → `#main-container` | OK (layout app) |

## Verifica

```bash
cd laravel/Themes/Sixteen && npm run build
curl -sL http://127.0.0.1:8000/it/auth/register | grep -E 'auth-register|aria-live|fi-color-primary'
```

Registrazione test: email unica, password `TestPassword1!` (12+ char, maiusc, minus, num, simbolo).

## UX pass 2026-10-07

- mantenuti password e conferma in colonna singola anche su mobile;
- mantenuti error summary annunciato con `role="alert"` e `aria-live`;
- CTA a tutta larghezza con target minimo 44px e link di accesso sempre visibile;
- il form usa le traduzioni auto-configurate dal LangServiceProvider: non si
  aggiungono `->label()` nei widget Filament;
- la verifica email è demandata al modulo User e non introduce logica di dominio
  nel tema.

Il certificato locale HTTPS già attivo è il prerequisito per testare la pagina
dal browser in LAN; la consegna email resta separata dal rendering della pagina.

## Regola di parità locale 2026-10-07

Ogni chiave usata da `Themes/Sixteen/resources/views/pages/auth/login.blade.php`
deve avere una traduzione esplicita in `Modules/User/lang/{locale}/auth.php` o
`login.php`. Il fallback italiano è vietato per URL `/en/*`: un URL localizzato
deve cambiare anche `app()->getLocale()` e tutto il copy visibile.

## Collegamenti

- [login-ux-fixes-2026-06-04.md](./login-ux-fixes.md)
- [filament-pa-design-colors.md](../../../Modules/Xot/docs/wiki/concepts/filament-pa-design-colors.md)

## Pass 2026-10-07 (seconda): chiavi grezze, card annidate, mobile

- Causa delle 9 chiavi grezze (`user::auth.register_page.*`, `user::registration.actions.register.*`, `user::registration.already_registered`): chiavi mai definite nei lang `it`. Aggiunte in `Modules/User/lang/it/auth.php` e `registration.php`; il test `Modules/User/tests/Feature/LoginPageTranslationsTest.php` le copre insieme a quelle del login.
- Il widget non annida più `x-filament-widgets::widget` + `x-filament::section` + `div p-5` dentro la card della pagina (quattro riquadri impilati, contenuto tagliato su mobile). Struttura come `login.blade.php`: card e padding nella pagina, widget con un solo `div`.
- Mobile (390px): card a tutta larghezza (`-mx-4 sm:mx-0`) e riquadro interno del form solo da `sm` (`border-0 p-0 sm:border sm:p-6`), così il campo password convive con il pulsante "Mostra password".
- Regola pratica: le utility Tailwind disponibili sono solo quelle presenti nell'ultimo build. Mancavano `sm:p-4`, `sm:p-5`, `sm:rounded-2xl`, `border-x-0`; verificare con `grep -c '\.sm\\:p-6{' public/assets/app-*.css` prima di usare una classe nuova in un blade, altrimenti serve un rebuild.
- CTA: la regola globale `body:not([data-page='auth-login']) button[type=submit].fi-btn` rendeva "Crea account" un contorno bianco; ora esclude tutte le pagine `auth-*` (commit 2b37f9c).

## Consensi 2026-10-19

I tre checkbox (privacy, termini, marketing) ricevevano il click e aggiornavano `wire:model`, ma la spunta restava invisibile. Flowbite, sulle pagine senza `.page-content[data-dc]`, azzera `appearance` e disegna un segno bianco su `background-color: white`, senza il fondo colorato dello stato checked. Nel widget il controllo nativo torna visibile (`appearance: auto`, `accent-color: currentColor`) così il consenso si vede prima di creare l'account.

## Correzione produzione 2026-10-20

`/it/auth/register` restituiva HTTP 500 prima del rendering: il CMS caricava due JSON con lo stesso slug `auth.register` (`pages/auth/register.json` e `pages/auth/auth.register.json`) e `Page::findUniqueBySlug()` rifiutava la collisione. La fonte canonica è `pages/auth/auth.register.json`, coerente con la route Folio `auth.register`; il duplicato è stato rimosso.

Il blocco `auth-register` ora usa `heading` e `intro` dal payload CMS con fallback alle traduzioni, e localizza i link accessibilità, privacy, termini e login con LaravelLocalization. Questo mantiene la pagina data-driven senza spostare la logica di registrazione nel tema.

## Pass 2026-10-20 — shell login-like + data-dc

Supersede: aggiunge al presente audit (non sostituisce le sezioni sopra).
Link da: [register-ux-design-handoff.md](./register-ux-design-handoff.md), [login-ux-fixes.md](./login-ux-fixes.md), [../prompts/04.md](../prompts/04.md).
Istruzione utente: «migliora https://fixcity.eu/it/auth/register».

**Perché:** con `.page-content[data-dc]` le utility Tailwind del tema e
`14-auth-login.css` sono isolate. Sintomi: card ~1392px, CTA apparentemente
piccola, chiavi grezze (`fixcity::auth.register.login_link.text`,
`user::auth.forgot_password.text`), breadcrumb `Home / /`, link Accedi duplicati.

**Cosa:**

| Pezzo | Scelta |
|-------|--------|
| Shell | `auth-register.blade.php` come login: un solo «Hai già un account? Accedi», forgot + a11y sotto Google |
| Larghezza | `<style>` inline (`max-width: 32rem`) — unica via sotto isolamento |
| CTA | submit inline nel widget `width:100%` + `min-height:2.75rem` + loading Livewire |
| Breadcrumb | `Home / {heading}` senza separator DC doppio |
| Cookie | padding inferiore shell; cookiebar fixed bottom (dismiss «Ho capito») |
| i18n | `user::registration.already_registered`, `user::login.actions.login.label`, `user::auth.login.forgot_password.text` |

**Verifica Playwright `:8000`:** 375 → card ~343 / CTA 52×293; 1440 → card 512 / CTA 52×446; breadcrumb ok; 0 chiavi grezze.

Dominio registrazione resta `Gdpr\RegisterWidget`. Login ha lo stesso isolamento `max-w-md` (fuori scope).

## Pass 2026-10-20b — conflitto merge + parity DC

**Causa:** `auth-register.blade.php` conteneva marker `<<<<<<<` da merge
non risolto → HTTP 500 locale.

**Fix:**

| Pezzo | Scelta |
|-------|--------|
| Shell | riscrittura atomica senza conflitti; card max 36rem; padding cookie |
| Breadcrumb | `Home / heading` senza `span.separator` (evita `/ /` del CSS DC) |
| Form | colonna unica (handoff UX); asterisco required; legend «Dati personali» / «Consensi» |
| Copy IT | `gdpr::register` allineato a prod DC (Indirizzo email, Password, Consensi) |
| CTA | full-width min 44px + loading Livewire |
| Input | bordo box `!important` (DC azzera top/left/right → solo underline) |

**Verifica:** HTTP 200; Playwright 375/1440 card 343/576, CTA h=52, campi stacked, crumb ok.