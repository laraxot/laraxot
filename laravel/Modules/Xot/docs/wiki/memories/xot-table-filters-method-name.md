---
title: "XotTable filters method naming"
type: memory
tags: [filament, table, filters, hasxottable, naming]
created: 2026-08-26
qmd: "getXotTableFilters getTableFilters HasXotTable XotBaseResourceTable filtri tabella naming convention"
trigger: "table filters not showing, filtri non visibili, getTableFilters override"
---

# XotTable filters method naming

**Appreso**: 2026-08-26

## Lezione

Quando si estende `XotBaseResourceTable` e si vogliono definire filtri tabella, sovrascrivere `getXotTableFilters()`, NON `getTableFilters()`.

## Perche

`HasXotTable::table()` linea 217 chiama direttamente `$this->getXotTableFilters()`:

```php
->filters($this->getXotTableFilters())
```

Il metodo `getTableFilters()` esiste nel trait ma chiama `getXotTableFilters()`. Se si sovrascrive `getTableFilters()` nella classe concreta, non viene mai invocato perche il trait chiama il metodo "interno" `getXotTableFilters()`.

## Pattern corretto

```php
class MessagesTable extends XotBaseResourceTable
{
    public function getTableColumns(): array { ... }  // OK - viene chiamato via reflection

    public function getXotTableFilters(): array       // CORRETTO
    {
        return [
            SelectFilter::make('anno')->options([...]),
        ];
    }

    // public function getTableFilters(): array      // SBAGLIATO - non chiamato
}
```

## Differenza colonne vs filtri

| Metodo | Come viene chiamato | Override corretto |
|--------|---------------------|-------------------|
| `getTableColumns()` | Reflection in `resolveTableColumnsForXotTable()` | `getTableColumns()` |
| Filters | Chiamata diretta `getXotTableFilters()` | `getXotTableFilters()` |

## Riferimenti

- `Modules/Xot/app/Filament/Traits/HasXotTable.php:217`
- `Modules/Xot/app/Filament/Resources/Tables/XotBaseResourceTable.php`
