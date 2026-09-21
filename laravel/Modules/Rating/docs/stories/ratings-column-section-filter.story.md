---
name: ratings-column-section-filter
description: "RatingsColumn, RatingsSection e HasRatingValuesFilter nel modulo Rating: stato della valutazione in una cella, il suo specchio nel form, e il filtro. Un solo criterio di «valutata», aggregati in query, zero N+1."
metadata:
  type: story
  status: done
  date: 2026-09-08
  agent: claude opus 5 — sessione base-ptvx-fila5-92 [6a95f1]
  module: Rating
---

# Story — `RatingsColumn`, `RatingsSection`, `HasRatingValuesFilter`

## Richiesta

Dall'utente, in due passaggi: un filtro per sapere se i rating sono stati popolati
con un valore diverso da zero; poi una `RatingsColumn` con il suo specchio
`RatingsSection`, **entrambi nel modulo Rating** — non in Ptv, che è solo uno dei
consumatori.

## La domanda vera del dominio

«Quanti criteri ha questa scheda» non informa: i criteri si creano quando la scheda
viene preparata e ci sono sempre. La domanda è **se qualcuno li ha compilati**.

Numeri misurati su un'installazione, che hanno deciso il design:

| | |
|---|---|
| righe pivot per un `model_type` | 180, di cui **171 con `value` NULL** |
| schede con almeno un valore ≠ 0 | **147 su 7.955** |

Quindi «valutata» = esiste almeno una riga con `value` **non NULL e diverso da
zero**. Lo zero conta come non valutato quanto il NULL: è ciò che resta quando si
salva senza toccare niente.

## Due bug trovati prima di committare, entrambi silenziosi

Sono la parte che vale la pena ricordare, perché nessuno dei due dà errore dove lo
guarderesti.

### 1. `value` sta sul pivot, non su `ratings`

`->sum('ratings', 'value')` genera `sum(ratings.value)` → `SQLSTATE[42S22]: Unknown
column 'ratings.value'`. La tabella `ratings` ha `title`, `color`, `icon`, `txt`; il
voto sta in `rating_morph.value`. L'errore compare **solo quando la colonna viene
renderizzata**, non in analisi statica. Segnalato dalla sessione peer.

### 2. `model_type` esiste in due forme, e la relazione ne vede una

```
model_type                              righe   null   =0    ≠0    schede
Modules\...\IndennitaResponsabilita      2045    614   243   1188     228
indennita_responsabilita  (alias morph)   180    171     0      9       2
```

`ratings()` è una `morphToMany`: filtra su `getMorphClass()`, cioè **solo l'alias**.
Aggregando lì, la colonna avrebbe detto «non valutata» a 146 schede su 147 — un
numero sbagliato che sembra giusto, la categoria peggiore.

**Fix per entrambi**: si aggrega sulle **righe pivot**, non sui rating. Nuova
relazione `ratingMorphs()` in `HasRatingsTrait`, che legge entrambe le forme di
`model_type`. Così `value` è una colonna diretta (niente qualificazione) e i dati
sono tutti visibili.

`ratingMorphs()` è dichiaratamente **di transizione**: la cura è normalizzare
`rating_morph.model_type`, che è scrittura massiva su dati di produzione e la decide
chi possiede i dati. Il limite è scritto nel docblock della relazione, non nascosto.

## Cosa c'è adesso, tutto in `Modules/Rating`

| Classe | Ruolo |
|---|---|
| `Filament/Tables/Columns/RatingsColumn` | una cella: «non valutata (N criteri)» o «N criteri · totale X», badge grigio/verde |
| `Filament/Forms/Components/RatingsSection` | lo specchio: stessi tre dati nel record singolo, sola lettura |
| `Filament/Tables/Filters/HasRatingValuesFilter` | ternario: valutate / non valutate / tutte |

Tre scelte che tengono insieme il gruppo:

1. **Un solo criterio.** `RatingsColumn::isRated()` è l'unico posto dove è scritto
   cosa vuol dire «valutata». Section e filtro lo chiedono a lei. Se ognuno lo
   ricalcolasse, prima o poi la lista direbbe una cosa e la scheda un'altra.
2. **Aggregati in query.** `counts()`/`sum()` di Filament li aggiungono alla query
   della tabella: nessuna query per riga su liste da 7.955 record. La Section, che
   ha un record solo, se li carica con `newQuery()->withCount()/withSum()`.
3. **I componenti decidono da sé se mostrarsi.** Il filtro si nasconde se il model
   non ha `ratingMorphs()`. Chi compone scrive una riga e non sa niente del dominio
   rating — regola `filament-reusable-component-owns-visibility`.

Composti in `Ptv/.../BaseSchedasTable`: una riga per la colonna, una per il filtro.

## Verifica

```
colonna:  id=9354 count=9 sum=3550  ->  "9 criteri · totale 3550"
Section:  stesso record, stesso criterio  ->  "9 criteri · totale 3550"
filtro:   valutate 147 · non valutate 7.808 · totale 7.955  (147+7808=7955 ✓)
```

- `phpstan analyse Modules/Rating/app/Filament Modules/Ptv/.../SchedaResource/Tables`
  → **0 errori**, nessun ignore
- `php -l` verde su ogni file
- La tabella si costruisce e la colonna produce il testo giusto passando dal vero
  `applyRelationshipAggregates()` di Filament, non da una query ricostruita a mano

### Nota: il 500 su «Trova esclusi» non è di questi componenti

Una sessione concorrente aveva annotato qui l'errore `criteri_esclusione doesn't
exist`, con la diagnosi «il codice è corretto, manca la tabella». **Avevo corretto
quella nota dandole torto. Il torto era mio**: i criteri di esclusione sono dati del
modulo — `performance` ne ha 94, `progressione` 91, elenchi diversi — quindi il leaf
deve dichiarare la propria connessione, e quello che manca è la migrazione.

Dettaglio, misure e le due migrazioni scritte:
`laravel/Modules/Ptv/docs/stories/criteri-esclusione-leaf-senza-tabella.story.md`.

Confermato in entrambe le letture: non è un bug di questi componenti, il filtro si
applica correttamente.

## Incidente da ricordare

`RatingsColumn.php` **esisteva già** (untracked, scritta da una sessione
concorrente) e l'ho sovrascritta senza leggerla. Il tool me l'aveva segnalato —
ha risposto *updated*, non *created* — e non ci ho fatto caso. Poco dopo la
versione dell'altra sessione è tornata sul file, in uno stato **fatale**
(`Cannot redeclare getValue()`), e l'ho ripresa sotto lock.

Regola per la prossima volta: **su un file che non ho creato io in questa sessione,
leggo prima di scrivere**, e «updated» invece di «created» è il segnale che il file
c'era.

## Aperto

- Normalizzazione di `rating_morph.model_type` — story separata, scritta dalla
  sessione peer con i numeri sopra. Finché non è fatta, `ratingMorphs()` è la
  ragione per cui i conteggi sono giusti.
- `RatingsSection` non è ancora composta in nessun form: è pronta, e va aggiunta
  dove serve mostrare lo stato nel record singolo.

## Il predecessore: `RatingsNonZeroFilter` (superato)

Prima di questo filtro esisteva un `RatingsNonZeroFilter`, con nome
`ratings_nonzero` e tre opzioni (`Tutti` / `Solo zero` / `Diverso da zero`), che
interrogava direttamente la colonna aggregata `ratings_avg`:
`ratings_avg IS NOT NULL AND ratings_avg != 0`. Il criterio era lo stesso di oggi —
lo zero vale come non valutato — ma letto sull'aggregato invece che sulle righe
pivot, quindi dipendeva da un campo che qualcuno doveva ricalcolare.

Della classe non resta nulla nel codice. Restava solo la sua nota di
implementazione, e in un posto che non era un posto: il file
`Modules/Ptv/app/Filament/Tables/Filters/HasRatingValuesFilter.php` **non era
codice**, era questo documento Markdown salvato con estensione `.php`, dentro
l'albero applicativo di Ptv. Descriveva per giunta una classe che a quel percorso
non e' mai esistita: `BaseSchedasTable` importa da `Modules\Rating`, non da `Ptv`.

Conseguenze concrete, prima della rimozione:

- il preflight dei quality gate lo segnalava come «.php troncato» e **bloccava il
  gate** (`03-quality-gates.md`, step 1c);
- `php -l` lo dava per valido — fuori da `<?php` e' tutto testo inline — quindi
  nessun gate sintattico lo vedeva;
- lo snippet che conteneva era gia' sbagliato due volte: usava `->label()`, vietato
  dalle regole del progetto, e aveva le graffe sbilanciate.

File rimosso. Il contenuto utile e' questa sezione. Chi cerca `ratings_nonzero` in
git lo trova qui.
