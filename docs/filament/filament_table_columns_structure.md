# Struttura delle Colonne nelle Tabelle Filament

## getListTableColumns() Method

Il metodo `getListTableColumns()` nelle List Pages deve seguire queste regole fondamentali:

1. **Chiavi Array**
   - DEVE restituire un array associativo con chiavi stringa
   - Le chiavi devono corrispondere al nome della colonna

2. **Label Management**
   - NON utilizzare mai il metodo `->label()`
   - Le label vengono gestite automaticamente dal LangServiceProvider
   - Questo garantisce la coerenza e la localizzazione in tutta l'applicazione

### Formato Corretto
```php
public function getListTableColumns(): array
{
    return [
        'id' => TextColumn::make('id')
            ->sortable()
            ->searchable(),
        'name' => TextColumn::make('name')
            ->sortable()
            ->searchable(),
    ];
}
```

### Formato Non Corretto (Da Evitare)
```php
public function getListTableColumns(): array
{
    return [
        TextColumn::make('id')  // Manca la chiave stringa!
            ->label('ID')       // Non usare ->label()!
            ->sortable(),
        TextColumn::make('name')
            ->label('Nome')     // Non usare ->label()!
            ->sortable(),
    ];
}
```

### Motivazione
1. **Chiavi Stringa**
   - Facilita l'accesso e la manipolazione delle colonne
   - Mantiene coerenza con altri array associativi nel framework
   - Migliora la leggibilità e manutenibilità del codice

2. **No Label Method**
   - Le label sono gestite centralmente dal LangServiceProvider
   - Garantisce coerenza nelle traduzioni
   - Evita duplicazione delle stringhe di traduzione
   - Facilita la manutenzione e l'aggiornamento delle traduzioni

### Note Importanti
1. Questo pattern deve essere seguito in tutte le List Pages
2. Le traduzioni devono essere configurate nel LangServiceProvider
3. Non tentare di sovrascrivere le label direttamente nelle colonne
