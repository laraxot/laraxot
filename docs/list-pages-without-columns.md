# Pagine list senza colonne

## Il difetto

Una pagina che estende `XotBaseListRecords` **non eredita le colonne dal `table()` della
Resource**. `HasXotTable::table()` ricostruisce la tabella da zero:

```php
$table->columns($this->layoutView->getTableColumns(array_values($this->getTableColumns()), ...))
```

Se la pagina non dichiara `getTableColumns()` e la Resource non ha la classe
`Tables\<Model>sTable` che `XotBaseResource::table()` cerca, il `table()` della Resource
viene ignorato e la tabella esce **senza colonne**: una pagina vuota in faccia
all'utente.

Il difetto e' silenzioso. Non lancia eccezioni, non fa fallire i test di dominio, e la
pagina risponde `200`. La suite di WorkOrder era verde con la sua lista principale vuota.

## Come si riconosce

```php
Livewire::test(ListWorkOrders::class)
    ->assertSuccessful()          // passa
    ->assertTableColumnExists('code');  // fallisce
```

Verificato cosi' su quattro pagine native:

| Pagina | Colonna dichiarata nella Resource | Presente a runtime |
|---|---|---|
| `ListWorkOrders` | `code` | no |
| `ListCustomers` | `legal_name` | no |
| `ListInterventions` | `code` | no |
| `ListEmployees` | `id` | no |

## Il presidio

`Modules/Xot/tests/Feature/ListRecordsPagesDeclareColumnsTest.php` scandisce tutte le
pagine che estendono `XotBaseListRecords` e fallisce su quelle che non dichiarano le
colonne ne' hanno una classe `Tables/`. La lista `DEBITO` in cima al test elenca le
pagine gia' in questo stato: **e' una lista da accorciare, mai da allungare**.

## Il debito residuo (16 pagine)

Tutte precedono questo intervento. Non sono state toccate perche' appartengono a moduli
in uso, e sistemarle e' una decisione che va presa esplicitamente, non di iniziativa.

| Modulo | Pagina |
|---|---|
| Catalog | `ListCatalogFrequencies` |
| Catalog | `ListCatalogMeasureUnits` |
| Catalog | `ListCatalogModels` |
| Catalog | `ListCatalogPartTypes` |
| Catalog | `ListMetelCatalogs` |
| Catalog | `ListProducts` |
| Customer | `ListCustomers` |
| Customer | `ListResellers` |
| Geo | `ListLocations` |
| HR | `ListAbsenceRequests` |
| HR | `ListEmployees` |
| Intervention | `ListDailyNotes` |
| Intervention | `ListInterventions` |
| Lang | `LangBaseListRecords` |
| UI | `BaseListRecords` |
| WorkOrder | `ListWorkOrders` |

`LangBaseListRecords` e `BaseListRecords` sono classi base: vanno guardate per prime,
perche' quello che decidono vale per tutto cio' che le estende.

## Come si sistema una pagina

Si spostano colonne, filtri, azioni e ordinamento dal `table()` della Resource alla
pagina, con **chiavi stringa** (il contratto di `HasXotTable` le richiede):

```php
/** @return array<string, Column> */
public function getTableColumns(): array
{
    return [
        'code' => TextColumn::make('code')->searchable()->sortable(),
        'name' => TextColumn::make('name')->searchable(),
    ];
}

public function getDefaultTableSortColumn(): string
{
    return 'sort_order';
}

/** @return array<string, Action> */
public function getTableActions(): array
{
    return ['edit' => EditAction::make(), 'delete' => DeleteAction::make()];
}
```

Attenzione a due cose che il solo spostamento non risolve:

- i metodi privati della Resource richiamati dalle closure (`payloadValue`,
  `familyOptions`, `hasDownloadablePdf`) vanno resi `public` e qualificati con il nome
  della Resource, non con `self::`, che ora punta alla pagina;
- i modelli usati nelle closure vanno importati nella pagina.

L'alternativa e' la classe `Tables\<Model>sTable`, che `XotBaseResource::table()` cerca
da sola: la usano gia' 73 Resource nel progetto, ed e' la strada preferibile quando la
stessa tabella serve a piu' pagine.
