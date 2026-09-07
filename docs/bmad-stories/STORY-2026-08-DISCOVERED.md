---
id: STORY-2026-08-DISCOVERED-ARCHITECTURE
slug: discovered-architecture-tables-schemas-trans-trait
title: DISCOVERED — Tables/Schemas architecture + TransTrait duplication + HasXotForm instance pattern
status: discovered
module: Xot
branch: dev
---

# Scoperte (da non perdere)

## 1. Tables + Schemas obbligatori per Filament
Ogni `Resource` ha bisogno di:
- `Tables/<Name>Table.php` — colonne, azioni
- `Schemas/<Name>Form.php` — form schema
- `Schemas/<Name>Infolist.php` — readonly schema
Se manca, `discoverResources()` fallisce → bootstrap PHPStan fallisce.

Archivio: `Modules/Xot/docs/wiki/filament-tables-schemas-architecture.md`

## 2. TransTrait duplicato
- Presente in `HasXotTable` trait
- Presente anche in `XotBaseResourceTable` (`use TransTrait;`)
- Non serve in `XotBaseResourceTable`; rimuovere.

## 3. HasXotForm / HasXotTable / HasXotInfolist — pattern unificato
Tutti e tre devono usare:
```php
public static function configure(...): ...
{
    $instance = app(static::class);
    Assert::isInstanceOf($instance, self::class);
    return $instance-><method>(...);
}
```
Non `static::getFormSchema()` diretto.

## 4. `mixed` inevitabile
Se `getTableColumns()` restituisce `array` con elementi non tipizzabili (es. ColumnGroup + Column misti), il filtro `array_filter` con `mixed $column` è la sola soluzione corretta. Documentare con commento breve.

## 5. `#[\Override]` non è sempre corretto
Se il parent `XotBaseResourceTable` non dichiara `getTableRecordTitleAttribute()` come abstract, non serve `#[\Override]`. Se il metodo non esiste nel parent, PHP lancia fatal.

# Per non dimenticare
- Leggere sempre `CLAUDE.md` e `AGENTS.md` prima di modificare
- Verificare `git show` per capire perché il codice è così
- Non estendere `Filament` diretto (`XotBaseResourceTable` è base, non estensione diretta)
- Non usare `RefreshDatabase` — `.env.testing` SQLite
- Non fare `git restore/checkout/reset/revert` — solo avanti (`git log`, `git show`, `git blame`)
