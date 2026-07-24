---
title: "Debito D10 — collisione route GET /login (Auth vs User vs Folio)"
type: how-to
tags: [technical-debt, auth, user, login, routes, folio]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt login route collision auth user folio legacy.login"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
  - "https://github.com/laraxot/base_workorder_fila5/issues/9"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-replica-auth-hr-enable.md"
---

# Debito D10 — collisione `GET /login`

## Perché (business)

Con `Auth` e `User` entrambi **enabled**, lo stesso URI HTTP può essere registrato da più layer. Laravel **non** fallisce al boot se i **name** delle route differiscono: vince l’ultimo `ServiceProvider` che registra — comportamento **silenzioso** e dipendente dall’ordine di boot dei moduli.

Login FO è cuore prodotto: non può dipendere dall’ordine alfabetico di `module.json`.

## Sintomo / evidenza (come verificare)

```bash
cd laravel
php artisan route:list --path=login
# Contare quante volte appare Method=GET e URI=login (o /{locale}/login)
```

Cercare registrazioni tipiche:

| Origine | Pattern tipico | Name tipico |
|---|---|---|
| Auth replica SRC | `Route::get('/login', …)` in `Modules/Auth/routes/*` | spesso `legacy.login` |
| User web | redirect verso panel Filament | `login` |
| User Folio | file `resources/views/pages/auth/login.blade.php` | name Folio auto |

```bash
rg -n "Route::get\\(['\\\"]/login" Modules/Auth/routes Modules/User/routes
ls Modules/User/resources/views/pages/auth/login.blade.php 2>/dev/null
```

## Impatto

- Login “a volte” Auth SRC, “a volte” redirect Admin, “a volte” Folio.
- Bypass involontario di 2FA/approvazione se il controller sbagliato risponde (già visto in Auth quando puntava a `App\Models\User` stub).
- Test Pest su `/login` flaky.

## Come risolvere (scegliere **una** opzione e documentarla su Issue #7/#9)

### Opzione A — Prefisso Auth replica (consigliata in fase replica)

**Idea:** Auth resta ispezionabile, non compete su `/login` pubblico.

1. Story BMAD + conferma: “login FO = User Folio”.
2. Wrappare le route Auth:
   ```php
   Route::prefix('legacy-auth')->middleware(['web'])->group(function (): void {
       Route::get('/login', [LoginController::class, 'show'])->name('legacy.login');
       // register, 2fa, …
   });
   ```
3. Aggiornare link interni Auth (`route('legacy.login')`, redirect post-logout).
4. Verifica:
   ```bash
   php artisan route:list --path=login
   # UNA sola GET “pubblica” per il login FO; Auth sotto /legacy-auth/login
   ```
5. Pest: hit FO login User; hit `/legacy-auth/login` Auth smoke.

### Opzione B — Auth diventa login FO (solo con mandato prodotto)

1. Decision log esplicito (rompe religione “User Folio primario”).
2. Disabilitare/redirect le route User che registrano `/login`.
3. Folio: non montare `pages/auth/login` **oppure** redirect a Auth.
4. Gap analysis STORY-001 completa prima.

### Opzione C — Disable Auth finché A/B non è fatta

```json
"Auth": false
```

Chiave **presente**. Riduce blast radius; non chiude il debito di contenuto Auth.

## Checklist agente

- [ ] `route:list --path=login` allegato a commento GitHub (prima/dopo)
- [ ] Nessun `rm` cartella Auth
- [ ] Login FO smoke manuale o Pest
- [ ] Aggiornare inventario [gestionale-technical-debt.md](./gestionale-technical-debt.md) (D10 stato)

## DoD

- [ ] Al più **una** registrazione GET per il login FO pubblico scelto
- [ ] Auth (se enabled) non shadowa silenziosamente quel path
- [ ] Decisione A/B/C scritta su Issue

## Non fare

- Lasciare “tanto funziona oggi”.
- Rinominare solo il `->name()` lasciando lo stesso URI.
- Fondere Auth in User senza STORY-001 + OK utente.
