# Struttura Form Schema in Filament Resources

## getFormSchema() Method

Il metodo `getFormSchema()` nelle Filament Resources deve restituire un array associativo dove le chiavi sono stringhe che corrispondono ai nomi dei campi.

### Formato Corretto

```php
public static function getFormSchema(): array
{
    return [
        'field_name' => TextInput::make('field_name')
            ->required(),
        'another_field' => Select::make('another_field')
            ->options([...]),
    ];
}
```

### Formato Non Corretto (Da Evitare)

```php
public static function getFormSchema(): array
{
    return [
        TextInput::make('field_name')  // Manca la chiave stringa!
            ->required(),
        Select::make('another_field')  // Manca la chiave stringa!
            ->options([...]),
    ];
}
```

### Motivazione
- La presenza delle chiavi stringa rende il codice più leggibile
- Permette un accesso più facile ai campi del form
- Mantiene una struttura coerente con altri array associativi nel framework
- Facilita la manutenzione e il debugging

### Note Importanti
1. La chiave stringa deve corrispondere al nome del campo
2. Questo pattern deve essere seguito in tutte le Resources che estendono XotBaseResource
3. È particolarmente importante quando si lavora con form complessi o nested
