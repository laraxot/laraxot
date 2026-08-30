---
title: "Import status — HR (Wave 3 replica, DO NOT DELETE)"
type: concept
tags: [hr, replica, import, wave3, do-not-delete]
created: 2026-07-23
updated: 2026-07-24
qmd: "HR import status wave3 replica reshape enabled do not delete"
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

# Import status — HR

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
- [x] RelationManager → `extends XotBaseRelationManager` (2026-07-24)
- [x] BaseModel → XotBaseModel per tutti e 5 i model (2026-07-24)
- [x] Migrazioni consolidate 1-per-modello con `XotBaseMigration`/`tableCreate()`/`tableUpdate()`, FK verso `users` corretti a `foreignUuid` (2026-07-24)
- [x] Factories create per tutti e 5 i model (2026-07-24, prima assenti)
- [x] `lang/{it,en}/hr.php` (2026-07-24, prima assente)
- [x] Pest test — EmployeeCrud + type normalization **green**; media/workflow **skip** D1/D6 (2026-07-24) — [tests-migration.md](./tests-migration.md)
- [x] `modules_statuses=true` (2026-07-24) — resta **replica**, non merge in Employee

## PHPStan

```
cd laravel && ./vendor/bin/phpstan analyse Modules/HR --memory-limit=-1
```

Esito: **39 errori** (baseline dopo reshape, in gran parte pre-esistenti nel sorgente originale:
tipizzazioni Spatie Permission su `App\Models\User`, migrations generiche, ecc. — non introdotti da questo reshape).

## TODO espliciti (rimandati da questo giro)

- BaseModel → XotBaseModel per i model in `app/Models`
- Consolidamento/dedup migrations, factories, seeders
- Copertura Pest

## Delta seconda tranche (2026-08-30)

Sostanza del delta sorgente: solo gating navigazione —
`AbsenceRequestResource` ed `EmployeeResource` → `GatedXotBaseResource`
(il resto era riallineamento Pint). Suite: 3 pass / 5 skip (gli skip sono
guard-clause su tabelle non presenti nella fixture condivisa).
