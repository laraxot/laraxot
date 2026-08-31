# Filament 5: colonne delle pagine di elenco

## Sintomo

Una pagina `List*` si apre con HTTP 200, la paginazione dichiara il numero corretto di record
("Mostrati da 1 a 10 di 45 risultati"), ma la tabella non ha nessuna colonna: solo la casella di
selezione e la colonna delle azioni. Nel markup si contano due soli `<th>` e le righe sono vuote.

## Causa

`Filament\Tables\Concerns\HasColumns` dichiara:

```php
/**
 * @deprecated Override the `table()` method to configure the table.
 */
protected function getTableColumns(): array
{
    return [];
}
```

`HasXotTable` dichiara `abstract protected function getTableColumns(): array;` e in
`HasXotTable::table()` costruisce la tabella con `$this->getTableColumns()`.

Una pagina di elenco eredita quindi il metodo dallo stub deprecato di Filament, che soddisfa
l'abstract del trait senza segnalare nulla: il contratto risulta rispettato e la tabella viene
configurata con un array vuoto. Nessun errore, nessun warning, solo colonne assenti.

Prima della correzione, in questo progetto, 24 pagine di elenco concrete si trovavano in questo
stato: tutte quelle che non reimplementavano `getTableColumns()` contando sulla classe Table della
Resource.

## Soluzione

`XotBaseListRecords` implementa il metodo e delega alla classe Table della Resource, che e' la
sorgente di verita' prevista dall'architettura (`XotBaseResource::getTableClass()`):

```php
protected function getTableColumns(): array
{
    $table = app(static::getResource()::getTableClass());
    Assert::isInstanceOf($table, XotBaseResourceTable::class);

    return $table->getTableColumns();
}
```

Le pagine che dichiarano un proprio `getTableColumns()` continuano a vincere sull'implementazione
della classe base: la delega vale solo per chi non lo reimplementa.

## Visibilita'

Il metodo va dichiarato `protected`, come in `HasColumns` e nell'abstract di `HasXotTable`. Le
pagine che lo reimplementano `public` restano valide (allargare la visibilita' e' consentito);
il contrario non lo sarebbe.

## Verifica

```bash
cd laravel
./vendor/bin/pest Modules/User/tests/Feature/Filament/TenantUserTableColumnsTest.php --no-coverage
```

Il test verifica che il metodo non sia piu' fornito dallo stub deprecato e che la classe Table
della Resource esponga almeno una colonna.

## Collegamenti

- [filament-5-nullable-select-option-labels.md](./filament-5-nullable-select-option-labels.md)
- [filament-5-migration-guide.md](./filament-5-migration-guide.md)
