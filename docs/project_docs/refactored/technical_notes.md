# Note Tecniche

## XotBaseResource e Form Handling
- La classe `XotBaseResource` definisce un metodo astratto `getFormSchema()` che DEVE essere implementato da tutte le classi che la estendono
- Il metodo `form(Form $form)` è già implementato in `XotBaseResource` e utilizza `getFormSchema()`
- Le classi figlie NON devono sovrascrivere il metodo `form()`, ma devono implementare `getFormSchema()`

## XotBaseResource e Navigazione
- La classe `XotBaseResource` gestisce autonomamente la navigazione
- Le classi figlie NON devono definire:
  - `protected static ?string $navigationIcon`
  - `public static function getNavigationGroup()`
- Questi elementi sono gestiti centralmente da `XotBaseResource` per mantenere consistenza nell'interfaccia

## XotBaseResource e Traduzioni
- NON utilizzare il metodo `->label()` nei form, le etichette sono gestite automaticamente dal LangServiceProvider
- Le traduzioni devono essere organizzate in file di lingua per ogni modulo
- Utilizzare `static::trans()` per accedere alle traduzioni
- Struttura delle traduzioni per i form:
  ```php
  return [
      'fields' => [
          'field_name' => 'Label Tradotto',
          'field_name.placeholder' => 'Placeholder Tradotto',
          'field_name.helper_text' => 'Testo di aiuto tradotto',
      ],
  ];
  ```

## Pagine delle Risorse
- Le pagine delle risorse devono estendere le classi base del modulo Xot:
  - Usare `XotBaseViewRecord` invece di `Filament\Resources\Pages\ViewRecord`
  - Le pagine di visualizzazione devono implementare `getHeaderActions()` per le azioni standard

### Esempio di Implementazione Corretta di una Risorsa
```php
class CustomResource extends XotBaseResource
{
    protected static ?string $model = CustomModel::class;

    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->placeholder(static::trans('fields.name.placeholder'))
                ->helperText(static::trans('fields.name.helper_text')),
        ];
    }
}
```

### Esempio di Implementazione ERRATA di una Risorsa
```php
class CustomResource extends XotBaseResource
{
    protected static ?string $model = CustomModel::class;
    
    // ❌ NON definire navigationIcon - è gestito da XotBaseResource
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    // ❌ NON implementare form() - è già implementato in XotBaseResource
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }
    
    // ❌ NON usare ->label() - le etichette sono gestite dalle traduzioni
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Name')  // ❌ ERRATO
                ->placeholder('Enter name'),  // ❌ ERRATO
        ];
    }
}
```

### Esempio di Implementazione Corretta di una Pagina di Visualizzazione
```php
class ViewCustom extends XotBaseViewRecord
{
    protected static string $resource = CustomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->action(fn () => $this->record->softDelete()),
        ];
    }
} 