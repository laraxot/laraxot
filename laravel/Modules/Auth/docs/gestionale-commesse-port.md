---
title: "Porting gestionale_commesse — Auth (reshape finale, permanente e separato da User)"
type: concept
tags: [import, gestionale-commesse, auth, xotbase, reshape, moduli-gemelli]
created: 2026-07-24
updated: 2026-07-24
qmd: "porting Auth gestionale_commesse XotBase reshape permanente separato User"
related:
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
  - "../../../../docs/gestionale-commesse-comparison/STORY-001-auth-vs-user.md"
---

# Porting gestionale_commesse — Auth (2026-07-24)

## Decisione di contesto

Audit BMAD readiness-check (2026-07-24) ha rilevato `Auth` come copia grezza in limbo, `false` in
`modules_statuses.json`, né fusa in `User` né rimossa. **Decisione utente finale**: `Auth` resta un
**modulo permanente separato**, reshapato come gli altri 16 moduli già completati (vedi
[porting-external-module-playbook.md § Moduli "gemelli"](../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md)).
Il gap-fill già presente in `User` (analisi 2FA) resta com'è.

## Stato all'ingresso di questa sessione

Una sessione precedente (vedi `docs/import-status.md`) aveva già portato molto del reshape: layout
sotto `app/`, `AuthServiceProvider extends XotBaseServiceProvider`, `RouteServiceProvider extends
XotBaseRouteServiceProvider`, Actions con `QueueableAction`, `UserResource extends XotBaseResource`,
e soprattutto aveva già trovato e corretto il bug radice più grave: quasi tutto il modulo importava
`App\Models\User` (uno stub quasi vuoto in `app/Models/User.php` dell'app host, senza ruoli/permessi)
invece del vero modello di autenticazione configurato in `config/auth.php`
(`Modules\User\Models\User`, con `HasRoles`/`HasPermissions`). Questo causava, prima della fix:
login sempre "non validato" (bypass di approvazione/2FA), 2FA email sempre bloccata (403), comandi
`Create*PanelUserCommand` con `Call to undefined method syncRoles()` fatale, bottone "Approva utente"
mai visibile. Vedi `docs/import-status.md` per il dettaglio completo di quella sessione.

Restavano aperti, a inizio di questa sessione: `EventServiceProvider` che estendeva ancora
`Illuminate\Foundation\Support\Providers\EventServiceProvider` invece di
`Modules\Xot\Providers\XotBaseEventServiceProvider` (bug pattern già noto, trovato/corretto in
~9 altri moduli nel corso del progetto), 2 riferimenti residui a `App\Models\User`
(`UserResource.php`, `EnsureEmailTwoFactorVerified.php`, `UserFactory.php`), nessun `lang/`, Pages
Filament che estendevano `Filament\Resources\Pages\*` invece di `Modules\Xot\Filament\Resources\Pages\XotBase*`,
`composer.json` senza secondo autore (convenzione repo) e senza `require.spatie/queueable-action`.

## Interventi di questa sessione

1. **`EventServiceProvider`**: riscritto come classe vuota `extends XotBaseEventServiceProvider`
   (era `extends Illuminate\...\EventServiceProvider` con `$listen`/`$shouldDiscoverEvents`
   ridichiarati manualmente — pattern deprecato, XotBase li gestisce già).
2. **`App\Models\User` residuo**: sostituito con `Modules\User\Models\User` in
   `app/Filament/Resources/UserResource.php`, `app/Http/Middleware/EnsureEmailTwoFactorVerified.php`,
   `database/factories/UserFactory.php` (incluso il PHPDoc `@extends Factory<...>`).
3. **Filament Pages**: tutte e 4 le pagine di `UserResource` (`ListUsers`, `CreateUser`, `EditUser`,
   `ViewUser`) convertite da `Filament\Resources\Pages\{ListRecords,CreateRecord,EditRecord,ViewRecord}`
   a `Modules\Xot\Filament\Resources\Pages\XotBase{ListRecords,CreateRecord,EditRecord,ViewRecord}`
   (drop-in, nessun metodo astratto aggiuntivo richiesto da queste classi base).
4. **Lang**: creati `lang/it/auth.php` e `lang/en/auth.php` (prima assenti) con tutte le chiavi
   effettivamente usate nel codice (`auth.navigation`, `auth.fields.*`, `auth.hierarchy.*`,
   `auth.status.*`, `auth.actions.approve`, `auth.two_factor.*`, `auth.invalid_credentials`,
   `auth.no_role_assigned`). Le label in `UserResource.php` erano già scritte come `__('users.xxx')`
   puntando a un namespace `users` che di fatto è di proprietà del modulo `User`
   (`Modules/User/lang/it/users.php`, incompleto e privo di `en/`, senza le chiavi
   `hierarchy.*`/`status.*`/`actions.approve` necessarie qui): per evitare collisione/dipendenza
   cross-modulo su chiavi non garantite, le label sono state rinominate `auth.*` (namespace di
   proprietà di questo modulo) e popolate qui.
5. **`composer.json`**: aggiunto secondo autore (Davide Cavallini, convenzione repo) e blocco
   `require` con `spatie/queueable-action` (le Action del modulo usano già il trait
   `QueueableAction`, ma la dipendenza non era dichiarata esplicitamente nel modulo).

## Master password provider — ESCLUSO ESPLICITAMENTE

`app/Auth/MasterPasswordEloquentUserProvider.php` e `app/Support/MasterPasswordContext.php`
implementano un provider di autenticazione che accetta, oltre alla password reale dell'utente, una
"master password" di servizio/emergenza letta da `config('auth.master_password')`. **Questa
feature non viene portata/attivata/documentata come utilizzabile**: resta nel codice sorgente per
fedeltà alla copia (non viene cancellata la classe), ma:

- non è referenziata in nessun `config/auth.php` reale, né nel `module.json`/`composer.json` come
  provider effettivo;
- non va mai collegata al guard `web`/`admin` di produzione;
- qualunque futura richiesta di riattivarla richiede una decisione di sicurezza esplicita separata.

Motivo: un meccanismo di bypass password universale è un rischio di sicurezza serio (già segnalato
nell'analisi precedente `docs/gestionale-auth-vs-user.md` e in STORY-001, entrambi con la stessa
raccomandazione "da NON portare").

## Non ancora fatto (TODO esplicito, ereditato dalla sessione precedente)

- **Migrazioni non consolidate 1-per-modello**: il modulo estende la tabella condivisa `users`
  (di proprietà del modulo `User`) con 4 migration di alter (`add_customer_id`,
  `revoke_shared_demo_account_access`, `add_user_approval_fields`, `add_manager_to_users`), oltre
  a una migration `0001_01_01_000000_create_users_table.php` che **duplica la creazione** di
  `users`/`password_reset_tokens`/`sessions` già create da `Modules\User` (6 migration proprie,
  vedi `Modules/User/database/migrations/*create_users_table*`). Questo è un conflitto potenziale
  se entrambi i moduli venissero mai abilitati contemporaneamente in un ambiente che esegue tutte
  le migration da zero (ordine alfabetico: il file `0001_01_01_...` di Auth verrebbe eseguito
  *prima* delle migration 2024/2026 di User, creando la tabella, poi la migration di User fallirebbe
  su "tabella già esistente" se non protetta da controllo idempotente). **Non risolto in questa
  sessione** per rischio di toccare/duplicare la logica del modulo `User` (fuori scope, modulo
  distinto con la propria ownership) — da trattare come debito tecnico tracciato, non da ignorare:
  la soluzione corretta è probabilmente rimuovere `0001_01_01_000000_create_users_table.php` da
  `Auth` (il modulo `User` già crea `users`/sessions/password_reset_tokens) e lasciare in `Auth`
  solo le migration di alter (`add_customer_id`, `add_user_approval_fields`, `add_manager_to_users`,
  `revoke_shared_demo_account_access`), ma questo richiede coordinamento esplicito con chi possiede
  il modulo `User` prima di procedere.
- Nessun test Pest (`tests/Feature/.gitkeep`, `tests/Unit/.gitkeep` soltanto). Da scrivere: login
  con account non approvato, 2FA email verify/resend, approvazione utente da `UserResource`,
  gerarchia manager/collaboratori.

## Verifica

- `php -l` su tutti i file del modulo (dopo tutte le modifiche di questa sessione): **OK**, nessun
  errore di sintassi.
- `phpstan analyse Modules/Auth Modules/HR --memory-limit=-1`: inizialmente **bloccato** da un
  errore di bootstrap non originato da questo modulo (`Class
  "Modules\Employee\Filament\Resources\AbsenceRequestResource\Pages\ListAbsenceRequests" not
  found`, modulo `Employee` sotto lock di un altro agente). Dopo l'attesa e il rilascio del lock
  (`bashscripts/lock/check.sh laravel/Modules/Employee` → `FREE`), rieseguito con successo: **0
  errori reali**, resta solo il warning artefatto `Ignored error pattern #PHPDoc tag @mixin
  contains unknown class # was not matched in reported errors` (stesso comportamento riprodotto su
  altri moduli quando analizzati isolatamente, non un errore del codice Auth). Nel run intermedio
  (prima del blocco) erano emersi e sono stati corretti: `ViewUser` non implementava
  `getInfolistSchema()` (astratto in `XotBaseViewRecord`, aggiunto con schema vuoto).

## Stato registrazione

`Auth` **abilitato** (`true`) in `modules_statuses.json` in questa sessione, dopo PHPStan pulito.
Resta un TODO esplicito il consolidamento delle migrazioni `users` (vedi sopra) — non bloccante per
l'abilitazione perché le migration esistenti sono comunque valide/idempotenti singolarmente, ma da
risolvere prima di eseguire `migrate` da zero in un ambiente che include anche il modulo `User`.
