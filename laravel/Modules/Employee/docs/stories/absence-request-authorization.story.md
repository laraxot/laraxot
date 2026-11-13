---
title: "Employee, autorizzazione delle richieste di assenza (chi chiede, chi decide)"
type: story
module: Employee
status: review
created: 2026-10-29
updated: 2026-10-29
tags: [bmad, authorization, policy, filament, absence-request, security]
qmd: "employee absence request policy authorization manage approve reject viewAny filament get_authorization_response status user_id form"
related:
  - ./mobile-ux-pass.story.md
  - ../business-logic-security.md
  - ../../../Xot/app/Models/Policies/XotBasePolicy.php
---

# Employee, autorizzazione delle richieste di assenza

## Richiesta

Nata dalla passata UX mobile (`mobile-ux-pass.story.md`): rendere facile chiedere un permesso da telefono ha riportato a galla che un dipendente poteva impostare da solo la propria richiesta come «Approvata». Scopo: chi chiede puo' solo chiedere (e ritirare finche' e' in attesa), chi decide e' una regola esplicita, non un effetto collaterale.

## Cosa si e' scoperto (verificato nel codice, non supposto)

| Fatto | Dove |
|---|---|
| `AbsenceRequestPolicy extends UserBasePolicy {}`: corpo vuoto. Dell'intera catena `XotBasePolicy` definisce solo `before()` (super-admin) e `viewAny()` (falso). | `Xot/app/Models/Policies/XotBasePolicy.php` |
| Se la policy esiste ma il metodo no, Filament **consente**: `get_authorization_response` risponde `allow` salvo che un `Gate::before` globale neghi con `false`. La modalita' strict (che lancerebbe un errore) non e' attiva. | `vendor/filament/filament/src/helpers.php` righe 60-92 |
| Il `before()` della policy **non viene chiamato** per un metodo assente (Laravel: `resolvePolicyCallback` ritorna `false` se il metodo non e' richiamabile). Quindi «policy vuota» non e' «tutto chiuso tranne il super-admin». | `Illuminate/Auth/Access/Gate.php` righe 781-785 |
| `CreateRecord` controlla solo `canCreate()`, `EditRecord` solo `canEdit()`: i campi del form non sono mai autorizzati. | `Filament/Resources/Pages/{CreateRecord,EditRecord}.php` |
| Risultato: elenco e menu chiusi ai non super-admin (`viewAny` falso), ma chi arrivava all'URL poteva creare, aprire e modificare, e il server si fidava di `status` e `user_id` del form. | probe sul Gate, 2026-10-29 |
| Lo scope «solo le proprie» dipendeva da `request()->is('admin/*')`: il pannello Employee e' su `employee/admin` e le richieste Livewire arrivano da `livewire/update`, quindi il filtro scattava a caso. | `AbsenceRequestResource::getEloquentQuery()` (versione precedente) |

Lezione: in Filament **il silenzio di una policy non e' un divieto**. Una policy nuova va scritta per intero, ogni abilita' (`view`, `create`, `update`, `delete`, `deleteAny`, `restore*`, `forceDelete*`, `replicate`, `reorder` e quelle delle azioni), anche solo `return false`.

## Cosa e' stato fatto

| File | Modifica |
|---|---|
| `Policies/AbsenceRequestPolicy.php` | Tutte le abilita' scritte. Dipendente: `viewAny`/`create` si'; `view` solo il proprio; `update`/`delete` solo il proprio **e** in attesa. `manage`, `approve`, `reject` e tutto il resto: falso. `super-admin` passa da `before()`. `manage()` e' l'unico punto dove aggiungere un ruolo responsabile. Identificatore confrontato come stringa (`user_id` e' `string(36)`), uno non scalare non possiede nulla. |
| `Filament/Resources/AbsenceRequestResource.php` | `canManageAll()` = `Gate::allows('manage', AbsenceRequest::class)`. `getEloquentQuery()` filtra `user_id` per chi non gestisce, in qualunque pannello e anche nelle richieste Livewire. |
| `Schemas/AbsenceRequestForm.php` | `user_id` (Dipendente) e `status` (Stato) visibili solo a chi gestisce. Al dipendente il form chiede tipo, date, note: un campo in meno su telefono. |
| `Pages/CreateAbsenceRequest.php` | Per chi non gestisce, `status = pending` e `user_id = Auth::id()` impostati dal server (il form non e' fonte fidata). |
| `Pages/EditAbsenceRequest.php` | `mutateFormDataBeforeSave` scarta `status` e `user_id` per chi non gestisce. |
| `Tables/AbsenceRequestsTable.php` | `->authorize('approve')` / `->authorize('reject')` sulle azioni di riga: `visible()` le nasconde, la policy le nega davvero. |
| `tests/Unit/Policies/AbsenceRequestPolicyTest.php` | Matrice completa (vedi sotto). Scritto, **non eseguito**. |

## Verifica

- **Sonda sul Gate vero, senza DB** (utenti Mockery, richieste in memoria): 40 verifiche su 20 abilita'/casi x 2 ruoli, **40 ok, 0 ko**. Dipendente: `viewAny`, `create` si'; `view` solo il proprio; `update`/`delete` solo il proprio in attesa (proprio approvato, proprio rifiutato, altrui: no); `manage`, `approve`, `reject`, `deleteAny`, `restore`, `restoreAny`, `forceDelete`, `forceDeleteAny`, `replicate`, `reorder`: no. Super-admin: tutto si'.
- `AbsenceRequestResource::canManageAll()`: dipendente `false`, super-admin `true`, ospite `false`.
- Query: dipendente `where user_id = ?` con binding `["u1"]` (stringa); super-admin nessun filtro oltre al soft delete.
- Helper del test eseguiti senza app (37 verifiche dirette sulla policy): 37 ok, 0 ko.
- `phpstan analyse Modules/Employee`: **0 errori**, test compresi, senza `@phpstan-ignore` ne' toccare `phpstan.neon`. Un primo passaggio ha segnalato `cast.string` su `getAuthIdentifier()` (`mixed`): risolto con `is_scalar()`, non con un cast piu' largo.
- **Non verificato:** Pest (`.env.testing` e' MySQL), le pagine nel browser (login e DB reale).

## Cambio di comportamento da sapere

Un super-admin nel pannello `employee/admin` ora vede **tutte** le richieste (prima, per un controllo sul percorso, solo le proprie). E' voluto: chi puo' decidere deve vedere cosa decidere.

## Decisioni aperte (all'utente)

1. **Chi e' il «responsabile»?** Oggi solo `super-admin`, quindi nessun altro puo' approvare. Per aprire a un ruolo basta una riga in `AbsenceRequestPolicy::manage()`, ma serve sapere quale ruolo esiste davvero.
2. **Le docs di sicurezza descrivono un sistema che nel codice non c'e'.** `business-logic-security.md` parla di `hr_admin`, `dept_manager`, `team_lead`, `employee`, `contractor` e di `super_admin` con underscore; il codice non cita nessuno di quei ruoli e usa `super-admin` (121 occorrenze). Da riallineare in una story a parte, dopo la decisione 1.
3. **`WorkHourPolicy` e' ancora vuota** (stesso difetto: `viewAny` negata, il resto non scritto) e `WorkHourResource` non ha scope per utente. Non toccata qui.
4. `AbsenceRequest` dichiara `@property int $user_id` ma la colonna e' `string(36)` (la migrazione ammette UUID): il phpdoc del modello e' sbagliato; il confronto in policy e' gia' per stringa.
