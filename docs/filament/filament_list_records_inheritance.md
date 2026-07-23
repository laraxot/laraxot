# Ereditarietà delle List Pages in Filament

## XotBaseListRecords vs ListRecords

Nel nostro progetto, tutte le List Pages devono estendere `XotBaseListRecords` invece di `ListRecords`.

### Metodi Permessi e Struttura Array
Le classi che estendono `XotBaseListRecords` possono implementare i seguenti metodi, ciascuno con una specifica struttura array:

1. `getListTableColumns()`: Definizione delle colonne della tabella
```php
public function getListTableColumns(): array
{
    return [
        'email' => TextColumn::make('email')  // ✓ Chiave stringa obbligatoria
            ->label('Email')
            ->sortable(),
        'status' => TextColumn::make('status')  // ✓ Chiave stringa obbligatoria
            ->label('Stato'),
        // ❌ NON fare: TextColumn::make('field') senza chiave
    ];
}
```

2. `getTableFilters()`: Filtri per la tabella
```php
protected function getTableFilters(): array
{
    return [
        'status' => TernaryFilter::make('status')  // ✓ Chiave stringa obbligatoria
            ->label('Stato'),
        'type' => SelectFilter::make('type')  // ✓ Chiave stringa obbligatoria
            ->options([...]),
    ];
}
```

3. `getHeaderActions()`: Azioni nell'header della pagina
```php
public function getHeaderActions(): array
{
    return [
        'import' => Actions\ImportAction::make('import')  // ✓ Chiave stringa obbligatoria
            ->importer(Importer::class),
        'export' => Actions\ExportAction::make('export')  // ✓ Chiave stringa obbligatoria
            ->exporter(Exporter::class),
    ];
}
```

4. `getTableActions()`: Azioni per ogni riga della tabella
```php
public function getTableActions(): array
{
    return [
        ...parent::getTableActions(),  // ⚠️ OBBLIGATORIO come primo elemento
        'edit' => Action::make('edit')  // ✓ Chiave stringa obbligatoria
            ->label('Modifica'),
        'delete' => Action::make('delete')  // ✓ Chiave stringa obbligatoria
            ->label('Elimina'),
    ];
}
```

5. `getTableBulkActions()`: Azioni bulk sulla tabella
```php
protected function getTableBulkActions(): array
{
    return [
        'delete' => BulkAction::make('delete')  // ✓ Chiave stringa obbligatoria
            ->label('Elimina selezionati'),
        'export' => BulkAction::make('export')  // ✓ Chiave stringa obbligatoria
            ->label('Esporta selezionati'),
    ];
}
```

### Regole Fondamentali
1. Estendere SEMPRE `XotBaseListRecords`
2. NON utilizzare mai `parent::getListTableColumns()`
3. OGNI elemento negli array DEVE avere una chiave stringa
4. Le chiavi devono essere UNICHE all'interno dello stesso array
5. Le chiavi devono essere DESCRITTIVE del loro scopo
6. ⚠️ In `getTableActions()`, `...parent::getTableActions()` DEVE essere il primo elemento dell'array

### Motivazioni per parent::getTableActions()
1. Mantiene le azioni base fornite da XotBaseListRecords
2. Garantisce funzionalità comuni in tutte le liste
3. Permette personalizzazioni mantenendo le funzionalità standard
4. Assicura coerenza nell'interfaccia utente
5. Facilita aggiornamenti e manutenzione

### Best Practices
1. Usare chiavi descrittive che riflettono lo scopo dell'elemento
2. Mantenere coerenza nella nomenclatura delle chiavi
3. Documentare il significato delle chiavi quando non ovvio
4. Raggruppare elementi correlati con prefissi comuni
5. Evitare chiavi duplicate o ambigue

### Esempio Corretto di getTableActions
```php
// ✓ Corretto
public function getTableActions(): array
{
    return [
        ...parent::getTableActions(),  // Primo elemento
        'custom_action' => Action::make('custom')  // Azioni aggiuntive dopo
            ->label('Azione Personalizzata'),
    ];
}

// ❌ NON Corretto
public function getTableActions(): array
{
    return [
        'custom_action' => Action::make('custom'),  // NON mettere azioni prima
        ...parent::getTableActions(),  // parent::getTableActions() deve essere primo
    ];
}
```

### Note Importanti
- La classe base gestisce già molte funzionalità comuni
- Concentrarsi sulla definizione corretta degli array con chiavi
- Evitare duplicazione di codice
- Mantenere la coerenza tra le diverse List Pages
- SEMPRE includere parent::getTableActions() come primo elemento in getTableActions()

### Documentazione Correlata
- XotBaseResource Documentation
- Filament Tables Documentation
- Project Coding Standards
- Module Structure Guidelines
