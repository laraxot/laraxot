---
title: "Base e leaf — dove sta il codice condiviso e perche'"
type: concept
status: active
module: Xot
created: 2026-09-02
tags: [base, leaf, ereditarieta, architettura, connection, factory, proprieta-del-concetto]
qmd: "base leaf pattern dove sta il codice condiviso modulo proprietario del concetto connection factory mai nominare foglia"
updated: 2026-09-02
issues:
  # DA CREARE — `gh` non autenticato: mai numeri inventati.
  # gh issue create --repo provtv/module_xot_fila5 --title "<argomento del file>"
  - "https://github.com/provtv/module_xot_fila5/issues/"
discussions:
  # DA CREARE — vedi sopra.
  - "https://github.com/provtv/module_xot_fila5/discussions/"
---

# Base e leaf

Regola di piattaforma. Vale per ogni modello condiviso fra piu' moduli, non solo per i
casi da cui e' nata.

## La regola, in due frasi

1. **Nella base sta cio' che e' vero per il modello; nel leaf solo cio' che e' vero per
   il modulo.** E cio' che e' vero per il modulo e', quasi sempre, **una riga**.
2. **La base vive nel modulo che possiede il concetto** — non in un contenitore comune,
   non "in Xot perche' e' condiviso".

La seconda frase e' quella che si sbaglia piu' spesso, e vale la pena vederla in atto:

| Concetto | Chi lo possiede | Dove sta la base |
|---|---|---|
| `Option` (configurazione per anno e categoria) | **Ptv** — `BaseOption`, `BaseOptionResource` | `Modules/Ptv` |
| `Rating` (dare un punteggio) | **Rating** — `BaseRating`, `BaseRatingsTable` | `Modules/Rating` |
| `Scheda` (valutare una persona) | **Ptv** | `Modules/Ptv` |

Mettere `BaseRatingFactory` in Ptv sarebbe stato comodo e sbagliato: Ptv e' la
piattaforma del dominio *scheda*, non il magazzino di tutto cio' che e' condiviso.
**Una base nel posto sbagliato costringe chi non c'entra a dipendere da chi non gli
serve.**

Il test per trovare il proprietario e' meccanico: **dove sta `Base<Concetto>` (il
modello)?** Li' stanno anche Resource, Table, Form, Infolist, Pages e Factory di base.

## Il corredo completo

| Artefatto | Base (modulo proprietario) | Leaf (modulo che lo usa) |
|---|---|---|
| Modello | `Base<X>` — colonne, cast, relazioni | `protected $connection` |
| Resource | `Base<X>Resource` | `protected static $model` |
| Table | `Base<X>sTable` | *(guscio vuoto)* |
| Form | `Base<X>Form` | *(guscio vuoto)* |
| Infolist | `Base<X>Infolist` | *(guscio vuoto)* |
| Pages | `BaseList<X>s`, `BaseCreate<X>`, `BaseEdit<X>` | `protected static $resource` |
| Factory | `Base<X>Factory` | `protected $model` |

Un leaf di tre righe non e' un file povero: e' un file **onesto**. Dice l'unica cosa che
sa e che la base non puo' sapere.

## I tre perche'

### 1. La connection — il piu' pericoloso

Ogni leaf vive su un database diverso. Se una classe base nomina un modello **concreto**,
tutto il traffico finisce su **quel** database, comunque si sia arrivati li'.

Misurato il 2026-09-02: `Ptv\...\OptionResource\Tables\OptionsTable` e la copia in
`Pages\BaseListOptions` costruivano la tendina dei possibili padri con
`Modules\Performance\Models\Option::where(...)`. Aprendo le opzioni di **qualunque**
modulo, i padri proposti venivano dal database di Performance.

Nessuna eccezione. Nessun log. Solo un elenco che viene da un'altra anagrafica.

```php
// no: la base conosce una foglia, e legge sempre quel database
$query = \Modules\Performance\Models\Option::where('year', $year);

// si: la base non sa quale foglia sta servendo
$query = $record->newQuery()->where('year', $year);
```

### 2. La direzione delle dipendenze

Le foglie conoscono la base; **la base non conosce le foglie**. Un
`use Modules\<Verticale>\...` dentro il modulo proprietario non e' una scorciatoia: e'
un ciclo.

Non e' teoria: **14 file di `Modules/Ptv` importano `Modules\Performance`** (misura del
2026-09-02). Due chiusi con la story 01.36, dodici sono debito dichiarato.

E' la stessa regola che vale un piano sotto fra Sigma e Ptv — *Ptv puo' dipendere da
Sigma, Sigma mai da Ptv.* Cambiano i nomi, non la forma.

### 3. La deriva silenziosa

Prima dell'estrazione delle basi:

| Concetto | Owner | Copie di `<X>Factory` | Con `definition()` vuota |
|---|---|---:|---:|
| `CategoriaPropro` | Ptv | 6 | 4 |
| `Rating` | Rating | **6** | **6** |
| `StabiDirigente` | Ptv | 5 | 4 |
| `CriteriEsclusione` | Ptv | 5 | 4 |
| `CriteriOption` | Ptv | 5 | 4 |
| `Message` | Ptv | 4 | **4** |
| `MyLog` | Ptv | 4 | **4** |
| `Option` | Ptv | 3 | 2 |
| `Valutatore` | Ptv | 2 | **2** |
| `Extra` | Xot | 2 | **2** |
| `Assenza`, `CriteriPrecedenza`, `Scheda` | Ptv | 2 ciascuno | 1 ciascuno |

Su ~50 factory riconducibili a una base, **circa 35 non definivano niente.**

### Il campo che rende il danno sistematico: `anno`

Quasi ogni concetto di questo dominio porta `anno` — `CategoriaPropro`,
`CriteriEsclusione`, `CriteriOption`, `Message`, `StabiDirigente`, `Valutatore`,
`Assenza`, `CriteriPrecedenza` — e **ogni query di dominio filtra su quel campo**.

Una `definition()` vuota lascia `anno` a `null`. Il record esiste, il test passa, e non
compare in **nessuna** schermata: non e' un caso particolare, e' la regola.

Una definition vuota **non e' neutra**: `factory()->create()` scrive una riga con tutti i
campi a `null`. Per `Option` quella riga non compare mai fra i padri selezionabili —
la query filtra su `year`, `option_type`, `name` e scarta `value` vuoto. Per `Rating`,
un record senza `title` non e' mostrabile e senza `rule` non valida nulla.

Il test passa, il dato e' inutile, nessuno se ne accorge.

**Tre copie di una cosa non sono tre cose: sono una cosa e due bug in attesa.**

## Lo scopo: rendere il costo di un modulo nuovo pari a zero

Il valore della base non e' non ripetersi. E' che **aggiungere un modulo che usa il
concetto costa otto file da una riga, e nessuna decisione**.

Chi aggiunge il settimo modulo che usa `Rating` non deve sapere che `slug` si genera da
`title`, ne' quali `rule` sono ammesse. Deve sapere una cosa sola: la propria connection.

**Una base ben fatta si misura da quanto poco deve sapere chi la usa.**

## La politica: dove finisce una riga di codice

Prima di scrivere qualcosa in un leaf, una domanda sola:

> *Questa riga sarebbe identica in un altro modulo?*

- **Si** → sta nella base. Sempre. Anche se oggi il modulo e' uno solo.
- **No** → sta nel leaf, e va spiegato **perche'** e' diverso.

Il secondo caso e' raro, ed e' bene che lo sia. Un leaf che cresce e' il sintomo che la
base ha sbagliato confine — non che il modulo e' speciale.

## Il debito: una base che fornisce un default che solo il leaf puo' sapere

`Ptv\Filament\Resources\BaseOptionResource` e' `abstract` ma dichiara
`protected static ?string $model = Ptv\Models\Option::class`.

Un figlio che dimentica di sovrascrivere `$model` legge la tabella di un altro modulo,
sulla connection di un altro modulo, **senza errore e senza log**. Il default rende
l'omissione invisibile: e' esattamente cio' che una classe astratta dovrebbe impedire.

`BaseOptionFactory` e `BaseRatingFactory`, scritte dopo, **non** dichiarano `$model`
apposta: se il leaf lo dimentica, l'errore e' immediato invece che silenzioso.

> **Una base astratta non fornisce default per cio' che solo il leaf puo' sapere.**
> Un default comodo che nasconde una dimenticanza non e' una comodita': e' una trappola
> con il timer.

## Lo zen

> Il file migliore che puoi scrivere e' quello che non contiene decisioni.
>
> Un leaf di tre righe non e' poco lavoro: e' tutto il lavoro fatto altrove, una volta
> sola, dove poteva essere fatto bene.
>
> Se aggiungere un modulo richiede di capire, il confine e' nel posto sbagliato.
>
> La base non sta dove e' comodo metterla: sta dove il concetto abita.

## Come verificarlo (il lavoro che resta)

Le regole scritte non trattengono nulla. Guardie proposte, in ordine di valore:

1. **Nessuna base nomina una foglia** — test che fallisce se il modulo proprietario
   importa un verticale. Oggi fallirebbe su 14 file in Ptv: si parte con una baseline a
   cricchetto, come per `CaseCollisionsDoNotGrowTest`.
2. **Ogni leaf dichiara la sua connection** — per ogni figlio di un `Base<X>`, verificare
   `$connection` valorizzata e diversa da quella del padre.
3. **Nessuna `definition()` vuota** — una factory che restituisce `[]` e' quasi sempre
   uno scheletro dimenticato. Sarebbe scattata 8 volte su 9 prima di oggi.
4. **Parita' del corredo** — per ogni `Base*`, i leaf hanno tutti i pezzi. Un pezzo
   mancante non da' errore finche' qualcuno non apre quella pagina.

## Collegamenti

- `Modules/Ptv/docs/base-leaf-pattern.md` — il caso `Option`, in dettaglio
- `Modules/Rating/docs/purpose.md` — perche' Rating possiede il concetto punteggio
- `Modules/IndennitaResponsabilita/docs/stories/01.36.option-corredo-e-base-ptv.story.md`
- `docs/wiki/rules/sigma-ptv-dependency-direction.md` — la stessa regola un piano sotto
