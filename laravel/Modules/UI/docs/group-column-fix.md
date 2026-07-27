# GroupColumn Fix - Risoluzione Errore "Column not mounted to table"

## Problema

Con `GroupColumn` alcuni valori risultano vuoti quando le colonne figlio puntano a relazioni o attributi annidati (es. `valutatore.nome_diri`). In questi casi la tabella non mostra nulla, anche se la stessa colonna funziona quando usata come `TextColumn` standard.

## Causa Tecnica

### Perché `$field->getState()` non funziona

Le colonne figlio nel `GroupColumn` **non sono montate alla tabella Filament**. In Filament, per far funzionare `getState()` su una colonna, questa deve essere:

1. Montata alla tabella (`$column->table($this->getTable())`)
2. Configurata con il record corrente (`$column->record($record)`)

Nel `GroupColumn` le colonne sono semplicemente memorizzate in un array `$schema`, ma non vengono mai preparate per essere usate con il record corrente. Questo significa che `$field->getState()` restituisce sempre `null`.

### La view originale

```php
// ❌ Non risolve la dot notation
$value = $record->{$name} ?? null;
```

Con `$name = 'valutatore.nome_diri'`, questo cerca `$record->{'valutatore.nome_diri'}` che non esiste come proprietà diretta.

## Soluzione Implementata

### View aggiornata con fallback `data_get()`

La view ora usa un approccio a due livelli:

```php
@php
    $name = $field->getName();
    // 1. Prova getState() (non funziona per colonne non montate)
    $value = $field->getState();
    // 2. Fallback: data_get() risolve la dot notation
    if ($value === null) {
        $value = data_get($record, $name);
    }

    // Skip valori vuoti
    if (empty($value) && $value !== 0 && $value !== '0') {
        continue;
    }
@endphp
```

### Perché funziona

`data_get($record, 'valutatore.nome_diri')` attraversa le relazioni:
1. Accede a `$record->valutatore` (che è un oggetto relazione)
2. Poi accede a `->nome_diri` su quell'oggetto

## Requisito: Eager Loading

**IMPORTANTE**: Per funzionare, la relazione deve essere caricata. Assicurarsi che la query della tabella includa:

```php
// Nel Resource o nella List page
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->with(['valutatore']); // ← Caricare la relazione
}
```

Senza eager loading, `data_get()` restituirà `null` perché la relazione non è caricata.

## Utilizzo

### Attributi diretti (sempre funziona)

```php
GroupColumn::make('lavoratore')->schema([
    TextColumn::make('matr'),
    TextColumn::make('cognome'),
    TextColumn::make('nome'),
])
```

### Attributi relazionali (richiede eager loading)

```php
GroupColumn::make('valutatore_info')->schema([
    TextColumn::make('valutatore.nome_diri'),
    TextColumn::make('valutatore.stabi_txt'),
])
```

## Alternative per Casi Complessi

### 1. Accessor piatto sul modello

Se il valore relazionale è usato frequentemente:

```php
// Nel Model
public function getNomeDiriAttribute(): ?string
{
    return $this->valutatore?->nome_diri;
}
```

Poi usare direttamente:

```php
TextColumn::make('nome_diri') // ← Senza dot notation
```

### 2. TextColumn standard fuori da GroupColumn

Per campi relazionali singoli, usare TextColumn direttamente (Filament gestisce automaticamente la dot notation):

```php
TextColumn::make('valutatore.nome_diri') // ← Fuori da GroupColumn
```

## Test

I test verificano:
- L'inizializzazione corretta dello schema
- La gestione di schemi vuoti
- Il percorso corretto della view
- La risoluzione della dot notation con `data_get()`

**File test**: `Modules/UI/tests/Feature/GroupColumnTest.php`

## File Coinvolti

- `Modules/UI/app/Filament/Tables/Columns/GroupColumn.php` - Classe colonna
- `Modules/UI/resources/views/filament/tables/columns/group.blade.php` - View con fix
- `Modules/UI/tests/Feature/GroupColumnTest.php` - Test

## Collegamenti

- [Custom Columns (Ptv)](../../Ptv/docs/custom-columns.md)
- [Common Errors (Theme One)](../../../Themes/One/docs/common-errors.md)
