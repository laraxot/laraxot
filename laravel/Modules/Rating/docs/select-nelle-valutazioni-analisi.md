# Un criterio di valutazione con risposta a scelta: analisi

**Stato: brainstorming.** Nessun codice scritto, nessuna decisione presa. Serve a
mettere le strade sul tavolo con i numeri veri, non a chiuderne una.

## La domanda posta

Fra le valutazioni serve anche un **select**. Le due strade proposte:

1. aggiungere al rating un campo `data` di tipo JSON con i valori del select;
2. usare padre/figlio — `parent_id` c'è già — e far diventare i figli le opzioni.

## La domanda si sdoppia, ed è il punto

Sono **due** domande, e vengono confuse perché di solito si risolvono insieme:

| | |
|---|---|
| **dove vivono le opzioni** | la lista di scelte offerte: «Base / Media / Alta» |
| **dove vive la risposta** | cosa ha scelto il valutatore per *questa* scheda |

La seconda è la più vincolante, e oggi ha già una risposta: la risposta vive in
`rating_morph.value`, che è **`decimal(10,3)`**. Numerico.

Da cui il vincolo che filtra tutte le strade:

> Un select le cui opzioni hanno un **peso numerico** si salva senza toccare niente.
> Un select di **etichette senza numero** non ha oggi un posto dove andare.

Se «Alta» vale 25 e «Base» vale 5, la scelta *è già* un valore: si continua a
scrivere `value`, e la lista serve solo a **guidare l'inserimento** invece di lasciare
un campo numerico libero. Se invece le opzioni sono etichette non ordinabili — «Ufficio
A / Ufficio B» — allora serve anche una colonna nuova sul pivot, e questa è una
decisione separata da dove mettere le opzioni.

**Prima domanda da girare a chi conosce il dominio: le opzioni hanno un punteggio?**
Da lì dipende metà del resto.

## Cosa c'è già, misurato

| | |
|---|---|
| `ratings.rule` (`RuleEnum`) | non è un tipo di widget, è una **stringa di validazione**: `numeric\|min:0\|max:5`, `min:0\|max:25\|not_in:1,2,3` |
| `ratings.extra_attributes` | colonna schemaless Spatie, **già presente** |
| `ratings.parent_id` + `HasRecursiveRelationships` | attivi, ma **0 righe** con padre: 37 criteri, 37 radici |
| `rating_morph.value` | **tipo divergente fra installazioni**: `int(11)` in IR e Progressioni, `decimal(10,3)` in Ptv. Più `note` (text), `percentage`, `is_winner`, `reward` |
| tabella `options` (`Ptv\BaseOption`) | esiste: `option_type`/`option_id` (morph), `name`, `value`, `year`, `txt`, `txt1`, `parent_id`, `pos` — e usa già l'adjacency list |
| dati in `options` | 3 righe in `indennita_responsabilita`, `option_type` **NULL**: usata come lista piatta globale, il morph non è mai stato sfruttato |
| `criteri_options` (`CriteriOption`) | **non c'entra**: è la configurazione della campagna per anno (`min_gg_asz_tip_cod_escluso_subito`, `no_posiz`, `data_presenza_al`, `lista_posiz_ruolo`), letta dai criteri di **esclusione**. Vedi sotto |
| `criteri_esclusione` | **non c'entra**: le regole di chi resta fuori (`field_name`, `op`, `value`, per anno) |

Due cose da notare prima di scegliere. `rule` dice *come si valida* un criterio, non
*come si presenta*: qualunque strada si prenda, «questo criterio è un select» è
un'informazione **nuova**, che oggi non c'è. E l'unica entità opzione riutilizzabile
del progetto è **una sola**, `options`: una forma nuova va giustificata contro quella.

### La trappola della parola «criterio»

Nel progetto «criterio» significa **due cose diverse**, in due fasi diverse:

| | |
|---|---|
| **criterio di esclusione** | *chi partecipa*. `criteri_esclusione` + `criteri_options`, chiave `anno`, di piattaforma (Ptv) |
| **criterio di valutazione** | *come si valuta chi partecipa*. `ratings` + `rating_morph`, chiave il criterio stesso, del modulo Rating |

Sono domini separati: fasi diverse, proprietari diversi, chiavi diverse. `criteri_options`
non tiene le opzioni di una valutazione, tiene i **parametri della campagna** — misurato
sui dati: `min_gg_asz_tip_cod_escluso_subito = 730`, `lista_posiz_tempo_determinato = "2,21"`,
`data_presenza_al = 2024-12-31`. Il suo `type` (`list|int|date`) serve a trasformare
`"2,21"` in una lista: descrive **quei** parametri, non un widget.

Una prima versione di questo documento le proponeva come casa delle opzioni di
valutazione. È sbagliato, ed è sbagliato per il motivo più comune: **il nome
condiviso**. Cercando «criteri» si trovano tabelle dell'altro dominio, e sembrano
pertinenti perché si chiamano come la cosa che si sta cercando.

## Le strade

### A. Campo JSON `data` sul rating

Le opzioni in una colonna JSON del criterio: `[{"label":"Base","value":5}, …]`.

**Pro** — una migrazione additiva, nessuna entità nuova, le opzioni viaggiano con il
criterio (esporti il criterio, esporti le sue opzioni).

**Contro** — le opzioni smettono di essere **dati interrogabili**: non si può chiedere
«quali criteri offrono l'opzione Alta», né ordinarle, né tradurle, né disattivarne una
lasciando storia di chi l'aveva scelta. E un JSON non ha vincoli: la prima etichetta
scritta a mano in modo diverso crea un doppione che nessuna query vede.

**Il difetto vero**: se le opzioni cambiano nel tempo — e in un sistema per anno
cambiano — il JSON descrive **solo l'oggi**. Una scheda del 2024 valutata con opzioni
poi modificate non è più interpretabile, perché la lista che l'utente vide non esiste
più da nessuna parte.

### B. `extra_attributes` invece di una colonna nuova

Identica ad A nella sostanza, ma senza migrazione: la colonna schemaless c'è già.

**Pro** — costo zero, reversibile.

**Contro** — gli stessi di A, più uno: `extra_attributes` è già usata per altro
(`type`, `anno` compaiono nei form), quindi diventerebbe un contenitore di cose non
omogenee. Va bene per **provare**, non per restare.

### C. Padre/figlio: i figli sono le opzioni

Il criterio è il padre, ogni opzione è un `rating` figlio. La scelta si registra come
riga `rating_morph` sul **figlio**.

**Pro** — nessuna entità nuova, l'infrastruttura è già in piedi (`parent_id`, trait,
RelationManager dei figli), le opzioni sono righe vere: ordinabili con `order_column`,
disattivabili con `is_disabled`, e il **peso** è `value` sul pivot. La risposta non
richiede colonne nuove.

**Contro, e sono due seri.**

1. **Un'opzione non è un criterio.** Diventerebbero la stessa entità, e tutto ciò che
   conta i criteri comincerebbe a contare anche le opzioni. Conseguenza concreta e
   già scritta: la `RatingsColumn` mostra `children_count` come «sotto-criteri» —
   con questa strada quel numero diventerebbe «numero di opzioni», e la stessa
   colonna direbbe due cose diverse a seconda del criterio. Servirebbe un
   discriminante (`is_option`, o un `rule` dedicato), ed è quello il vero costo.
2. **L'albero perde un significato solo.** Oggi `children` può voler dire
   «sotto-criterio» *oppure* «opzione», e `descendants()` mescolerebbe i due. Il
   ricorsivo, appena abilitato, diventerebbe ambiguo.

### D. La tabella `options` esistente, usata come morph

`options` ha già tutto: `option_type`/`option_id` per puntare al criterio, `name` e
`value` per etichetta e peso, `pos` per l'ordine, `parent_id` per opzioni annidate, e
soprattutto **`year`**.

**Pro** — nessuna entità nuova *e* nessuna ambiguità: un'opzione è un'opzione, un
criterio è un criterio. `year` risolve il problema che affonda A e B: le opzioni del
2024 restano, quelle del 2026 sono altre righe, e una scheda vecchia resta leggibile.
L'infrastruttura morph esiste già, semplicemente non è mai stata usata.

E — verificato — **sta sulla connessione del modulo**, non su `ptv`: la tabella esiste
in `indennita_responsabilita`, in `performance` e in `ptv_lara`, e in IR `options` e
`ratings` sono **nello stesso database**. Non c'è nessuna questione cross-database:
una prima versione di questo documento diceva il contrario, ed era la stessa
supposizione non misurata che aveva portato a preferire `criteri_options`.

**Contro** — due, entrambi concreti:

1. `option_type` è NULL su tutte e 3 le righe presenti: nessuno ha ancora usato il
   morph, quindi è terreno da inaugurare;
2. **i tipi non combaciano**: `options.option_id` è `int(10) unsigned`, `ratings.id`
   è `bigint(20) unsigned`. Oggi non rompe niente perché i valori sono piccoli, ma è
   una chiave esterna più stretta della chiave che punta — lo stesso difetto già
   trovato su `rating_morph.user_id`. Si allarga adesso o si eredita.

### E. `criteri_options` — strada ritirata

Era la raccomandazione della prima versione di questo documento. **Non regge**, e vale
la pena tenerne traccia invece di cancellarla, perché l'errore è istruttivo.

Sembrava giusta per tre motivi, tutti veri e tutti irrilevanti: la tabella esiste, sta
sulla connessione del modulo, ed è letta come `etichetta => valore`
(`getCriteriOptionsParsedByYear` → `pluck('value_real','name')`). Ma è la forma ad
assomigliare, non il contenuto: quelle righe sono **parametri della campagna annuale**
letti dai criteri di esclusione, non scelte offerte a un valutatore. Metterci dentro
le opzioni di un rating significherebbe mescolare due domini in una tabella sola, e la
prima query che chiede «tutti i parametri del 2026» se le troverebbe in mezzo.

Il segnale che avrebbe dovuto fermarmi c'era: **0 righe in IR**. Una tabella «che esiste
già per questo» e che nessuno ha mai riempito in quel modulo non è una tabella per
quello.

### F. Enum PHP

Se le opzioni sono **poche e non cambiano**, non sono dati: sono codice. Un enum le
rende tipizzate, traducibili e verificabili dall'analisi statica, e il progetto ha già
la convenzione (`->options(MioEnum::class)`).

**Pro** — la forma più solida di tutte, zero query, zero migrazioni.

**Contro** — se cambiano per anno o le configura un utente, è la strada sbagliata:
ogni modifica diventa un rilascio.

**Domanda che la decide**: le opzioni le decide uno sviluppatore o le configura un
amministratore dalla UI?

### G. Il problema ortogonale: «questo criterio è un select»

Qualunque strada si scelga, resta da dire **come si presenta** un criterio. Oggi
`rule` porta una stringa di validazione, non un tipo. Le forme possibili: estendere
`RuleEnum` con casi che implicano il widget, oppure aggiungere `input_type`, oppure
dedurlo dalla presenza di opzioni («se ha opzioni è un select»).

L'ultima è la più economica e la più fragile: un criterio a cui vengono tolte tutte
le opzioni tornerebbe silenziosamente a campo numerico.

## Come si scelgono, in ordine

Tre domande di dominio, e ognuna ne elimina almeno una:

1. **Le opzioni hanno un peso numerico?**
   Se sì, `rating_morph.value` basta e non servono colonne nuove.
   Se no, serve comunque una colonna per la risposta, **indipendentemente** da dove
   stanno le opzioni.
2. **Le opzioni cambiano per anno?**
   Se sì, cadono A e B — un JSON non ha versioni — e resta in piedi D, che `year` ce
   l'ha già.
3. **Chi le configura?**
   Uno sviluppatore → F (enum). Un amministratore → serve una UI, e allora sono dati.

## Cosa direi io, e perché

**La strada è D: `options` come morph del criterio.** Non per eleganza: perché ha già
la forma esatta del problema — `option_type`/`option_id` per appartenere a un criterio,
`name` e `value` per etichetta e peso, `pos` per l'ordine, `year` per la versione,
`parent_id` per opzioni annidate — e sta sulla stessa connessione dei rating. È
un'infrastruttura montata e mai accesa.

Ordine di preferenza e motivo, in una riga ciascuno:

| | strada | perché sì / perché no |
|---|---|---|
| 1 | **D — `options` morph** | ha già tutto: appartenenza, etichetta, peso, ordine, anno; stesso database dei rating. Costa: inaugurare il morph e allargare `option_id` |
| 2 | **F — enum** | la più solida, **se** le opzioni non cambiano e non le configura un utente |
| 3 | **B — `extra_attributes`** | solo come prototipo, sapendo che i dati andranno migrati |
| 4 | **A — colonna JSON** | come B ma con una migrazione in più, e senza nessun vantaggio in cambio |
| 5 | **C — figli come opzioni** | l'unica che **peggiora** qualcosa: rende ambiguo un albero appena nato pulito |
| — | ~~E — `criteri_options`~~ | ritirata: altro dominio, vedi sopra |

**Su C, che è la strada proposta, il no è motivato.** È attraente perché
l'infrastruttura è stata montata oggi, ma paga con un'ambiguità permanente: `children`
vorrebbe dire «sotto-criterio» *oppure* «opzione», e `descendants()` li mescolerebbe.
Conseguenza già scritta e verificabile: `RatingsColumn` mostra `children_count` come
sotto-criteri, e su un criterio-con-opzioni quel numero direbbe un'altra cosa. Servirebbe
un flag discriminante, e quel flag è il costo vero della strada — pagato per non usare
una tabella che c'è già.

L'albero oggi è **37 radici, zero figli**: è ancora tutto da usare per la gerarchia
vera. Riempirlo di opzioni prima significa non poterle più distinguere.

Nota su D e C insieme: `options` ha **anch'essa** `parent_id`. Se un giorno servissero
opzioni annidate, l'albero è di là — dove annidare opzioni è l'unico significato
possibile, e quindi non è ambiguo.

## Domande aperte

- Le opzioni hanno un punteggio, o sono etichette?
- Cambiano per anno? Se sì, `year` su `options` risponde già.
- Le configura un amministratore o sono fisse nel codice?
- Le opzioni esistono già da qualche parte (un file, un foglio, una tabella legacy) o
  vanno inserite da zero? `options` in IR ha 3 righe con `option_type` NULL: vanno
  capite prima di scrivere accanto.
- `options.option_id` è `int` mentre `ratings.id` è `bigint`: si allarga la colonna
  ora o si accetta il restringimento?

## Riferimenti

- `laravel/Modules/Rating/docs/stories/rating-gerarchia-parent-id-e-figli-in-edit.story.md`
- `laravel/Modules/Rating/docs/stories/ratings-column-section-filter.story.md`
- `laravel/Modules/Rating/docs/stories/18.35.rating-morph-type-doppia-forma.story.md`
