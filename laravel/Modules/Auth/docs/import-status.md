---
title: "Import status — Auth (Wave 3 replica, DO NOT DELETE)"
type: concept
tags: [auth, replica, import, wave3, do-not-delete]
created: 2026-07-23
updated: 2026-07-24
qmd: "Auth import status wave3 replica reshape enabled do not delete"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./composer-manifest.md"
  - "./README.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../docs/wiki/rules/module-name-singular-mandatory.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Auth

> **Fase:** replica Wave 3 (Auth, Document, Email, HR, Platform, WhatsApp).
> Copiato da `gestionale_commesse`, reshape-ato come cartella indipendente.
> **Non** merge in moduli nativi (User, Employee, Media, Notify, AI, Tenant).
> **Non** cancellare.

## Checklist reshape

- [x] Layout sotto `app/` (Application/Domain/Infrastructure/Filament/Http/Console/Providers root rimossi)
- [x] Database/Resources/Routes (capital) spostati in database/resources/routes (lowercase)
- [x] Namespace PHP allineati al nuovo path (rimosso segmento Application/Domain/Infrastructure)
- [x] ServiceProvider principale estende `XotBaseServiceProvider`
- [x] `RouteServiceProvider` estende `XotBaseRouteServiceProvider`
- [x] `EventServiceProvider` estende `Modules\Xot\Providers\XotBaseEventServiceProvider` (2026-07-24: corretto da `Illuminate\...\EventServiceProvider`)
- [x] `composer.json` Laraxot PSR-4 → `app/` (namespace verificato: singolo backslash)
- [x] Azioni con `execute()` → trait `Spatie\QueueableAction\QueueableAction` (rimosso `readonly` dalla classe, incompatibile con la property non-readonly del trait)
- [x] Filament Resource CRUD principali → `extends XotBaseResource` (`form()` → `getFormSchema(): array`)
- [x] Filament Pages → `extends XotBase{List,Create,Edit,View}Record` (2026-07-24)
- [x] `lang/{it,en}/auth.php` (2026-07-24, prima assente)
- [ ] BaseModel → XotBaseModel: N/A per questo modulo (Auth non ha un `app/Models/User` proprio, usa `Modules\User\Models\User`)
- [ ] Migrazioni `users` consolidate/deduplicate rispetto a `Modules\User` (TODO esplicito, vedi `gestionale-commesse-port.md`)
- [ ] Pest test (0 test presenti — TODO)
- [x] `modules_statuses=true` (2026-07-24) — resta **replica**, non merge in User

## PHPStan

```
cd laravel && ./vendor/bin/phpstan analyse Modules/Auth --memory-limit=-1
```

Esito (2026-07-24): **0 errori reali**. Rimane solo il warning artefatto
`Ignored error pattern #PHPDoc tag @mixin contains unknown class # was not matched in reported errors.`,
dovuto al `phpstan.neon` di root (`ignoreErrors` globale) quando si analizza un solo modulo isolato — stesso
comportamento riprodotto su altri moduli (WorkOrder, Quotation), non modificabile da qui (root
`phpstan.neon` fuori scope) e non un errore del codice Auth.

### Bug funzionali reali trovati e corretti

Causa radice comune: quasi tutto il modulo importava `App\Models\User` (uno stub quasi vuoto in
`app/Models/User.php`, senza ruoli/permessi) invece del vero modello di autenticazione configurato in
`config/auth.php` (`Modules\User\Models\User`, con `HasRoles`/`HasPermissions` via `HasSpatiePermission`).
Conseguenze runtime concrete, non solo tipizzazione:

- **Login sempre "non validato"**: in `LoginController::store()` il check
  `$user instanceof \App\Models\User` era sempre `false` (l'utente reale è
  `Modules\User\Models\User`), quindi il blocco che verifica approvazione account, ruoli
  ammessi al pannello (admin/operator) e attivazione 2FA email non veniva **mai eseguito**:
  chiunque poteva fare login e bypassare approvazione/2FA.
- **2FA email sempre bloccata**: in `EmailTwoFactorController::verify()`/`resend()`,
  `abort_unless($user instanceof User, 403)` con lo stesso `User` sbagliato faceva **sempre**
  abortire con 403 — il flusso di verifica/reinvio codice 2FA era di fatto irraggiungibile.
  Stesso problema di tipo nei parametri di `StartEmailTwoFactorChallengeAction` e
  `VerifyEmailTwoFactorCodeAction` (avrebbero comunque fallito a runtime con
  `TypeError` non fatale in PHP non strict, ma logicamente sbagliati).
- **Registrazione/creazione utenti da comando rotta**: `RegisterController::store()` e tutti
  gli 8 comandi `Create*PanelUserCommand` chiamavano `User::firstOrCreate()`/`->syncRoles()`
  sullo stub `App\Models\User`, che non ha il trait `HasRoles` → `Call to undefined method
  syncRoles()` a runtime (fatal error).
- **Pulsante "Approva utente" mai visibile**: in `UserResource` la visibilità dell'azione
  dipendeva da `method_exists($record, 'isApproved')`, ma `isApproved()` non esiste da
  nessuna parte nel repo → il bottone di approvazione admin non compariva mai.
- **Foreign key incompatibili nelle migration**: `users.id` è `string(36)` (UUID), ma le
  migration `2026_07_21_120000_add_manager_to_users.php` e
  `2026_05_06_140000_add_user_approval_fields.php` usavano `$table->foreignId(...)`
  (`unsignedBigInteger`) per `manager_id`/`approved_by_user_id` referenziando `users.id`:
  vincolo di foreign key con tipi incompatibili. Corretto in `foreignUuid(...)`.

Fix applicato: sostituito ovunque `use App\Models\User;` con `use Modules\User\Models\User;`
(Actions, Console Commands, Controllers, Seeders), sostituiti gli accessi a colonne custom
(`approved_at`, `manager_id` — aggiunte dalle migration di questo modulo e non presenti nel
docblock del model, che vive in `Modules/User` fuori scope) con `getAttribute()`/`setAttribute()`
guardati, e sostituita la chiamata inesistente `AccountFeatures::canAccessPanel()` con i metodi
realmente esistenti (`hasAdministrativeRole()`, `hasRole('operator')`). `GetTeamMemberIdsAction`/
`ResolveTeamMemberIdsAction` tipizzavano gli id utente come `int`: corretti a `string` (UUID).

## Pest

```
cd laravel && vendor/bin/pest Modules/Auth/tests
```

Esito: **nessun test presente** (solo `tests/Feature/.gitkeep` e `tests/Unit/.gitkeep`). Non è
stato possibile "verificare che i test passino" perché non esistono test per questo modulo:
resta un TODO esplicito aperto (vedi sotto).

## TODO espliciti (rimandati da questo giro)

- BaseModel → XotBaseModel per i model in `app/Models`
- Consolidamento/dedup migrations, factories, seeders
- Copertura Pest: **0 test esistenti**, da scrivere da zero (login con account non approvato,
  2FA email verify/resend, approvazione utente da `UserResource`, gerarchia manager/collaboratori)
