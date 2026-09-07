---
id: STORY-2026-08-SUGGESTED-IMPROVEMENTS
title: SUGGESTED — Miglioramenti per zero errori PHPStan + qualità
status: suggested
module: All
branch: dev
---

# Suggerimenti (non obbligatori ma utili)

## 1. Fissa tutti i `public static function table()` nei Table subclass
Cambiare a `public function table()` (o usare `configure()` come `XotBaseResourceTable`). Questo elimina il mismatch con `HasXotTable`.

## 2. Aggiungi `getTableRecordTitleAttribute()` come `public` in tutte le Table
Se è `protected`, Filament non trova il metodo → bootstrap fallisce.

## 3. Rimuovi `#[\Override]` da tutti i file Filament
Se il parent non ha il metodo, il codice fallisce. Meglio rimuovere e aggiungere solo quando il parent lo dichiara esplicitamente.

## 4. Aggiorna `docs/` di ogni modulo dopo la fix
Non solo Xot — anche `Billing`, `Compliance`, `User`, `Signage`, `Catalog`, `PublicProcurement`, `Geo`, `EnergyBroker`, `WorkOrder`, `HR`, `Rating`, `Activity`, `Media`.

## 5. Esegui `pint --dirty --test` dopo ogni fix
I 39 file formattati da Pint devono essere corretti prima del commit.

## 6. Test Pest per il coverage
Ogni fix non banale deve avere un test Pest. Senza test, non si può dimostrare che il coverage è salito.

## 7. Documenta `mixed` inevitabile
Se `array_filter` o `mixed $column` è usato, lascia un commento `// mixed inevitabile: filtra Column + ColumnGroup unificati`.

# Priorità
1. Bootstrap (User + Media duplicati) — risolve 23 errori
2. Table static/non-static + protected title → risolve i restanti
3. Pint → 39 file
4. Pest → test per ogni modulo toccato
5. PHPInsights → soglie
