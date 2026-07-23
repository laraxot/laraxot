# Schema Clean Code Rule

**Regola**: `->schema()` deve sempre chiamare un metodo che restituisce un array con chiavi stringhe

## Definizione

Tutti i metodi che chiamano `->schema()` devono ricevere come parametro un array associativo dove le chiavi sono stringhe.

## Pattern Corretto

```php
// Schema definito in un metodo che restituisce array<string, mixed>
public static function getFormSchema(): array
{
    return [
        'title' => TextInput::make('title'),
        'description' => Textarea::make('description'),
        'status' -> Select::make('status'),
    ];
}

// Utilizzo corretto
public static function form(Schema $schema): Schema
{
    return $schema->schema(static::getFormSchema());
}
```

## Pattern Errato

```php
// Schema direttamente inline (non permette riutilizzo)
public static function form(Schema $schema): Schema
{
    return $schema->schema([
        TextInput::make('title'),
        Textarea::make('description'),
        Select::make('status'),
    ]);
}
```

## Vantaggi

1. **Riutilizzabilità**: I metodi `getFormSchema()` possono essere riutilizzati in contesti diversi
2. **Testabilità**: Ogni schema può essere testato separatamente
3. **Manutenibilità**: Le modifiche allo schema possono essere fatte in un unico punto
4. **Readability**: Il codice è più leggibile e organizzato

## Applicazione

Questa regola si applica a:
- `Filament\Resources\Resources::schema()`
- `Filament\Resources\Pages\Page::schema()`
- `Filament\Widgets\Widget::schema()`
- `Filament\Forms\Form::schema()`
- `Filament\Infolists\Infolist::schema()`

## Eccezioni

Non si applica a:
- Array indicizzati per layout semplice (es. grid)
- Array temporanei per configurazione interna

## Implementazione

I metodi che seguono questa regola devono:

1. Definire un metodo `getSchemaName()` che restituisce array<string, mixed>
2. Usare quel metodo in `->schema(static::getSchemaName())`
3. Mantenere il metodo `getSchemaName()` senza side effects

## Esempi Pratici

### Resource Schema

```php
// Corretto
class UserResource extends Resource
{
    public static function schema(Schema $schema): Schema
    {
        return $schema->schema(static::getUserSchema());
    }

    public static function getUserSchema(): array
    {
        return [
            'personal_info' => Section::make('Informazioni personali')
                ->schema([
                    TextInput::make('name'),
                    TextInput::make('email'),
                ]),
            'profile' => Section::make('Profilo')
                ->schema([
                    Select::make('role'),
                    Toggle::make('active'),
                ]),
        ];
    }
}
```

### Wizard Schema

```php
// Corretto
class CreateTicketWizardWidget extends XotBaseWizardWidget
{
    public function getSteps(): array
    {
        return [
            Step::make('Dati Anagrafici')
                ->schema(static::getPersonalDataSchema()),
            Step::make('Dettagli Segnalazione')
                ->schema(static::getTicketDetailsSchema()),
        ];
    }

    public static function getPersonalDataSchema(): array
    {
        return [
            'contact_info' -> Section::make('Contatto')
                ->schema([
                    TextInput::make('name'),
                    TextInput::make('email'),
                ]),
            'address' -> Section::make('Indirizzo')
                ->schema([
                    TextInput::make('address'),
                    TextInput::make('city'),
                ]),
        ];
    }
}
```

## Verifica

Questa regola può essere verificata con:
- PHPStan custom rule per il tipo di ritorno
- Code analysis per identificare pattern non conformi
- Review manuale durante il code review