# Filament 5: Select su relazione con colonna titolo nullable

## Sintomo

```
TypeError
Filament\Forms\Components\Select::isOptionDisabled(): Argument #2 ($label) must be of type
Illuminate\Contracts\Support\Htmlable|string, null given, called in
vendor/filament/forms/src/Components/Select.php on line 155
```

La pagina di edit (o di create) va in errore 500 appena il Select viene renderizzato. Basta un
solo record correlato con la colonna titolo a `NULL` per far fallire l'intera pagina.

## Causa

`Select::relationship($name, $titleAttribute)` carica le opzioni con `pluck($titleAttribute, $key)`.
Se la colonna e' nullable, l'array delle opzioni contiene una etichetta `null`. In `Select::setUp()`
la closure `transformOptionsForJsUsing` passa quel valore a
`isOptionDisabled($value, $label)`, tipizzato `string|Htmlable`: TypeError.

Il caso reale: `customers.name` nulla su 1 record su 22 rompeva
`/user/admin/tenant-users/{record}/edit`.

## Soluzione

`XotBaseResourceForm::relationshipSelect()` costruisce il Select con un callback per record, che
non passa mai `null`:

```php
'customer_id' => static::relationshipSelect('customer_id', 'customer')
    ->searchable()
    ->preload()
    ->required(),
```

Il fallback e' la chiave primaria (`#24`), cosi' l'opzione resta selezionabile e distinguibile
invece di far fallire la pagina.

## Perche' l'ordinamento e' esplicito

Filament applica `orderBy($titleAttribute)` solo sul ramo `pluck()`. Impostando
`getOptionLabelFromRecordUsing()` il componente passa al ramo `get()`, dove quell'ordinamento non
viene aggiunto. `relationshipSelect()` lo richiede quindi esplicitamente nel `modifyQueryUsing`,
per non perdere l'ordine alfabetico delle opzioni.

## Quando serve il callback da solo

Per Select gia' configurati altrove si puo' usare solo il callback:

```php
Select::make('customer_id')
    ->relationship('customer', 'name')
    ->getOptionLabelFromRecordUsing(static::optionLabelFromRecord())
```

`optionLabelFromRecord()` accetta il nome della colonna titolo (default `name`) e supporta la
sintassi JSON `colonna->chiave` usata da Filament.

## Verifica

```bash
cd laravel
./vendor/bin/pest Modules/Xot/tests/Unit/Filament/OptionLabelFromRecordTest.php --no-coverage
```

## Collegamenti

- [filament-5-list-table-columns-delegation.md](./filament-5-list-table-columns-delegation.md)
- [filament-5-migration-guide.md](./filament-5-migration-guide.md)
