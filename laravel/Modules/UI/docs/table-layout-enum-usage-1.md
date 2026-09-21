# TableLayoutEnum Usage Guide

## Nuovo Approccio (Corretto)

Dopo la correzione del problema di visibilità, il metodo `getTableColumns()` ora richiede parametri espliciti invece di usare debug_backtrace.

### Esempio di uso nelle classi ListRecords:

```php
use Modules\UI\Enums\TableLayoutEnum;

class ListUsers extends ListRecords
{
    protected TableLayoutEnum $layout;
<<<<<<< HEAD

=======
<<<<<<< HEAD

=======
    
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    public function mount(): void
    {
        $this->layout = TableLayoutEnum::LIST;
    }
<<<<<<< HEAD

=======
<<<<<<< HEAD

=======
    
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    public function table(Table $table): Table
    {
        return $table
            ->columns($this->getColumnsForLayout())
            ->contentGrid($this->layout->getTableContentGrid());
    }
<<<<<<< HEAD

=======
<<<<<<< HEAD

=======
    
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    /**
     * Restituisce le colonne appropriate per il layout corrente
     */
    protected function getColumnsForLayout(): array
    {
        $listColumns = [
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('created_at'),
        ];
<<<<<<< HEAD

=======
<<<<<<< HEAD

=======
        
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
        $gridColumns = [
            Tables\Columns\Layout\Stack::make([
                Tables\Columns\TextColumn::make('name')
                    ->weight(FontWeight::Bold),
                Tables\Columns\TextColumn::make('email'),
            ]),
        ];
        
        return $this->layout->getTableColumns($listColumns, $gridColumns);
    }
    
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev

        return $this->layout->getTableColumns($listColumns, $gridColumns);
    }

<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    /**
     * Toggle del layout tramite action
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggleLayout')
                ->icon($this->layout->getIcon())
                ->action(function () {
                    $this->layout = $this->layout->toggle();
                }),
        ];
    }
}
```

### Vantaggi del nuovo approccio:

1. **Type Safety**: Non usa più reflection o debug_backtrace
2. **Chiarezza**: Esplicito su quali colonne usare per ogni layout
3. **Testabilità**: Più facile da testare senza dipendenze nascoste
4. **Performance**: Nessun overhead di debug_backtrace

### Breaking Change:

Il metodo `getTableColumns()` ora richiede due parametri:
- `$listColumns`: Array delle colonne per layout lista
<<<<<<< HEAD
- `$gridColumns`: Array delle colonne per layout griglia
=======
<<<<<<< HEAD
- `$gridColumns`: Array delle colonne per layout griglia
=======
- `$gridColumns`: Array delle colonne per layout griglia 
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
