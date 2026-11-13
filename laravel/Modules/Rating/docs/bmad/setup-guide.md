---
title: "Rating: Setup Guide"
type: note
module: Rating
tags:
  - bmad
  - rating
  - setup
created: 2026-09-28
updated: 2026-10-07
qmd: "rating setup provider config migrate seed test"
related:
  - README.md
  - quick-reference.md
status: draft
---

# Rating: Setup Guide

> **SUMMARY**: Come si porta il modulo `Rating` in un ambiente funzionante: provider da
> registrare, config, migrazioni, seeders e test. Comandi da eseguire dalla root di `laravel/`.

## 1. Registrazione provider

Dichiarati in `module.json` e in `composer.json` (`extra.laravel.providers`):

- `Modules\Rating\Providers\RatingServiceProvider`
- `Modules\Rating\Providers\Filament\AdminPanelProvider`

`app/Providers/RatingServiceProvider.php` e' una classe di 20 righe che estende
`XotBaseServiceProvider` e non sovrascrive `boot()`/`register()`: il boot del modulo e' tutto
in `Filament/AdminPanelProvider.php` e nella scoperta automatica delle risorse.

## 2. Config

`config/config.php` contiene solo:

| Chiave | Uso |
|--------|-----|
| `name` | nome del modulo |
| `icon` | icona sulla dashboard (`heroicon-o-star`) |
| `navigation_sort` | ordine di navigazione |

Non ci sono chiavi di connessione DB nel modulo: `BaseRating` e `BaseRatingMorph` usano la
connessione standard, mentre le concrete in altri moduli sono su connessioni dedicate
(vedi `RatingContract` docblock).

## 3. Migrazioni

Migrazioni attive in `database/migrations/`:

| File | Oggetto |
|------|---------|
| `2023_01_01_000000_create_ratings_table.php` | tabella ratings |
| `2023_01_01_000005_create_rating_morph_table.php` | tabella rating_morph |
| `2026_03_12_180000_create_ratings_table.php` | ratings |
| `2026_03_27_000001_add_percentage_to_rating_morph_table.php` | colonna percentage |
| `2026_03_27_000002_add_percentage_to_rating_morph_table_on_rating_connection.php` | percentage su connessione rating |
| `2026_06_16_000003_create_ratings_table.php` | ratings |
| `2026_07_15_120003_create_rating_morph_table.php` | rating_morph |
| `2026_07_27_100005_create_rating_morph_table.php` | rating_morph |

Copie spostate in `database/migrations/_archive_redundant/` e `database/migrations/_bak/`.
Prima di applicare, verificare quali sono idempotenti: piu' file creano la stessa tabella.

Vincoli di progetto: mai `migrate:fresh`, mai `--force`, mai `RefreshDatabase`.

## 4. Factory e seeders

| File | Path |
|------|------|
| `BaseRatingFactory`, `RatingFactory`, `LikeFactory` | `database/factories/` |
| `RatingSeeder`, `RatingMorphSeeder`, `RatingDatabaseSeeder` | `database/seeders/` |

## 5. Rotte

`routes/web.php` e `routes/api.php` contengono solo `declare(strict_types=1);`.
Non esiste superficie HTTP dichiarata dal modulo.

## 6. Test

Da `laravel/`:

```bash
./vendor/bin/pest --filter=Rating
./vendor/bin/pest Modules/Rating
```

Supporto: `tests/Pest.php`, `tests/TestCase.php`, stub in `tests/Fixtures/`
(`HasRatingHostStub.php`, `RatingsHostStub.php`, `RatedModelStub.php`, `OwnedModelStub.php`,
`BaseEditRatingStub.php`, `BaseRatingsTableStub.php`, `LikeableStub.php`).

Nota di progetto: sull'host `10.100.200.15` non si lanciano test (dati sacri).

## 7. Dashboard

`app/Filament/Pages/Dashboard.php`, `app/Filament/Blocks/Rating.php` e
`app/Filament/Widgets/StatsOverview.php` compongono la vista dashboard del modulo; la traduzione
dell'etichetta dashboard e' in `lang/it/admin_panel_provider.php`.

## 8. Dipendenze runtime

`spatie/laravel-schemaless-attributes` e PHP `^8.3` (`composer.json`). Verificare che la colonna JSON degli attributi sia valorizzata sulle tabelle host.

## 9. Regole del modulo

Buone pratiche:

- Documentare prima di implementare (PRD prima del codice).
- Estendere XotBase: modelli da `BaseModel`, pivot da `BaseMorphPivot`, resource da `Base*Resource`, mai Filament diretto.
- Actions e non Services: logica in `app/Actions/` con `execute()`.
- PHPStan al livello massimo, nessun `ignoreErrors`, `phpstan.neon` non si modifica.
- Traduzioni da file, mai label hardcoded; array PHP con una chiave per riga.
- Tipizzare su `RatingContract` e `HasRatingContract`, mai su `BaseRating`.

Da evitare:

- Usare `''` come sentinella per «altro» (collassa a `null`, la nota non diventa obbligatoria).
- Usare `RuleEnum` sulla Select con figli (usare `Rule::in`).
- Duplicare fill e save del pivot negli host (esiste `HasRatingsTrait`).
- Creare Services.

## 10. False friends

| Termine | Sembra | In realta |
|---|---|---|
| Rating | una valutazione | la definizione di un insieme di opzioni, con i suoi figli |
| RatingMorph | una relazione | il pivot con `value` e `note` tra rating e host |
| RuleEnum | enum di opzioni | enum di regole di validazione Laravel |
| BaseRating, BaseRatingMorph | classi base generiche | basi riusabili dai moduli host |
| HasRating, HasRatingsTrait | due trait simili | il primo espone il rating, il secondo gestisce il form pivot |
| Service | servizio generico | vietato in Xot, si usano le Actions |

## 11. Struttura directory

- `app/Actions/`: `HasRating/` e le action di conteggio, somma e opzioni.
- `app/Models/`: basi e concrete, `Contracts/`, `Traits/`.
- `app/Enums/`: `RuleEnum`, `SupportedLocale`.
- `app/Filament/`: resource, `Forms/`, `Concerns/`, `Tables/`, `Blocks/`, `Widgets/`, `RelationManagers/`.
- `database/migrations/`: `ratings` e `rating_morph` (con `percentage`), schema incrementale.
- `lang/it/`: chiavi di traduzione.
- `docs/bmad/`: questa documentazione.
