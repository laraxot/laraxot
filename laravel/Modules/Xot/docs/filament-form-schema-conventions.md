# Convenzioni per Form Schema in Filament

## Regola Fondamentale

In <nome progetto>, il metodo `getFormSchema()` nelle risorse Filament deve **SEMPRE** restituire un array associativo con chiavi stringhe, mai un array numerico.

## Implementazione Corretta

```php
// ✅ CORRETTO
public function getFormSchema(): array
{
    return [
        'title' => Forms\Components\TextInput::make('title')
            ->required(),
        'content' => Forms\Components\RichEditor::make('content')
            ->columnSpan(2),
        'status' => Forms\Components\Select::make('status')
            ->options(StatusEnum::options()),
    ];
}
```

## Implementazione Errata

```php
// ❌ ERRATO
public function getFormSchema(): array
{
    return [
        Forms\Components\TextInput::make('title')
            ->required(),
        Forms\Components\RichEditor::make('content')
            ->columnSpan(2),
        Forms\Components\Select::make('status')
            ->options(StatusEnum::options()),
    ];
}
```

## Componenti Deprecati da Evitare

La classe `Forms\Components\Card` è deprecata e **NON** deve essere utilizzata. Utilizzare invece:

```php
// ✅ CORRETTO
'info_section' => Forms\Components\Section::make('Informazioni')
    ->schema([
        'name' => Forms\Components\TextInput::make('name'),
        'email' => Forms\Components\TextInput::make('email'),
    ]),
```

## Origine dei Campi

I campi del form devono essere ricavati da:

1. **Modello Eloquent**: Utilizzare le proprietà e le relazioni del modello
2. **Migration**: Basarsi sui campi definiti nelle migrazioni
3. **Vincoli di Business**: Riflettere i requisiti specifici dell'applicazione

Non inventare o aggiungere arbitrariamente campi che non esistono nel modello o nelle migration.

## Proprietà di Navigazione

Nelle classi che estendono `XotBaseResource`, **NON** definire:

- `protected static ?string $navigationIcon`
- `protected static ?string $navigationGroup`
- `protected static ?int $navigationSort`
- `public static function getNavigationLabel()`
- `public static function getPluralModelLabel()`
- `public static function getModelLabel()`

`XotBaseResource` gestisce automaticamente questi aspetti.

## Esempi Completi

### Prima (Non Corretto)

```php
class MyResource extends XotBaseResource
{
    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecords::route('/'),
            'create' => Pages\CreateRecord::route('/create'),
            'edit' => Pages\EditRecord::route('/{record}/edit'),
        ];
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('title'),
            Forms\Components\RichEditor::make('content'),
        ];
    }
}
```

### Dopo (Corretto)

```php
class MyResource extends XotBaseResource
{
    public function getFormSchema(): array
    {
        return [
            'title' => Forms\Components\TextInput::make('title'),
            'content' => Forms\Components\RichEditor::make('content'),
        ];
    }
}
```

## Vantaggi dell'Approccio Corretto

1. **Consistenza**: Mantiene coerenza in tutto il progetto
2. **Mantenibilità**: Facilita la gestione e la modifica dei form
3. **Chiarezza**: Rende esplicita l'associazione tra campi e componenti
4. **Estensibilità**: Permette l'override parziale del form schema nelle classi derivate

## `getFormSchema()`/`getInfolistSchema()` sono di ISTANZA — anche su enum

`XotBaseResource::getFormSchema()` è `final public function` (istanza). La stessa
regola vale per `Modules\Xot\Traits\EnumTrait::getFormSchema()` quando un enum
Filament (`AddressItemEnum`, `ContactTypeEnum`, `FieldTypeEnum`, ecc.) usa il
trait: il metodo è di istanza, non chiamarlo con `EnumClass::getFormSchema()`.

```php
// ❌ ERRATO — static call a metodo di istanza
$schema = AddressItemEnum::getFormSchema();

// ✅ CORRETTO — un case concreto come receiver
$schema = AddressItemEnum::NAME->getFormSchema();

// ✅ CORRETTO — per una classe Schemas/*Form.php o *Infolist.php
$schema = app(AddressForm::class)->getFormSchema();
// oppure, dentro un metodo static factory della stessa classe:
$schema = (new self())->getFormSchema();
```

Il corpo di `EnumTrait::getFormSchema()` itera `static::cases()`, quindi il case
scelto come receiver non altera il risultato — ma **verificare sempre il corpo
del metodo prima di assumerlo**: se in futuro dipendesse dal case specifico,
prendere un case a caso introdurrebbe un difetto silenzioso.

Regressione ricorrente e cross-modulo, non un caso isolato: story
[18.41](./stories/18.41.test-chiamano-getformschema-staticamente.story.md) (7
file di test) ed epic
[5.86](./stories/5.86.xotbaseresourceform-infolist-trait-based-instance-pattern-epic.story.md)
(forma canonica) la tracciano; swarm PHPStan del 2026-09-10 ha trovato altre 10+
occorrenze indipendenti su AI, Geo, Notify, Lang, UI, Quaeris — story di modulo
in `Modules/<X>/docs/stories/phpstan-*-fix-2026-09-10.story.md`. Nessuna guardia
meccanica impedisce a un nuovo test/call-site di riscrivere la forma statica:
resta un gap aperto (18.41 AC, task "guardia").

## Documentazione Correlata

- [XotBaseResource](./XOT_BASE_RESOURCE.md)
- [Form Components](./FORM_COMPONENTS.md)
- [Form Validation](./FORM_VALIDATION.md)
- [Filament Best Practices](../../docs/rules/filament_best_practices.md)
- [Story 18.41 — test che chiamano getFormSchema staticamente](./stories/18.41.test-chiamano-getformschema-staticamente.story.md)
- [Epic 5.86 — forma canonica istanza](./stories/5.86.xotbaseresourceform-infolist-trait-based-instance-pattern-epic.story.md)
