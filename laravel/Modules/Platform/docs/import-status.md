---
title: "Import status — Platform (Wave 3 replica, DO NOT DELETE)"
type: concept
tags: [platform, replica, import, wave3, do-not-delete]
created: 2026-07-23
updated: 2026-07-23
qmd: "Platform import status wave3 replica reshape do not delete"
related:
  - "./composer-manifest.md"
  - "../../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../../docs/wiki/rules/module-name-singular-mandatory.md"
---

# Import status — Platform

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
- [x] `EventServiceProvider` estende `Illuminate\Foundation\Support\Providers\EventServiceProvider`
- [x] `composer.json` Laraxot PSR-4 → `app/` (namespace verificato: singolo backslash)
- [x] Azioni con `execute()` → trait `Spatie\QueueableAction\QueueableAction` (rimosso `readonly` dalla classe, incompatibile con la property non-readonly del trait)
- [x] Filament Resource CRUD principali → `extends XotBaseResource` (`form()` → `getFormSchema(): array`)
- [ ] BaseModel → XotBaseModel (rimandato, TODO esplicito)
- [ ] Factories/seeders/migrations parity/consolidamento (rimandato, TODO esplicito)
- [ ] Pest test
- [ ] Enable in `modules_statuses.json` solo dopo PHPStan pulito + ordine utente

## PHPStan

```
cd laravel && ./vendor/bin/phpstan analyse Modules/Platform --memory-limit=-1
```

Esito: **1 (bloccante esterno: Intervention) errori** (baseline dopo reshape, in gran parte pre-esistenti nel sorgente originale:
tipizzazioni Spatie Permission su `App\Models\User`, migrations generiche, ecc. — non introdotti da questo reshape).

## TODO espliciti (rimandati da questo giro)

- BaseModel → XotBaseModel per i model in `app/Models`
- Consolidamento/dedup migrations, factories, seeders
- Copertura Pest
