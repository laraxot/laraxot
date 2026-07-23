# Gestione della Navigazione in Filament

## XotBasePage e XotBaseResource

Nel nostro progetto, la navigazione viene gestita attraverso il sistema di traduzione, non direttamente nel codice.

### Importante per XotBasePage
- NON definire `protected static ?string $navigationIcon` nelle classi che estendono `XotBasePage`
- NON definire `protected static ?string $navigationGroup` nelle classi che estendono `XotBasePage`
- NON definire `protected static string $view` nelle classi che estendono `XotBasePage`
- Queste proprietà sono gestite automaticamente da `XotBasePage` attraverso il sistema di traduzioni

### Importante per XotBaseResource
- NON definire `protected static ?string $navigationIcon` nelle classi che estendono `XotBaseResource`
- NON definire `protected static ?string $navigationLabel` nelle classi che estendono `XotBaseResource`
- NON definire `protected static ?string $navigationGroup` nelle classi che estendono `XotBaseResource`

### Gestione Corretta
Le classi che estendono `XotBasePage` o `XotBaseResource` ereditano `TransTrait` e `NavigationLabelTrait` che gestiscono:
1. Icone di navigazione
2. Label di navigazione
3. Gruppi di navigazione
4. Ordinamento della navigazione
5. View di riferimento (per XotBasePage)

### File di Traduzione
Le configurazioni di navigazione devono essere definite nei file di traduzione:

```php
// Per XotBasePage - lang/it/page_name.php
return [
    'page-name' => [
        'get_navigation_icon' => 'heroicon-o-map',
        'get_navigation_label' => 'Nome Pagina',
        'get_navigation_group' => 'Gruppo',
        'get_navigation_sort' => '1',
    ],
];

// Per XotBaseResource - lang/it/resource_name.php
return [
    'navigation' => [
        'icon' => 'heroicon-o-users',
        'group' => 'Sistema',
        'label' => 'Utenti',
        'sort' => 1,
    ],
];
```

### Icone Disponibili
Le icone devono essere specificate nel file di traduzione e possono essere:
1. Heroicons (preferite)
   - heroicon-o-* (outline)
   - heroicon-s-* (solid)
2. Custom SVG registrate nel sistema

### Note Importanti
1. Le icone di default sono gestite da `NavigationLabelTrait::getNavigationIcon()`
2. Se l'icona non è trovata nel file di traduzione, viene usata l'icona di default 'heroicon-o-question-mark-circle'
3. La struttura mantiene separazione tra codice e presentazione
4. Facilita la gestione multilingua
5. Permette una gestione centralizzata della navigazione

### Best Practices
1. Sempre usare i file di traduzione per la configurazione della navigazione
2. Non sovrascrivere i metodi di NavigationLabelTrait nelle classi
3. Mantenere coerenza nelle icone tra le diverse lingue
4. Documentare le scelte di navigazione nel file di traduzione
5. Assicurarsi che tutte le pagine estendano `XotBasePage` invece di `Filament\Pages\Page`
6. Assicurarsi che tutte le risorse estendano `XotBaseResource` invece di `Filament\Resources\Resource`

### Esempio di Migrazione da Page a XotBasePage
```php
// PRIMA
class MyPage extends Page 
{
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'MyGroup';
    protected static string $view = 'my-view';
}

// DOPO
class MyPage extends XotBasePage 
{
    // Nessuna proprietà di navigazione qui
    // Tutto è gestito nel file di traduzione
}
```
