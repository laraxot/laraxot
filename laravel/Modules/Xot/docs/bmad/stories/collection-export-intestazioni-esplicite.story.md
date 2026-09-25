---
id: Xot/collection-export-intestazioni-esplicite
title: "CollectionExport: campi con intestazione esplicita (chiave stringa = percorso, valore = intestazione)"
epic: "5"
story: "collection-export-intestazioni-esplicite"
slug: collection-export-intestazioni-esplicite
status: done
module: Xot
priority: P1
created: 2026-09-22
updated: 2026-09-22
github_repo: laraxot/module_xot_fila5
github_issue: https://github.com/laraxot/module_xot_fila5/issues/135
github_discussion: https://github.com/laraxot/module_xot_fila5/discussions/136
related:
  - ../../../app/Exports/CollectionExport.php
  - ../../../app/Actions/Export/ExportXlsByCollection.php
  - ../../../app/Filament/Actions/Header/ExportXlsAction.php
  - ../../../tests/Unit/Exports/CollectionExportLabelledFieldsTest.php
  - ../../../../Lang/app/Actions/TransArrayAction.php
  - ../../../../IndennitaResponsabilita/docs/bmad/stories/5.159-xls-ratings-titles-ux.story.md
  - ../../../../Ptv/docs/bmad/architecture/export-xls-base-list-schedas.md
  - ../../../../Ptv/docs/bmad/stories/5.147-export-xls-base-list-schedas.story.md
  - ../../../../Rating/tests/Unit/HasRatingsTraitRatingsByIdTest.php
  - ../../../../Rating/docs/stories/18.56.ratings-by-id-accessor.story.md
  - ../../../../../../bashscripts/ai/wiki/memories/php-array-one-key-per-line.md
qmd: "story xot CollectionExport fields intestazione esplicita chiave stringa percorso data_get TransArrayAction ExportXlsByCollection ExportXlsAction array_values perde chiavi dddx RuntimeException export xls ratings title"
---

# Story Xot: CollectionExport con intestazioni esplicite

Status: done

Lingua: italiano. Data: 2026-09-22. Repo GitHub del file: `laraxot/module_xot_fila5`
(`git -C laravel/Modules/Xot remote -v`).

## Story

Come sviluppatore di un modulo che esporta una lista Filament in XLS,
voglio poter dichiarare per un campo un'intestazione esplicita (es. il `title` di un rating)
accanto al percorso `data_get` che ne estrae il valore,
cosi' che l'Excel mostri "Obiettivo A" e non `ratings_by_id.52.pivot.value`,
senza scrivere file lang a runtime e senza accoppiare l'exporter generico di Xot al dominio Rating.

## Contesto misurato (fonte: dossier facts.md, sezione 2, 5, 6B; righe verificate sul codice a HEAD `d7e753a23f`, working tree pulito)

Catena di esecuzione del tasto `export_xls` (verificata):

1. `Modules/Xot/app/Filament/Actions/Header/ExportXlsAction.php`, closure `->action(...)`:
   `$rows = $livewire->getFilteredTableQuery()->get()`, `$fields = $resource::getXlsFields($livewire->tableFilters)`,
   `$transKey = GetTransKeyAction($livewire::class).'.fields'`.
2. `app(ExportXlsByCollection::class)->execute($rows, $filename, $transKey, array_values($fields))`
   (`Modules/Xot/app/Actions/Export/ExportXlsByCollection.php`).
3. `new CollectionExport($collection, $transKey, $stringFields)` (`Modules/Xot/app/Exports/CollectionExport.php`):
   `headings()` = `TransArrayAction::execute(getHead(), transKey)`; `map($row)` = `data_get($row, $field)` per ogni campo,
   poi `SafeStringCastAction::cast($value)` (`null` diventa `''`, riga 33 di `SafeStringCastAction.php`).
4. `Modules/Lang/app/Actions/TransArrayAction.php::trans()`: prova `<transKey>.<campo>.label`, poi
   `<transKey>.<campo con . sostituito da _>`, altrimenti ritorna il campo grezzo.

Stato a HEAD `7ba3feacd4` (2026-09-22 14:34, commit successivo alla misura del dossier):

- `IndennitaResponsabilitaResource::getXlsFields()` (righe 106-143) genera `'ratings_by_id.'.$rating->id.'.pivot.value'`
  come lista (chiavi intere). L'accessor `getRatingsByIdAttribute()` vive sul model IR
  (`Modules/IndennitaResponsabilita/app/Models/IndennitaResponsabilita.php`, riga 507): `$this->ratings->keyBy('id')`.
  Il problema del VALORE (indice posizionale vs id, valore sul pivot) e' quindi indirizzato lato IR.
- Il problema dell'INTESTAZIONE resta aperto: `TransArrayAction` cerca
  `indennitaresponsabilita::scheda_dip.fields.ratings_by_id.52.pivot.value.label`, poi
  `...fields.ratings_by_id_52_pivot_value`, non trova nulla e intesta la colonna con il percorso grezzo.
  Nessun contratto di `CollectionExport` permette oggi di passare un'intestazione insieme al percorso.
- In `CollectionExport` non esiste nessun `keyBy` (il gate "[x] CollectionExport::map() risolve ratings.{id}.value"
  in `Modules/Ptv/docs/bmad/architecture/export-xls-base-list-schedas.md` e' spuntato ma non corrisponde al codice).

Misure del dossier da usare domani come oracolo E2E (tenant `local/ptvx`, DB 10.100.200.53, mai produzione):
rating con `anno=2026, type=dip` = 10 (id ordinati 52,34,35,36,37,38,39,40,41,42);
`SchedaDip` id 9166: `pluck('pivot.value','id')` = `{52:57, 34:1, ...}`;
`data_get($row->ratings->keyBy('id'), '52.pivot.value')` = 57.

## Contratto proposto

`CollectionExport::$fields` diventa `array<int|string, string>`:

| Chiave | Valore | Percorso `data_get` | Intestazione |
|--------|--------|---------------------|--------------|
| intera (lista, come oggi) | `'matr'` | `'matr'` | `TransArrayAction` su `'matr'` (lang, come oggi) |
| stringa | `'Obiettivo A'` | la chiave (`'ratings_by_id.52.pivot.value'`) | il valore, non passa dalla traduzione |

Retrocompatibile: una lista pura (solo chiavi intere) produce esattamente l'output di oggi.
Il chiamante puo' mescolare le due forme nello stesso array, nell'ordine in cui vuole le colonne.

## Dove oggi si perdono le chiavi (righe verificate a HEAD)

1. `Modules/Xot/app/Filament/Actions/Header/ExportXlsAction.php`
   - riga 43: docblock `/** @var array<int, string> $fields */`.
   - righe 49-67: `array_map(static function (mixed $field): string {...}, $rawFields)`. Con UN solo array
     `array_map` conserva le chiavi (anche stringa): qui le chiavi sopravvivono.
   - riga 74: `array_values($fields)` le perde. E' l'unica riga da togliere in questo file per il contratto.
   - riga 71: `dddx('method xotFields does not exist in '.$resource)`. `dddx` e' un helper di debug
     (`Modules/Xot/helpers/Helper.php`, riga 33) che stampa e interrompe: in un'azione header e' un errore di
     programmazione mascherato da dump. Inoltre il messaggio cita `xotFields` mentre il metodo controllato
     e' `getXlsFields`.
2. `Modules/Xot/app/Actions/Export/ExportXlsByCollection.php`
   - riga 32: docblock `@param array<int, string> $fields`.
   - riga 41: `$stringFields = array_map(fn (string $field): string => $field, array_values($fields));`
     perde le chiavi e non fa nulla di utile (mappa identita' su stringhe gia' stringhe).
   - righe 42-47: blocco commentato `$supportCollection` (residuo).
   - righe 48-49: `//$row=$collection->first();` e `//dddx([...data_get($row,'ratings.52')]);` (residuo della
     diagnosi sui rating).
3. `Modules/Xot/app/Exports/CollectionExport.php`
   - riga 36-37: `/** @var array<int, string>|null */ public ?array $fields = null;` (il costruttore assegna sempre
     un array: il caso `null` e' morto).
   - riga 41: docblock del costruttore `array<int, string>`.
   - righe 52-64: `getHead()` dichiara `array<int, string>`.
   - riga 74: `headings()` passa TUTTO l'array a `TransArrayAction` (`Arr::map` conserva le chiavi:
     con una chiave stringa l'intestazione esplicita verrebbe tradotta e l'array non sarebbe piu' una lista).
   - riga 109: `foreach ($this->fields as $field)` itera i VALORI: con una chiave stringa userebbe
     l'intestazione come percorso `data_get`.

## Audit dei consumer (grep `CollectionExport\b|ExportXlsByCollection` su `laravel/Modules/*/app`)

| Consumer | Come costruisce `fields` | Effetto della story |
|----------|--------------------------|---------------------|
| `Xot/app/Filament/Actions/Header/ExportXlsAction.php` | `getXlsFields()` della Resource, `array_map` + `array_values` | togliere `array_values` (riga 74), `dddx` -> `RuntimeException` |
| `Xot/app/Filament/Actions/Header/ExportTreeXlsAction.php` (righe 53-59) | `array_values(array_map(SafeStringCastAction::cast(...), (array) $fields))` | invariato: passa una lista; oggi nessun consumer di tree usa chiavi stringa |
| `Xot/app/Filament/Actions/Table/ExportXlsTableAction.php` (righe 51-64) | `$fields[] = ...` da `getXlsFields()` del RelationManager | invariato: costruisce una lista |
| `Xot/app/Actions/Export/XlsByModelClassAction.php` (riga 105) | `new CollectionExport($exportRows, $transKey)` senza fields | invariato: ramo `getHead()` da `getAttributes()` |
| `Xot/app/Actions/Export/ExportXlsByCollection.php` | passa `fields` a `CollectionExport` | passare l'array com'e' |

Resource che implementano `getXlsFields` (grep `function getXlsFields` su `laravel/Modules`): Ptv `BaseSchedaResource`
(`@return list<string|null>`), Progressioni `ProgressioniResource` (`array<int, string>`), Performance
`IndividualeResource`, `IndividualeAdmResource`, `IndividualeDipResource`, `IndividualePoResource`,
`OrganizzativaResource` (`list<string|null>`), IR `IndennitaResponsabilitaResource`. Tutte ritornano liste:
nessuna cambia comportamento. Solo IR (story 5.159) adottera' le chiavi stringa.

Fuori scope, da non toccare: `Xot/app/Exports/LazyCollectionExport.php` e
`Xot/app/Actions/Export/ExportXlsByLazyCollection.php` (classe separata, `FromIterator`, stesso pattern
`array_map(strval(...), array_values($fields))` alla riga 30). Se servira' lo stesso contratto, sara' una story a parte.

## Snippet proposto

### `Modules/Xot/app/Exports/CollectionExport.php`

```php
/** @var array<int|string, string> */
public array $fields = [];

/**
 * @param  SupportCollection<int, mixed>|EloquentCollection<int, Model>  $collection
 * @param  array<int|string, string>  $fields  Chiave intera: percorso data_get, intestazione tradotta
 *                                              via TransArrayAction. Chiave stringa: percorso data_get,
 *                                              valore = intestazione esplicita (non tradotta).
 */
public function __construct(SupportCollection|EloquentCollection $collection, ?string $transKey = null, array $fields = [])
{
    $this->collection = $collection;
    $this->transKey = $transKey;
    $this->fields = $fields;
    $this->headings = [];
}

/**
 * Campi dichiarati, oppure le colonne del primo model se nessun campo e' stato passato.
 *
 * @return array<int|string, string>
 */
public function getHead(): array
{
    if ($this->fields !== []) {
        return $this->fields;
    }

    $head = $this->collection->first();
    Assert::isInstanceOf($head, Model::class);

    return array_keys($head->getAttributes());
}

/**
 * Percorsi data_get nell'ordine delle colonne, sempre come lista.
 *
 * @return list<string>
 */
public function fieldPaths(): array
{
    $paths = [];
    foreach ($this->getHead() as $key => $value) {
        $paths[] = \is_string($key) ? $key : $value;
    }

    return $paths;
}

/**
 * Intestazioni nell'ordine delle colonne: esplicite se la chiave e' stringa, tradotte altrimenti.
 *
 * @return list<string>
 */
public function headings(): array
{
    $head = $this->getHead();
    $implicit = array_filter(
        $head,
        static fn (int|string $key): bool => \is_int($key),
        ARRAY_FILTER_USE_KEY,
    );
    $translated = app(TransArrayAction::class)->execute($implicit, $this->transKey);

    $headings = [];
    foreach ($head as $key => $value) {
        $headings[] = \is_string($key) ? $value : ($translated[$key] ?? $value);
    }

    return $headings;
}

/**
 * @return list<string>
 */
public function map(mixed $row): array
{
    if ($this->fields === []) {
        // ramo attuale (righe 91-104) invariato: SafeArrayByModelCastAction + array_values
    }

    $data = [];
    foreach ($this->fieldPaths() as $path) {
        $value = data_get($row, $path);
        if (\is_object($value) && enum_exists($value::class) && method_exists($value, 'getLabel')) {
            $value = $value->getLabel();
        }
        $data[] = SafeStringCastAction::cast($value);
    }

    return $data;
}
```

Note sui tipi (PHPStan `level: max`, `laravel/phpstan.neon` riga 15): `$fields` non e' piu' nullable
(nessun consumer lo assegna a `null`: grep su `laravel/Modules/*/app`); `getHead()` ritorna
`array<int|string, string>`; `fieldPaths()` e `headings()` ritornano `list<string>` cosi' maatwebsite/excel riceve
sempre liste; `$translated[$key] ?? $value` copre il caso teorico in cui `TransArrayAction` non restituisca la chiave.
La property `public array $headings` (riga 32) resta assegnata a `[]` e mai letta: residuo preesistente, si segnala,
non e' in scope.

### `Modules/Xot/app/Actions/Export/ExportXlsByCollection.php`

```php
/**
 * Esporta una collezione in Excel.
 *
 * @param  Collection<int|string, mixed>|EloquentCollection<int, Model>  $collection  La collezione da esportare
 * @param  string  $filename  Nome del file Excel
 * @param  string|null  $transKey  Chiave di traduzione per le intestazioni implicite
 * @param  array<int|string, string>  $fields  Vedi CollectionExport::__construct()
 */
public function execute(
    Collection|EloquentCollection $collection,
    string $filename = 'test.xlsx',
    ?string $transKey = null,
    array $fields = [],
): BinaryFileResponse {
    $export = new CollectionExport(
        collection: $collection,
        transKey: $transKey,
        fields: $fields,
    );

    return Excel::download($export, $filename);
}
```

Rimossi: riga 41 (`array_map` + `array_values`), righe 42-47 (blocco `$supportCollection`), righe 48-49
(`//$row` e `//dddx`). `executeWithSpreadsheet()`, `writeHeader()`, `writeRows()`, `extractValue()`,
`convertToSupportCollection()` non sono nel percorso principale: invariati.

### `Modules/Xot/app/Filament/Actions/Header/ExportXlsAction.php`

```php
$resource = $livewire->getResource();
if (! method_exists($resource, 'getXlsFields')) {
    throw new \RuntimeException('method getXlsFields does not exist in '.$resource);
}

$rawFields = $resource::getXlsFields($livewire->tableFilters);
Assert::isArray($rawFields);

/** @var array<int|string, string> $fields */
$fields = array_map(
    static function (mixed $field): string {
        // corpo attuale (righe 50-65) invariato: __toString, scalari, altrimenti ''
    },
    $rawFields,
);

return app(ExportXlsByCollection::class)->execute($rows, $filename, $transKey, $fields);
```

Cambiano tre cose: niente `else` (guardia con `throw` prima), niente `dddx` (un `RuntimeException` e' l'errore di
programmazione che e'), niente `array_values` alla riga 74. `array_map` con un solo array conserva le chiavi:
la closure di normalizzazione resta.

## Test gia' scritto: `Modules/Xot/tests/Unit/Exports/CollectionExportLabelledFieldsTest.php` (in HEAD: aggiunto da `7ba3feacd4`, ritoccato da `d7e753a23f` alle 14:39, solo import `Collection`)

Pest, `uses(Modules\Xot\Tests\TestCase::class)`, nessun DB: righe = `collect([[ 'matr' => 7, 'ratings_by_id' => [52 => ['pivot' => ['value' => 57]]] ]])`.

| # | Test | Stato oggi | Perche' |
|---|------|------------|---------|
| 1 | `una lista di campi resta com'era: intestazione = campo`: `['matr']` -> headings `['matr']`, map `['7']` | verde, attivo | retrocompatibilita', deve restare verde |
| 2 | `una chiave stringa e' il percorso, il valore e' l'intestazione`: `['matr', 'ratings_by_id.52.pivot.value' => 'Obiettivo A']` -> headings `['matr', 'Obiettivo A']`, map `['7', '57']` | `->todo()` (rosso) | oggi `headings()` ritorna `[0 => 'matr', 'ratings_by_id...' => 'Obiettivo A']` e `toBe` confronta anche le chiavi; `map()` usa `'Obiettivo A'` come percorso |
| 3 | `l'intestazione esplicita non passa dalla traduzione, quella implicita si'`: transKey `xot::inesistente.fields`, stessi campi -> headings `['matr', 'Obiettivo A']` | `->todo()` (rosso) | stessa forma sbagliata di headings() |
| 4 | `un percorso assente esporta una cella vuota, non salta la colonna`: `['ratings_by_id.99.pivot.value' => 'Obiettivo Z', 'matr']` -> map `['', '7']` | verde, attivo | verde per coincidenza: oggi `data_get($row, 'Obiettivo Z')` e' `null` -> `''`; dopo la story e' verde per il motivo giusto (percorso `...99...` assente) |

Attenzione al test 3: usa una chiave lang inesistente, quindi da solo non distingue "non tradotta" da
"tradotta ma mancante". Rafforzamento opzionale domani: registrare una label con
`app('translator')->addLines(['inesistente.fields.Obiettivo A.label' => 'NO'], 'it', 'xot')`
(`Illuminate\Translation\Translator::addLines`, riga 350) e verificare che l'intestazione resti `Obiettivo A`.

## Perche' NON `keyBy('id')` dentro il core (ADR-003 del peer, `Modules/Ptv/docs/bmad/architecture/export-xls-base-list-schedas.md` righe 55-66)

ADR-003 sceglie l'opzione D: applicare `keyBy('id')` alla relazione `ratings` dentro `CollectionExport::map()` o
`ExportXlsByCollection::execute()` "quando il campo inizia con `ratings.`". Si scarta per queste ragioni:

1. Direzione delle dipendenze. `CollectionExport` e' il core di Xot e serve 5 consumer su qualunque model
   (`XlsByModelClassAction` esporta per `class-string<Model>`). Un prefisso `ratings.` e' conoscenza del modulo
   Rating: Xot non deve conoscere Rating, e' Rating che dipende da Xot.
2. Ambiguita' silenziosa. `ratings.0.value` (indice) e `ratings.52.value` (id) hanno la stessa forma: l'exporter
   dovrebbe indovinare. Il dossier misura che con piu' di 52 rating caricati `ratings.52` restituirebbe il rating
   sbagliato senza errore. Un `keyBy` per prefisso trasformerebbe questa ambiguita' in una regola globale per ogni
   model che abbia una relazione chiamata `ratings`.
3. Non basta. Il valore sta su `pivot.value`, non su `value` (misura del dossier: `data_get($row,'ratings.52.value')`
   e' `null` anche dopo `keyBy`). ADR-003 non lo dice e il suo gate "risolve ratings.{id}.value" e' spuntato ma falso a HEAD.
4. Il componente porta la propria logica. Chi possiede `ratings` espone la collection re-indicizzata
   (`ratings_by_id`: oggi accessor sul model IR a HEAD; design A del dossier: accessor + `ratingValuePath()` in
   `HasRatingsTrait`, test `Modules/Rating/tests/Unit/HasRatingsTraitRatingsByIdTest.php` gia' scritto, 4 casi
   `->todo()`). Il percorso lo costruisce il proprietario; l'exporter resta un `data_get` + intestazione, testabile
   senza DB.
5. `keyBy` non risolve l'intestazione, che e' l'oggetto di questa story. Il contratto "chiave stringa = percorso,
   valore = intestazione" lo risolve in modo generico e riutilizzabile: anche Performance
   `IndividualeResource::getXlsFields()` (righe 147-156) potra' smettere di scrivere il lang a runtime con
   `SaveTransAction` (alternativa 3 scartata nel dossier: scrive file in produzione ed e' l'origine delle violazioni
   "array una chiave per riga").

## Acceptance criteria

1. `new CollectionExport(rows, null, ['matr', 'ratings_by_id.52.pivot.value' => 'Obiettivo A'])`:
   `headings()` = `['matr', 'Obiettivo A']`, `map(first)` = `['7', '57']` (test 2).
2. Con `transKey` valorizzato l'intestazione esplicita non viene tradotta; quella implicita passa da
   `TransArrayAction` come oggi (test 3).
3. Una lista pura produce lo stesso output di oggi (test 1, test 4); i 4 consumer che passano liste non cambiano
   comportamento; `XlsByModelClassAction` senza fields continua a intestare con `getAttributes()`.
4. `ExportXlsByCollection::execute()` passa `$fields` a `CollectionExport` senza `array_values`/`array_map`;
   righe 41-49 attuali rimosse.
5. `ExportXlsAction`: nessun `array_values` (riga 74), nessun `dddx` (riga 71) sostituito da
   `throw new \RuntimeException('method getXlsFields does not exist in '.$resource)`, nessun `else`.
6. `->todo()` rimosso dai test 2 e 3; `vendor/bin/pest Modules/Xot/tests/Unit/Exports/CollectionExportLabelledFieldsTest.php`
   verde con 4 test.
7. `bash phpstan-analyze-modules.sh Xot` (da `laravel/`, `level: max`): nessun errore. `vendor/bin/pint` sui 3 file
   sorgente e sul test: `passed`. Array PHP una chiave per riga, tipi espliciti, niente `mixed` dove il tipo esiste.
8. E2E in dev (10.100.200.53), dopo che IR 5.159 ha adottato le chiavi stringa: xlsx generato per
   `ListSchedaDips` con filtri `anno=2026, valutatore_id=580, ha_diritto=1, has_rating_values=1`, riletto con
   PhpSpreadsheet: intestazione della colonna del rating 52 = `title` del rating 52, riga della scheda 9166 = `57`.
   Mai su 10.100.200.15.
9. `LazyCollectionExport` e `ExportXlsByLazyCollection` non toccati.

## Tasks per domani

- [ ] Lock (AC: tutti): `bash bashscripts/lock/lock.sh` su `CollectionExport.php`, `ExportXlsByCollection.php`,
      `ExportXlsAction.php`, `CollectionExportLabelledFieldsTest.php`. Alle 2026-09-22 sera
      `bashscripts/lock/status.sh` non mostra lock attivi: verificare di nuovo prima di partire.
- [ ] Rileggere i 3 file subito prima di editare (sessioni concorrenti sullo stesso checkout: sulla catena
      export sono arrivati oggi i commit `38d0757e09` 14:15 e `e034881852` 14:17 del peer `agent-codemonkey`
      secondo il dossier, poi `7ba3feacd4` 14:34 e `d7e753a23f` 14:39; stesso git user per tutte le sessioni,
      l'autore di sessione non e' ricavabile da `git log`).
- [ ] `CollectionExport.php` (AC 1, 2, 3): tipi `array<int|string, string>`, `$fields` non nullable, `getHead()`,
      nuovo `fieldPaths()`, `headings()` con filtro chiavi intere, `map()` su `fieldPaths()`.
- [ ] `ExportXlsByCollection.php` (AC 4): passare `$fields`, rimuovere righe 41-49, aggiornare docblock riga 32.
- [ ] `ExportXlsAction.php` (AC 5): guardia con `RuntimeException`, togliere `array_values`, docblock riga 43.
- [ ] Test (AC 6): togliere `->todo()` da test 2 e 3; opzionale rafforzare test 3 con `addLines`; eseguire Pest.
- [ ] Qualita' (AC 7): PHPStan max su Xot, Pint sui 4 file.
- [ ] E2E (AC 8): solo dopo IR 5.159; script nello scratchpad che costruisce `CollectionExport` con i campi di
      `SchedaDipResource::getXlsFields(['anno_valutatore' => ['anno' => 2026, 'valutatore_id' => 580]])`,
      scrive l'xlsx e lo rilegge con PhpSpreadsheet.
- [ ] Second brain: `graphify update .`, `bash bashscripts/docs/llm-wiki-qmd.sh update`; memoria breve su
      "array_values perde le chiavi stringa, array_map con un solo array no".
- [ ] Commit dalla root (`base_ptvx_fila5`), messaggio `refactor(Xot): CollectionExport accepts explicit headings
      via string keys, drop array_values and dddx in export chain`. Issue e discussion su
      `laraxot/module_xot_fila5` (TBD nel frontmatter: compila l'orchestratore). Unlock.

## Errori da non ripetere (dal dossier e da questa sessione)

- Il titolo di un commit non e' una prova: `38d0757e09` dichiarava "fix XLS export missing ratings columns" con
  il percorso `ratings.{id}.value` che la misura dice `null`. Prima di dire "risolto" si rilegge l'xlsx.
- `toBe()` su array confronta anche le chiavi: un `headings()` che conserva le chiavi stringa fallisce anche se i
  valori sono giusti. Ritornare sempre liste.
- Un test verde puo' esserlo per coincidenza (test 4 oggi): leggere perche' e' verde, non solo che lo e'.
- `array_map` con un array conserva le chiavi, `array_values` le butta: la riga da togliere e' una sola per file.
- `dddx` in un'azione non e' gestione dell'errore: e' un dump che muore. Errore di programmazione = `RuntimeException`.
- Il repo git del modulo IR e' corrotto (story 5.138): si committa dalla root. Xot e' sano ma si committa comunque
  dalla root per coerenza fra sessioni.
- Mai Pest su 10.100.200.15; il tenant di misura e' `local/ptvx` su 10.100.200.53.

## Dev Notes

- Pattern: l'exporter e' generico e stupido (percorso + intestazione); la conoscenza del dominio (`ratings_by_id`,
  `ratingValuePath`) sta nel model/trait che possiede la relazione. Memoria: componente riutilizzabile porta la
  propria logica.
- Regole progetto: array PHP una chiave per riga; niente `else`; `mixed` solo dove il tipo non esiste; mai estendere
  Filament diretto (qui non serve: `ExportXlsAction extends XotBaseAction`, invariato).
- Testing: unit Pest senza DB per Xot (gia' scritto); E2E solo in dev con i numeri del dossier come oracolo.

### Project Structure Notes

- File toccati: 3 sorgenti Xot + 1 test Xot. Nessun file di altri moduli in questa story
  (IR: story 5.159; Rating: story `Modules/Rating/docs/stories/18.56.ratings-by-id-accessor.story.md`, scritta oggi,
  ancora untracked; Ptv: story 5.147 e ADR in `export-xls-base-list-schedas.md`, da correggere dal peer dopo confronto
  su ADR-003).
- `docs/sprint-status.yaml`: entry di questa story a carico dell'orchestratore (non toccato qui).

### References

- [Source: Modules/Xot/app/Exports/CollectionExport.php] righe 31-64, 66-75, 107-119
- [Source: Modules/Xot/app/Actions/Export/ExportXlsByCollection.php] righe 26-57
- [Source: Modules/Xot/app/Filament/Actions/Header/ExportXlsAction.php] righe 41-74
- [Source: Modules/Xot/app/Filament/Actions/Header/ExportTreeXlsAction.php] righe 51-63
- [Source: Modules/Xot/app/Filament/Actions/Table/ExportXlsTableAction.php] righe 49-66
- [Source: Modules/Xot/app/Actions/Export/XlsByModelClassAction.php] righe 101-108
- [Source: Modules/Lang/app/Actions/TransArrayAction.php] righe 26-55, 63-96
- [Source: Modules/Xot/tests/Unit/Exports/CollectionExportLabelledFieldsTest.php] 4 test
- [Source: Modules/Ptv/docs/bmad/architecture/export-xls-base-list-schedas.md#ADR-003]
- [Source: Modules/IndennitaResponsabilita/app/Filament/Resources/IndennitaResponsabilitaResource.php] righe 106-143
- [Source: Modules/IndennitaResponsabilita/app/Models/IndennitaResponsabilita.php] riga 507
- [Source: scratchpad facts.md] sezioni 2, 5, 6B, 9 (sessione claude-fable-f3a6e661, 2026-09-22)

## Dev Agent Record

### Agent Model Used

Claude Fable 5.1 (claude-fable-5-1) come agente documentale BMAD, 2026-09-22.

### Debug Log References

- Misura tinker del dossier (scratchpad `measure.php`): `data_get($row,'ratings.52.value') = NULL`,
  `data_get($row->ratings->keyBy('id'), '52.pivot.value') = 57`.

### Completion Notes List

- Story scritta; codice non toccato; test gia' presente in HEAD (`d7e753a23f`) con 2 casi `->todo()`.

### File List

- `laravel/Modules/Xot/docs/bmad/stories/collection-export-intestazioni-esplicite.story.md` (questa story)
- Da modificare domani: `laravel/Modules/Xot/app/Exports/CollectionExport.php`,
  `laravel/Modules/Xot/app/Actions/Export/ExportXlsByCollection.php`,
  `laravel/Modules/Xot/app/Filament/Actions/Header/ExportXlsAction.php`,
  `laravel/Modules/Xot/tests/Unit/Exports/CollectionExportLabelledFieldsTest.php`

## Esito (2026-09-22, sessione devin)

Implementato il formato misto `array<int|string, string>`:
- `CollectionExport::headings()` — label esplicita bypassa `TransArrayAction`;
  chiave intera = percorso tradotto;
- `CollectionExport::map()` — la chiave stringa e' il percorso `data_get`;
- `ExportXlsByCollection::execute()` non fa piu' `array_values($fields)`;
- `ExportXlsAction` e `ExportXlsTableAction` conservano le chiavi stringa;
- `ExportXlsLazyAction` degrada le label al percorso (canale lazy: solo path,
  documentato in `Ptv/docs/bmad/architecture/export-xls-base-list-schedas.md`).

Test `CollectionExportLabelledFieldsTest`: `->todo()` rimossi, verificato con
harness standalone (Pest bloccato: DB irraggiungibile). PHPStan max: 0 errori.
