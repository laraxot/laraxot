---
name: "Compliance test coverage"
description: "PHPStan/Pest baseline and delta for the 2026-09-06 Filament v5 + type-safety fix pass"
metadata:
  module: Compliance
  updated: 2026-09-06
---

# Coverage e qualita' — Compliance

## PHPStan (level max, `laravel/phpstan.neon`, invariato)

| Momento | Errori |
|---|---|
| Baseline (prima) | 22 |
| Dopo il fix | 0 |

Categorie risolte:
- 19 deprecazioni Filament v5 (`Table::actions()` -> `recordActions()`, `Table::bulkActions()` -> `toolbarActions()`, `Action::form()` -> `Action::schema()`, `Placeholder::make()->content()` -> `TextEntry::make()->state()`).
- 3 `cast.string`/`cast.int` su valori `mixed` nei seeder demo (`ComplianceDemoSeeder`, `HeatingDemoSeeder`), risolti con type narrowing (`is_string()`/`is_numeric()`), non con cast forzati.

## PHPMD (`laravel/phpmd.xml`)

Nessuna nuova violazione introdotta dai rename Filament (i file Resource/RelationManager restano puliti). Nei seeder, il narrowing per i 3 errori PHPStan aggiunge inevitabilmente un ramo condizionale ciascuno (ternario), il che sposta di poco un debito tecnico gia' preesistente:

| File | Regola | Prima | Dopo |
|---|---|---|---|
| `database/seeders/ComplianceDemoSeeder.php` | `ExcessiveClassComplexity` | 60 (gia' oltre soglia 60) | 62 |
| `database/seeders/ComplianceDemoSeeder.php` | `ExcessiveMethodLength` (`documentPng()`) | 101 righe (gia' oltre soglia 100) | 101 righe (invariato) |

Tutte le altre voci PHPMD pre-esistenti (CyclomaticComplexity di `seedDocuments()`, `ShortVariable`, `ShortMethodName`, `UnusedFormalParameter` nelle Policy, `CamelCasePropertyName` nei Provider nwidart) sono debito tecnico non toccato da questa story, verificato identico prima/dopo con `git show HEAD:<file> | phpmd`.

phpinsights non e' installato in questo repo (rimosso, incompatibile con Pest 5).

## Pest

| Momento | Passed | Failed | Assertions |
|---|---|---|---|
| Baseline (prima) | 18 | 4 | 44 |
| Dopo il fix | 22 | 4 | 51 |

Aggiunto `tests/Unit/ComplianceDocumentFilePreviewTest.php` (4 test, 7 assertion) che copre `ComplianceDocumentResource::filePreview()` — il metodo dietro alla nuova `TextEntry::make('file_preview')->state(...)` che sostituisce il deprecato `Placeholder::content()`: nessun media, media immagine, media non immagine, record null.

I 4 test che falliscono **prima e dopo** (nessuna regressione, non toccati da questa story):
- `CustomerSystemAndBoilerMaintenanceTest` (3 test) — `SQLSTATE[42S02]: Base table 'workorder_data_test.customers' doesn't exist`. La tabella `customers` (modulo Customer, connessione `customer`) non e' migrata nel DB di test condiviso in questo ambiente multi-agente. Non fixabile restando dentro Modules/Compliance (richiederebbe migrare un'altra connessione/modulo).
- `UserIdColumnsAcceptUuidTest` (1 test) — la colonna `fgas_leak_checks.performed_by_user_id` non esiste nello schema di test corrente. Anche questo e' uno stato del DB di test, non del codice Compliance.

Entrambi i blocchi sono ambientali (stato del DB di test condiviso), verificati identici confrontando l'esecuzione prima e dopo le modifiche di questa story.
