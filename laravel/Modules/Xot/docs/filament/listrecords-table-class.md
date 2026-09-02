# List Records — tabella via `*Table` class

## Regola (story 5.45 + 5.49)

`XotBaseListRecords` **non** usa `HasXotTable`. La tabella si costruisce così:

```
ListRecords::makeTable()
  → Resource::table()
    → getTableClass()::configure()   // XotBaseResourceTable + HasXotTable
```

Metodi `getTableColumns()`, `getTableFilters()`, `getTableActions()` sulla **pagina** non entrano nel flusso Filament: vanno nella classe `Resource/Tables/{Model}Table.php`.

## Estensione corretta

```php
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListMyRecords extends XotBaseListRecords
{
    protected static string $resource = MyResource::class;

    // Solo azioni/header/widget specifici della pagina — non la tabella.
}
```

```php
// Resource/Tables/MyModelsTable.php
class MyModelsTable extends XotBaseResourceTable
{
    public function getTableColumns(): array { /* ... */ }

    public function getTableFilters(): array { /* ... */ }
}
```

## Nomi metodo tabella

Usare `getTableFilters()`, `getTableActions()`, `getTableBulkActions()`, …

**Non** usare `getXotTableFilters()` né altri prefissi `getXotTable*`: rimossi in story 5.49.

## Collegamenti

- [xot-table-filters-method-name](../wiki/memories/xot-table-filters-method-name.md)
- [filament-table-architecture.md](../filament-table-architecture.md)
- [HasXotTable](../../app/Filament/Traits/HasXotTable.php)
- [XotBaseResourceTable](../../app/Filament/Resources/Tables/XotBaseResourceTable.php)
