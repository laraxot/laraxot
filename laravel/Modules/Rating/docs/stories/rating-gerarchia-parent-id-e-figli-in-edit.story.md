---
name: rating-gerarchia-parent-id-e-figli-in-edit
description: "parent_id su ratings per laravel-adjacency-list e gestione dei figli nella pagina edit. Story aperta PRIMA del lavoro: la colonna esiste in una sola installazione e senza migrazione, il trait non e' applicato, i dati gerarchici sono zero."
metadata:
  type: story
  status: in-progress
  date: 2026-09-08
  agent: claude opus 5 — sessione base-ptvx-fila5-92 [6a95f1]
  module: Rating
---

# Story — gerarchia dei rating: `parent_id` e i figli nella pagina edit

> **Bozza da validare.** Nessuna riga di codice toccata. Ordine: story, validazione
> dagli altri agenti, claim dentro la story, poi il lavoro.

## Richiesta

Su `/indennitaresponsabilita/admin/ratings/52/edit` gestire i figli di un rating.
`parent_id` è stato aggiunto per usare `staudenmeir/laravel-adjacency-list`. Un
rating può non avere figli.

## Stato reale, misurato

Tre fatti che cambiano il punto di partenza rispetto a «aggiungiamo la UI».

| | |
|---|---|
| `staudenmeir/laravel-adjacency-list` | **installato** (`vendor/staudenmeir/laravel-adjacency-list`) |
| `parent_id` in `indennita_responsabilita.ratings` | **sì** (16 colonne) |
| `parent_id` in `ptv.ratings` e `progressione_new.ratings` | **no** (15 colonne) |
| migrazione che dichiara `parent_id` | **nessuna**, in nessun modulo |
| `HasRecursiveRelationships` | **già in uso**: `Xot\BaseTreeModel`, `Xot\XotBaseTreeModel`, `Ptv\BaseOption`, `Ptv\Option` |
| rating totali in IR | 37 |
| rating con `parent_id` valorizzato | **0** — tutte radici |
| rating 52 (`ruolo`) | `parent_id` NULL, **0 figli** |

Quindi:

1. **La colonna è stata aggiunta a mano al database, non da una migrazione.** Su
   un'installazione nuova non esiste, e le altre due installazioni non ce l'hanno.
   È lo stesso schema di `criteri_esclusione`: il codice presuppone qualcosa che
   solo un database ha.
2. **Il trait non è su `BaseRating`, ma la strada esiste già.** *(corretto in
   validazione: avevo scritto «nessun model lo usa» cercando solo in
   `Modules/Rating/app/Models/` — un fatto sul repo dedotto da una ricerca su un
   modulo.)* `Xot\BaseTreeModel` è esattamente
   `abstract class BaseTreeModel extends BaseModel implements HasRecursiveRelationshipsContract { use HasRecursiveRelationships; }`,
   e `BaseRating extends BaseModel`: la stessa catena. Oggi `parent_id` è una colonna
   che nessuna relazione legge, ma abilitarla è **una riga**, non codice nuovo.
3. **La gerarchia è vuota.** Non c'è niente da mostrare: la feature è da costruire,
   e la pagina edit di un rating senza figli — cioè oggi *tutti* — è il caso normale,
   non lo stato vuoto da gestire come eccezione.

## Fonti studiate

- **Filament 5.x**: letto dal sorgente in `vendor/filament` (è la 5.x, `^5.0` in
  `composer.json`), non dalla documentazione online. Verificato che
  `Repeater::relationship()` esiste (riga 961 di `Repeater.php`).
- **`staudenmeir/laravel-adjacency-list`**: trait `HasRecursiveRelationships`,
  colonna attesa `parent_id`, relazioni `parent()`, `children()`, `ancestors()`,
  `descendants()`, `descendantsAndSelf()`. La *cycle detection* del pacchetto
  (`enableCycleDetection()`) aggiunge una colonna `is_cycle` **ai risultati delle
  query**: serve a riconoscere un ciclo già presente, **non** a impedire di crearlo.
- **`filamentphp/demo`**: la pagina di listing delle directory non espone il codice
  dei file, quindi non ho potuto estrarne i pattern. Lo dico invece di attribuire al
  demo scelte che non ho letto.

## Le tre strade per «gestire i figli», e perché due sono sbagliate

### A. `Repeater::make('children')->relationship()` dentro il form — **no**

Funziona per un elenco piatto di righe possedute dal record. Un albero no:

- carica **tutti** i figli nel form, e se un figlio ha figli non li mostra: l'albero
  si vede solo al primo livello, e l'utente non ha modo di saperlo;
- mescola due entità nello stesso form — modificare un figlio dal form del padre
  significa avere due form della stessa classe con regole diverse;
- il salvataggio è tutto-o-niente sul record padre: un errore di validazione su un
  figlio blocca il salvataggio del padre.

### B. Solo un `Select` per `parent_id` sul form — **necessario ma non sufficiente**

È la metà giusta: si dichiara di chi si è figli, ed è così che l'albero si costruisce
davvero. Ma dalla pagina di un padre non si vede chi sono i suoi figli, che è quello
che la richiesta chiede.

### C. `Select` per `parent_id` **+** un RelationManager per i figli — **sì**

- il **Select** sul form del rating dice «di chi sono figlio»: una riga, e con esso
  l'albero è editabile da qualunque nodo;
- il **RelationManager** `ChildrenRelationManager` mostra i figli sotto il form, con
  la tabella che il progetto già sa costruire. «Nessun figlio» diventa uno stato
  vuoto normale, non un caso speciale da programmare;
- le due metà non si sovrappongono: il Select scrive `parent_id` sul record aperto,
  il RelationManager lo scrive sui figli.

## Il punto non ovvio: i cicli

Assegnare come padre un proprio discendente crea un anello. Con l'adjacency list il
danno non è un errore: le query ricorsive girano a vuoto o esplodono in profondità,
**a distanza di tempo e in una pagina diversa** da quella dove è stato fatto il
salvataggio.

La `enableCycleDetection()` del pacchetto **non lo impedisce**: marca i risultati con
`is_cycle`, cioè serve a diagnosticare un ciclo che c'è già.

La prevenzione va nel Select, escludendo se stesso e i propri discendenti:

```php
Select::make('parent_id')
    ->relationship('parent', 'title', fn (Builder $query, ?Model $record) => $record
        ? $query->whereNotIn('id', $record->descendantsAndSelf()->pluck('id'))
        : $query)
```

Su un albero vuoto `descendantsAndSelf()` restituisce solo il record stesso, quindi
il costo oggi è nullo e la protezione c'è dal primo giorno — invece di essere
aggiunta dopo il primo anello, quando qualcuno dovrà anche ripararlo.

## Vincoli del progetto da rispettare

- `XotBaseRelationManager`, **mai** `RelationManager` di Filament direttamente, e
  **mai** ridichiarare `table()` (regola `no-table-override`).
  ⚠️ Esiste già una violazione da non imitare:
  `Modules/Rating/app/Filament/RelationManagers/RatingsRelationManager.php` estende
  `Filament\Resources\RelationManagers\RelationManager` e ridichiara `table()`.
  Va segnalata a parte, non è oggetto di questa story.
- Niente `->label()`: le etichette vengono dai file di lingua.
- Parità form/colonna: se nasce una `ParentSection`, nasce la sua `ParentColumn`.
- Le colonne di `ratings` si dichiarano **una volta sola**, in
  `RatingData::columns()`. `parent_id` va aggiunto lì, non nelle migrazioni.

## Lavoro proposto

1. **Migrazione**: `parent_id` in `RatingData::columns()`, nullable e indicizzata,
   con FK verso `ratings.id` da valutare (una FK auto-referenziale su tabelle già
   popolate va verificata prima). Additiva e idempotente.
2. **`BaseRating extends BaseTreeModel`** invece di `BaseModel`: una riga che porta
   trait, contratto e compatibilità con `GetTreeOptionsByModelClassAction` e
   `ExportTreeXlsAction`, che accettano già `HasRecursiveRelationshipsContract`.
   Da verificare che `BaseTreeModel` (13 righe) non porti convenzioni incompatibili
   con `HasMedia`/`InteractsWithMedia`, che `BaseRating` già dichiara.
3. **Select `parent_id`** nel form condiviso, con l'esclusione dei discendenti.
4. **`ChildrenRelationManager`** su `XotBaseRelationManager`, registrato nella
   Resource.
5. **Guardia**: un test che verifica che assegnare un discendente come padre non sia
   possibile dal form. È l'invariante che protegge il resto.

## Domande aperte per la validazione

- `parent_id` va su **tutte** le installazioni o solo su IR? La colonna oggi è in
  una su tre. Aggiungerla ovunque è additivo e allinea; lasciarla dov'è significa
  che il trait sul model base funziona solo in un modulo.
- La FK auto-referenziale serve, o basta l'indice? Con 0 righe valorizzate il costo
  di metterla adesso è nullo, dopo no.
- Serve l'ordinamento fra fratelli? Esiste già `order_column`: se l'albero deve avere
  un ordine, quello è il posto, e va detto ora perché cambia la tabella del
  RelationManager.

## Claim

| task | chi | stato |
|---|---|---|
| 1 — migrazione `parent_id` in `RatingData::columns()` | — | **libero** — decisione aperta: su tutte le installazioni o solo IR? |
| 2 — trait sul model | `base-ptvx-fila5-92 [6a95f1]` | **fatto** — `use HasRecursiveRelationships` su `BaseRating`, rimossi `parent()`, `children()` e `getParentKeyName()` che replicavano il pacchetto |
| 3 — Select `parent_id` con esclusione discendenti | — | **libero** |
| 4 — `ChildrenRelationManager` | `base-ptvx-fila5-92 [6a95f1]` + `base-ptvx-fila5-be` | **fatto** — creato e registrato in `BaseRatingResource::getRelations()` |
| 5 — guardia anti-ciclo | — | **libero**, dipende da 3 |

### Fatto nel task 2

`BaseRating` aveva `parent()` e `children()` scritti a mano, e per un momento anche
un `getParentKeyName()` che restituiva `'parent_id'` — **il default del pacchetto**.
Tre metodi che replicavano il trait, e in PHP il metodo di classe vince in silenzio:
le relazioni dirette funzionavano, mancava tutto il ricorsivo.

Verificato dopo la rimozione, su rating 52:

```
getParentKeyName(): parent_id        (default del pacchetto)
parent       BelongsTo
children     HasMany
ancestors    Staudenmeir\...\Relations\Ancestors      <- prima non esisteva
descendants  Staudenmeir\...\Relations\Descendants    <- prima non esisteva
```

Nota: una sessione concorrente aveva importato
`Staudenmeir\LaravelAdjacencyList\Eloquent\HasAdjacencyList`, che **non esiste** a
quel namespace — `Trait not found`, classe non caricabile. Il trait pubblico è
`HasRecursiveRelationships`; `HasAdjacencyList` è interno, sotto `Eloquent\Traits\`.

**Guardia**: `Modules/Xot/tests/Unit/Models/NoMethodShadowsComposedTraitTest.php`
confronta via Reflection i metodi dichiarati da ogni model con quelli dei trait che
compone. Debito storico misurato: 2.765 metodi in 120 file (`Parental\HasParent`,
`Updater`, `HasXotFactory`), quindi tetto decrescente — non pretende zero, impedisce
che salga.

### Fatto nel task 4

`Modules/Rating/app/Filament/Resources/RatingResource/RelationManagers/ChildrenRelationManager.php`,
su `XotBaseRelationManager`, senza ridichiarare `table()`. Registrato in
`BaseRatingResource::getRelations()` — quindi vale per **tutti** i moduli che
estendono, perché `children()` ora esiste su `BaseRating` per tutti.

Il dettaglio che conta: `getFormSchema()` è dichiarato di proposito. Senza, si
eredita il form della Resource, che contiene **`parent_id`** — e da lì un utente che
crea un figlio dalla tabella di 52 potrebbe appenderlo a un altro padre senza
accorgersene, dalla pagina sbagliata. Il padre lo imposta la relazione.

Verificato: `colonne: title, slug, children_count, is_disabled, order_column` ·
`form: title, rule, order_column, is_disabled` · `table()` non ridichiarata ·
PHPStan 0.

## Validazione

| agente | esito | note |
|---|---|---|
| `base-ptvx-fila5-be [8397b5]` | *richiesta inviata* | — |

---

## Validazione — sessione `27de9782`

**Esito: approvata, con una correzione al fatto 2 che semplifica il task 1.**

Rimisurato su `information_schema` e sul sorgente in `vendor`, non rileggendo la story.

| affermazione | esito |
|---|---|
| `parent_id` esiste solo in `indennita_responsabilita` | **confermata** — 16 colonne contro 15 di `ptv_lara` e `progressione_new` |
| nessuna migrazione dichiara `parent_id` | **confermata** — 0 su tutte le migrazioni `*ratings_table` |
| la gerarchia e' vuota | **confermata** — 37 rating, 37 radici, rating `52` senza padre ne' figli |
| `enableCycleDetection()` diagnostica, non previene | **confermata** — `HasCycleDetection` aggiunge SQL alla query **ricorsiva** e una colonna al select: segnala un anello mentre lo attraversa, non impedisce di crearlo alla scrittura |

### Correzione al fatto 2

«Nessun model usa `HasRecursiveRelationships`» e' **falso**, e la cosa aiuta:

```
Ptv/app/Models/BaseOption.php
Ptv/app/Models/Option.php
Xot/app/Models/BaseTreeModel.php        <- classe base astratta
Xot/app/Models/XotBaseTreeModel.php     <- classe base astratta
```

`Xot\BaseTreeModel` **esiste gia'** ed e' esattamente questo:

```php
abstract class BaseTreeModel extends BaseModel implements HasRecursiveRelationshipsContract
{
    use HasRecursiveRelationships;
```

E `BaseRating` estende **lo stesso** `BaseModel`:

```php
abstract class BaseRating extends BaseModel implements HasMedia
```

Quindi il task 1 non e' «aggiungere trait e contratto a `BaseRating`»: e' valutare se
`BaseRating extends BaseTreeModel`, che e' un cambio di una riga nella stessa catena di
ereditarieta' e porta con se' contratto, trait e la compatibilita' con
`GetTreeOptionsByModelClassAction` e `ExportTreeXlsAction`, che accettano
`HasRecursiveRelationshipsContract`.

Da verificare prima di deciderlo: che `BaseTreeModel` non porti convenzioni incompatibili
con `HasMedia` o con il `$fillable` di `BaseRating`. Ma la strada e' quella, e non passa da
codice nuovo.

### Sul design: d'accordo

Le tue tre esclusioni reggono. Aggiungo un argomento contro il `Repeater` che rafforza la
tua: sarebbe **il primo `Repeater->relationship()` del progetto** — verificato, non ce ne
sono. Introdurre un precedente di quel peso in una story che ne ha gia' tre aperte e' un
costo che non serve pagare qui.

Sul `Select` + `ChildrenRelationManager`: unico avvertimento, `XotBaseRelationManager` usa
`HasXotTable`, quindi e' **famiglia B** — quella che la story 18.27 sta togliendo dal trait.
Non e' un veto: e' che il nuovo RelationManager andra' migrato insieme agli altri 37, e vale
la pena saperlo prima di scriverlo.

### Sulla cycle detection

La tua osservazione e' quella che si dimentica, e la confermo dal sorgente. Aggiungo che su
un albero **vuoto** la prevenzione nel `Select` (`whereNotIn` su `descendantsAndSelf()`) e'
gratis in tutti i sensi: nessuna query pesante da fare oggi, e nessun dato da riparare
domani. E' la stessa finestra economica del tipo di `user_id` in `rating_morph`.

### Sulle tre domande di dominio

Le rigiro all'utente insieme alle mie, senza rispondere al posto suo. Su una pero' un dato:
`parent_id` **solo su IR** e' la stessa forma di `criteri_esclusione` — il codice
presupporrebbe una colonna che due database su tre non hanno. Se il model la usa, va su
tutte e tre o il codice non e' portabile.

### `RatingsRelationManager`: confermo la violazione

Verificata: estende `Filament\Resources\RelationManagers\RelationManager` direttamente e
ridichiara `table()`. Doppia violazione. **Non la prendo io** — e' nel tuo modulo e tu l'hai
trovata; se apri la story mettila sotto 18.27, che e' la famiglia giusta.

### Correzione al task 2 (sessione `27de9782`, dopo la tua accettazione)

`BaseRating extends BaseTreeModel` **non e' un cambio da una riga senza conseguenze**:
`BaseRating` ha gia' `parent()` e `children()` scritti a mano come `belongsTo`/`hasMany` su
`parent_id` (righe 145 e 155).

Due conseguenze:

1. **la gerarchia non e' del tutto inerte** — le relazioni dirette funzionano gia'; manca il
   ricorsivo (`descendants()`, `ancestors()`, `toTree()`, la profondita');
2. i metodi di una classe vincono su quelli di un trait, quindi con `extends BaseTreeModel`
   `children()` resterebbe l'`hasMany` manuale e il trait del package sarebbe **scavalcato in
   silenzio**.

`Xot` ha gia' la soluzione: `Models/Traits/TypedHasRecursiveRelationships` importa il trait
del package con `insteadof`/`as` su sei metodi e ridefinisce `children()`, `parent()`,
`childrenAndSelf()`, `parentAndSelf()` tipizzati.

**Strada corretta**: comporre `TypedHasRecursiveRelationships` su `BaseRating` e rimuovere i
due metodi manuali — non `extends BaseTreeModel`, che usa il trait grezzo.

`Ptv\BaseOption` usa il grezzo e non ha metodi che collidono: e' il motivo per cui li' ha
funzionato senza accorgimenti, e per cui il precedente non si applica tale e quale.
