---
title: "typeCoverage.constantTypeCoverage fix pattern — @var list<string> regression"
type: coordination-alert
created: 2026-09-07
status: active
issue: https://github.com/laraxot/base_workorder_fila5/issues/14
discussion: https://github.com/laraxot/base_workorder_fila5/discussions/15
---

# Attenzione: pattern di fix sbagliato su typeCoverage.constantTypeCoverage

## Cosa sta succedendo

Rescan pieno `phpstan analyse Modules` oggi (2026-09-07): 159 → 157 errori, MA sono comparsi
errori NUOVI `classConstant.phpDocType` su almeno:

- `MetalWork/app/Actions/MatchRawMaterialToSolidAction.php:38` (`ELIGIBLE_FAMILIES`)
- `MetalWork/app/Support/StepFileParser.php:50` (`COUNTED_ENTITIES`)
- `MetalWork/app/Support/StepSolidExtractor.php:58` (`SURFACE_ENTITIES`)
- `PublicProcurement/app/Actions/EvaluateTenderEligibilityAction.php:20` (`DEFAULT_DOCUMENTS`)
- `Signage/database/seeders/SignageDemoSeeder.php:32` (`WORKFLOW_STATUSES`)

Tutti con lo stesso messaggio: `@var ... with type list<string> is incompatible with value
array{...}` — cioè qualcuno ha aggiunto `@var list<string>` su costanti che in realtà sono
array **associativi o annidati** (chiavi stringa, o array di array), non liste sequenziali.

## Causa radice del fix sbagliato

`typeCoverage.constantTypeCoverage` si soddisfa con un **type declaration reale** sulla
costante (PHP 8.3+, `public const array FOO = [...]`), non con un `@var` PHPDoc. Chi ha
fatto questi fix probabilmente ha capito il contrario e ha aggiunto `@var list<string>`
a caso senza controllare la shape reale del valore.

## Fix corretto

1. Guarda il valore reale della costante.
2. Se è una lista sequenziale (`['a', 'b', 'c']`) → `public const array FOO = [...]` +
   opzionale `@var list<string>` se serve precisione extra per l'IDE.
3. Se è associativa (`['KEY' => 'value', ...]`) → `public const array FOO = [...]` +
   `@var array<string,string>` (o la shape esatta `array{KEY: string, ...}` se serve
   precisione totale — preferibile quando le chiavi sono un set fisso e noto).
4. Se è annidata (array di array) → shape esplicita `array{code: string, name: string, ...}`
   o `list<array{code: string, ...}>`, mai `list<string>`.

Il tipo REALE (`array`) sulla dichiarazione `const` è quello che sposta la percentuale
globale (147/300 → 99%+). Il PHPDoc è un extra per la precisione statica, ma deve
corrispondere alla shape vera o PHPStan lo boccia con `classConstant.phpDocType` — un
errore NUOVO che prima non c'era, quindi peggiora il totale invece di migliorarlo.

## Verificato — pattern corretto applicato

`Rating/app/Filament/Blocks/Rating.php:23`: `public const string BLOCK_TYPE = 'rating';`
— costante scalare stringa, tipo reale `string`, zero PHPDoc necessario, zero errori.

## Coordinamento

Se stai lavorando sul bucket typeCoverage.constantTypeCoverage: controlla la shape REALE
di ogni costante prima di tipizzarla. Non incollare `@var list<string>` per abitudine.
Se trovi altri file con questo stesso pattern sbagliato, correggi la shape invece di
limitarti a togliere il `@var`.
