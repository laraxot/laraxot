---
title: "Porting gestionale_commesse — HR (reshape finale, permanente e separato da Employee)"
type: concept
tags: [import, gestionale-commesse, hr, xotbase, reshape, moduli-gemelli]
created: 2026-07-24
updated: 2026-07-24
qmd: "porting HR gestionale_commesse XotBase reshape permanente separato Employee"
related:
  - "./import-status.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
  - "../../../../docs/gestionale-commesse-comparison/STORY-002-hr-vs-employee.md"
---

# Porting gestionale_commesse — HR (2026-07-24)

## Decisione di contesto

Audit BMAD readiness-check (2026-07-24) ha rilevato `HR` come copia grezza in limbo, `false` in
`modules_statuses.json`. **Decisione utente finale**: `HR` resta un **modulo permanente separato**
da `Employee`, reshapato come gli altri 16 moduli già completati. Il gap-fill di
`AbsenceRequest`/anagrafica eventualmente già presente in `Employee` resta com'è, non viene
rimosso: `HR` non lo sostituisce, coesiste come dominio a parte (vedi
[STORY-002](../../../../docs/gestionale-commesse-comparison/STORY-002-hr-vs-employee.md) per
l'analisi comparativa completa: `Employee` è maturo su timbrature/dashboard, `HR` porta un sistema
reale di assenze/documenti/visite mediche/formazione che in `Employee` esisteva solo come mock UI).

## Stato all'ingresso di questa sessione

Layout già sotto `app/`, `HrServiceProvider extends XotBaseServiceProvider` con `Gate::policy()`
per `Employee`/`AbsenceRequest`, `RouteServiceProvider extends XotBaseRouteServiceProvider`, 5
Actions con trait `QueueableAction`, 2 Filament Resource (`EmployeeResource`,
`AbsenceRequestResource`) già `extends XotBaseResource`, 3 RelationManager su `EmployeeResource`
(documenti, visite mediche, formazione).

Problemi trovati a inizio sessione:

1. **`EventServiceProvider`** estendeva ancora `Illuminate\...\EventServiceProvider` invece di
   `Modules\Xot\Providers\XotBaseEventServiceProvider` (stesso pattern-bug di altri ~9 moduli).
2. **Modelli non estendevano `XotBaseModel`**: tutti e 5 (`Employee`, `AbsenceRequest`,
   `EmployeeMedicalVisit`, `EmployeeTraining`, `EmployeeDocument`) estendevano
   `Illuminate\Database\Eloquent\Model` direttamente, senza `HasFactory`/`HasXotFactory`.
3. **`App\Models\User`** invece di `Modules\User\Models\User` in `Employee.php`,
   `AbsenceRequest.php`, `EmployeeResource.php` — stesso bug radice già diagnosticato e corretto in
   `Auth` da una sessione precedente (vedi `Modules/Auth/docs/import-status.md`), non ancora
   applicato qui.
4. **Migrazioni non consolidate 1-per-modello**: un unico file
   `2026_02_16_100154_create_hr_tables.php` creava 4 tabelle (`employees`,
   `employee_medical_visits`, `employee_trainings`, `employee_documents`) con `Schema::create`
   diretto (niente `XotBaseMigration`/`tableCreate()`/`tableUpdate()`/`updateTimestamps()`), più un
   file separato `add_media_to_employee_visits_and_trainings.php` che alterava 2 di quelle tabelle
   appena create nello stesso "giro" concettuale (da consolidare, non da tenere come alter
   separato). Inoltre `users.id` è **string(36) UUID** in questo monorepo
   (`Modules/User/database/migrations/..._create_users_table.php`), ma i FK verso `users`
   (`employees.user_id`, `absence_requests.user_id`, `absence_requests.decided_by_user_id`)
   usavano `$table->foreignId(...)` (`unsignedBigInteger`) — mismatch di tipo che avrebbe fatto
   fallire il vincolo FK a runtime.
5. **Nessuna factory** (`database/factories/` non esisteva).
6. **Nessun `lang/`**: tutte le label in `EmployeeResource.php`, `AbsenceRequestResource.php`, e
   nelle 3 RelationManager usavano già `__('hr.xxx')`, ma il file `lang/{it,en}/hr.php` non
   esisteva — errore silente a runtime (fallback al testo della chiave).
7. **RelationManager non estendevano `XotBaseRelationManager`**: le 3 RelationManager
   (`DocumentsRelationManager`, `MedicalVisitsRelationManager`, `TrainingsRelationManager`)
   estendevano `Filament\Resources\RelationManagers\RelationManager` direttamente con `form()`
   pubblico — pattern non allineato a `XotBaseRelationManager` (che rende `form()` `final` e
   richiede `getFormSchema(): array`), diversamente da `Bom/app/Filament/Resources/BomResource/RelationManagers/BomItemsRelationManager.php`
   preso come riferimento.
8. **Filament Pages**: tutte e 8 le pagine (`EmployeeResource` + `AbsenceRequestResource`, 4
   ciascuna) estendevano `Filament\Resources\Pages\*` invece di
   `Modules\Xot\Filament\Resources\Pages\XotBase*`.
9. **`composer.json`**: nessun secondo autore, nessun blocco `require` con
   `spatie/queueable-action`.

## Interventi di questa sessione

1. `EventServiceProvider` → classe vuota `extends XotBaseEventServiceProvider`.
2. Tutti e 5 i modelli → `extends XotBaseModel`, aggiunto `use HasFactory` (con `@use
   HasFactory<{Model}Factory>`), stesso pattern di `Bom`/`Billing` (che estendono `XotBaseModel` e
   ridichiarano comunque `HasFactory` per compatibilità con `HasXotFactory::newFactory()`).
3. `App\Models\User` → `Modules\User\Models\User` in `Employee.php`, `AbsenceRequest.php`,
   `EmployeeResource.php`.
4. **Migrazioni consolidate 1-per-modello** con `XotBaseMigration`/`tableCreate()`/`tableUpdate()`/
   `updateTimestamps()`, FK verso `users` corretti a `foreignUuid(...)`:
   - `2026_02_16_100154_create_employees_table.php`
   - `2026_02_16_100155_create_employee_medical_visits_table.php` (con `media_id`/`title` già
     inclusi nella create, non più come alter separato)
   - `2026_02_16_100156_create_employee_trainings_table.php` (idem)
   - `2026_02_16_100157_create_employee_documents_table.php`
   - `2026_02_17_120000_create_absence_requests_table.php` (`user_id`/`decided_by_user_id` →
     `foreignUuid`)
   - Rimossi: `create_hr_tables.php` (unico file con 4 `Schema::create`),
     `add_media_to_employee_visits_and_trainings.php` (merge nella create).
5. **Factories create** (prima assenti): `EmployeeFactory`, `EmployeeMedicalVisitFactory`,
   `EmployeeTrainingFactory`, `EmployeeDocumentFactory`, `AbsenceRequestFactory` — usano
   `{Model}Factory::new()` per le dipendenze cross-model invece di `Model::factory()` diretto,
   perché Larastan non riconosce lo static method `factory()` fornito da `HasXotFactory` (custom
   wrapper) su model diversi da quello sotto analisi diretta (falso positivo
   `staticMethod.notFound`, verificato con `phpstan analyse` in questa sessione prima del blocco
   sul modulo `Employee`, vedi sezione Verifica).
6. **`lang/{it,en}/hr.php`** creati con tutte le chiavi effettivamente referenziate nel codice
   (`navigation`, `employee(s)`, `absence_request(s)`, `documents`, `medical_visits`, `trainings`,
   `fields.*`, `statuses.*`, `absence_types.*`, `actions.*`).
7. **RelationManager** convertite a `extends Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager`,
   `form(Schema)` → `getFormSchema(): array` (pattern preso da
   `Bom/BomItemsRelationManager`), `table(Table)` invariato (non `final` nella classe base).
8. **Filament Pages**: tutte e 8 convertite a `XotBase{ListRecords,CreateRecord,EditRecord,ViewRecord}`
   (drop-in, nessun metodo astratto aggiuntivo richiesto).
9. **`composer.json`**: aggiunto secondo autore (Davide Cavallini) e `require.spatie/queueable-action`.

## Non ancora fatto (TODO esplicito)

- Pest: nessun test presente (`tests/Feature/.gitkeep`, `tests/Unit/.gitkeep`). Da scrivere:
  creazione `Employee`, ciclo di vita `AbsenceRequest` (create/approve/reject con scoping
  admin/operator), upload documento/visita medica/formazione via RelationManager.
- Nessuna verifica automatica del merge concettuale con `Employee` (i due moduli restano
  intenzionalmente separati per decisione utente, ma un futuro audit dovrebbe verificare che non
  emergano due `AbsenceRequest` differenti attive contemporaneamente sullo stesso dominio utente,
  se `Employee` dovesse mai implementare la propria versione del gap descritto in STORY-002).

## Verifica

- `php -l` su tutti i file del modulo (dopo tutte le modifiche): **OK**.
- `phpstan analyse Modules/HR Modules/Auth --memory-limit=-1`: prima fase di analisi ha rilevato 4
  errori `staticMethod.notFound` su `Employee::factory()` nelle factory cross-model (vedi punto 5
  sopra) — **risolti** passando a `EmployeeFactory::new()`. Una seconda esecuzione è stata
  temporaneamente **bloccata** da un errore di bootstrap non originato da questo modulo (`Class
  "Modules\Employee\Filament\Resources\AbsenceRequestResource\Pages\ListAbsenceRequests" not
  found`, lock attivo di un altro agente su `Modules/Employee`, verificato con
  `bashscripts/lock/check.sh`). Dopo il rilascio del lock, rieseguita: emersi e corretti 4 errori
  reali aggiuntivi — `ViewEmployee`/`ViewAbsenceRequest` non implementavano `getInfolistSchema()`
  (astratto in `XotBaseViewRecord`, aggiunto con schema vuoto), `ListEmployees`/
  `ListAbsenceRequests::getHeaderActions()` restituivano un array a chiavi numeriche invece che
  `array<string, Action>` (aggiunta chiave `'create'`). Esecuzione finale: **0 errori reali**, resta
  solo il warning artefatto `Ignored error pattern #PHPDoc tag @mixin contains unknown class # was
  not matched in reported errors` (stesso comportamento riprodotto su altri moduli quando
  analizzati isolatamente). La sessione precedente a questa riportava 39 errori pre-esistenti su
  `HR` (tipizzazioni Spatie Permission su `App\Models\User`, ecc.): risolti dai fix di questa
  sessione (modelli → `XotBaseModel`, `App\Models\User` → `Modules\User\Models\User`, ecc.).

## Stato registrazione

`HR` **abilitato** (`true`) in `modules_statuses.json` in questa sessione, dopo PHPStan pulito.
