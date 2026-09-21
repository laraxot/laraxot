<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
---
title: "List Records in Filament"
type: concept
tags: [listrecords]
created: 2026-07-14
updated: 2026-07-14
qmd: "listrecords list records in filament"
issues: ["https://github.com/provtv/<nome repository>/issues/124"]
discussions: ["https://github.com/provtv/<nome repository>/discussions/1"]
related:
  - "./automatic-translations.md"
  - "./best-practices.md"
  - "./component-icon-support.md"
  - "./component-methods-compatibility.md"
  - "./filament-4-components-guide.md"
  - "./filament-4-migration-guide.md"
  - "./filament-4-migration-summary.md"
  - "./filament-4-migration-sumy.md"
---

<<<<<<< HEAD
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
# List Records in Filament

## Estensione Corretta
```php
// ❌ ERRATO: Non estendere ListRecords
use Filament\Resources\Pages\ListRecords;
class ListMyRecords extends ListRecords
{
    // ...
}
// ✅ CORRETTO: Estendere XotBaseListRecords
## Perché

Le pagine lista delle Resource non estendono `Filament\Resources\Pages\ListRecords`.
Estendono `XotBaseListRecords`: traduzioni, permessi e colonne restano nel contratto Laraxot.

## Estensione corretta

```php
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListMyRecords extends XotBaseListRecords
{
    protected static string $resource = MyResource::class;

    public function getListTableColumns(): array
    {
        return [
            // definizione colonne
// colonne
        ];
    }
}
```

## Metodi obbligatori

## Metodi Obbligatori
Quando si estende `XotBaseListRecords`:
1. `getListTableColumns()` — colonne
2. `getListTableActions()` — azioni riga
3. `getListTableBulkActions()` — azioni di massa
4. `getListTableFilters()` — filtri
Prefisso `List` obbligatorio. Visibilità `public`. Non cambiare la visibilità dei metodi ereditati.
## Errori comuni
- Estendere `ListRecords` Filament direttamente
- Usare `getTableColumns()` / `getTableActions()` senza prefisso `List`
- Mettere la tabella sulla Resource invece che sulla pagina List
- `protected function getListTableColumns()`
## Best practices
```php
declare(strict_types=1);
namespace Modules\XXX\Filament\Resources\XXXResource\Pages;
use Exception;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;
use Modules\Xot\Filament\Traits\TransTrait;
use Webmozart\Assert\Assert;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======

<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
class ListXXX extends XotBaseListRecords
{

    protected static string $resource = XXXResource::class;

=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> 92912795 (.)

class ListXXX extends XotBaseListRecords
{
    
    protected static string $resource = XXXResource::class;

    

    

  

<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
class ListXXX extends XotBaseListRecords
{
    
    protected static string $resource = XXXResource::class;

>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    public function getGridTableColumns(): array
    {
        return [
        ];
    }
## Metodi obbligatori
Quando si estende `XotBaseListRecords`:

1. `getListTableColumns()` — colonne
2. `getListTableActions()` — azioni riga
3. `getListTableBulkActions()` — azioni di massa
4. `getListTableFilters()` — filtri

Prefisso `List` obbligatorio. Visibilità `public`. Non cambiare la visibilità dei metodi ereditati.

## Errori comuni

- Estendere `ListRecords` Filament direttamente
- Usare `getTableColumns()` / `getTableActions()` senza prefisso `List`
- Mettere la tabella sulla Resource invece che sulla pagina List
- `protected function getListTableColumns()`

## Best practices

```php
class ListMyRecords extends XotBaseListRecords
{
    protected static string $resource = MyResource::class;

    public function getListTableColumns(): array
    {
        return [
        ];
    }
    public function getTableFilters(): array
    {
        return [
    public function getTableActions(): array
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
    {
        return [
<<<<<<< HEAD
            
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======

=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
            
=======

>>>>>>> laraxot/dev
=======
            
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
            ViewAction::make()
                ->label(''),
            EditAction::make()
            DeleteAction::make()
                ->label('')
                ->requiresConfirmation(),
    public function getTableBulkActions(): array
            DeleteBulkAction::make(),
    public function table(Table $table): Table
        return $table
            // ->columns($this->getTableColumns())
            ->columns($this->layoutView->getTableColumns())
            ->contentGrid($this->layoutView->getTableContentGrid())
            ->headerActions($this->getTableHeaderActions())
            ->filters($this->getTableFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->persistFiltersInSession()
            ->actions($this->getTableActions())
            ->bulkActions($this->getTableBulkActions())
            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->defaultSort(
                column: 'created_at',
                direction: 'DESC',
            );
            TextColumn::make('id')->sortable(),
            TextColumn::make('name')->searchable(),
        ];
    }
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
}
<<<<<<< HEAD
```
=======
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
```
=======
=======
=======
<<<<<<< HEAD
<<<<<<< HEAD
```
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
```
# List Records in Filament
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

    public function getListTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
        ];
    }

    public function getListTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }
}
```

Traduzioni con `static::trans()`, permessi con `can()` nel `mount()`.
---
## Contenuto assorbito da `ListRecords.md`
# List Records in Filament
## Estensione Corretta
```php
// ❌ ERRATO: Non estendere ListRecords
use Filament\Resources\Pages\ListRecords;
class ListMyRecords extends ListRecords
{
    // ...
}
// ✅ CORRETTO: Estendere XotBaseListRecords
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
class ListMyRecords extends XotBaseListRecords
    protected static string $resource = MyResource::class;
    public function getListTableColumns(): array
        return [
            // definizione colonne
        ];
```
## Metodi Obbligatori
Quando si estende `XotBaseListRecords`, è necessario implementare i seguenti metodi:
1. `getListTableColumns()`: Definisce le colonne della tabella
2. `getListTableActions()`: Definisce le azioni per singola riga
3. `getListTableBulkActions()`: Definisce le azioni di massa
4. `getListTableFilters()`: Definisce i filtri della tabella
## Convenzioni Importanti
1. **Prefisso List**:
   - Tutti i metodi relativi alla tabella DEVONO avere il prefisso "List"
   - Es: `getListTableColumns()` invece di `getTableColumns()`
2. **Visibilità dei Metodi**:
   - Tutti i metodi devono essere `public`
   - Non cambiare la visibilità dei metodi ereditati
3. **Namespace**:
## Errori Comuni da Evitare
1. **❌ Estensione Errata**:
   // NON estendere mai ListRecords direttamente
2. **❌ Metodi Senza Prefisso List**:
   // NON usare i metodi senza prefisso List
   public function getTableColumns(): array
   public function getTableActions(): array
   public function getTableBulkActions(): array
3. **❌ Visibilità Errata**:
   // NON cambiare la visibilità dei metodi
   protected function getListTableColumns(): array
## Best Practices
1. **Organizzazione del Codice**:
               TextColumn::make('id')->sortable(),
               TextColumn::make('name')->searchable(),
       public function getListTableActions(): array
               EditAction::make(),
               DeleteAction::make(),
       public function getListTableBulkActions(): array
               DeleteBulkAction::make(),
2. **Traduzioni**:
   - Usare `static::trans()` per le traduzioni
   - Definire le traduzioni nel file di lingua del modulo
3. **Permessi**:
   - Implementare controlli nei mount()
   - Usare can() per azioni condizionali
## Note Importanti
1. XotBaseListRecords fornisce funzionalità aggiuntive rispetto a ListRecords:
   - Gestione automatica delle traduzioni
   - Integrazione con il sistema di permessi
   - Funzionalità custom del framework
2. La configurazione della tabella deve essere sempre nella classe List, non nel Resource
3. Mantenere la coerenza in tutto il progetto usando sempre XotBaseListRecords
declare(strict_types=1);
namespace Modules\XXX\Filament\Resources\XXXResource\Pages;
use Exception;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\UI\Filament\Actions\Table\TableLayoutToggleTableAction;
use Modules\Xot\Filament\Traits\TransTrait;
use Webmozart\Assert\Assert;
class ListXXX extends XotBaseListRecords
    protected static string $resource = XXXResource::class;
    public function getGridTableColumns(): array
    public function getTableFilters(): array
            ViewAction::make()
                ->label(''),
            EditAction::make()
            DeleteAction::make()
                ->label('')
                ->requiresConfirmation(),
    public function table(Table $table): Table
        return $table
            // ->columns($this->getTableColumns())
            ->columns($this->layoutView->getTableColumns())
            ->contentGrid($this->layoutView->getTableContentGrid())
            ->headerActions($this->getTableHeaderActions())
            ->filters($this->getTableFilters())
            ->filtersLayout(FiltersLayout::AboveContent)
            ->persistFiltersInSession()
            ->actions($this->getTableActions())
            ->bulkActions($this->getTableBulkActions())
            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->defaultSort(
                column: 'created_at',
                direction: 'DESC',
            );
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
    }
}
```
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
```
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
