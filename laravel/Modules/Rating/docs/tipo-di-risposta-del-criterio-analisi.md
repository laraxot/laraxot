# Che tipo di risposta vuole un criterio: analisi

**Stato: brainstorming.** Nessun codice, nessuna decisione. Segue e completa
[`select-nelle-valutazioni-analisi.md`](select-nelle-valutazioni-analisi.md), che
si fermava a questa domanda chiamandola «il problema ortogonale».

## La domanda posta

Serve un campo che dica se un criterio si compila come **intero**, **decimal**,
**select**, **radio** o **testo libero** — e, legato al select, la regola: se scegli
«Altro», il testo diventa obbligatorio.

## Prima cosa: quale «criterio»

Nel progetto la parola «criterio» copre due domini distinti, e confonderli è
l'errore più facile da fare qui:

| | *chi partecipa* | *come si valuta chi partecipa* |
|---|---|---|
| tabelle | `criteri_esclusione`, `criteri_options` | `ratings`, `rating_morph` |
| chiave | `anno` (la campagna) | il criterio stesso |
| proprietario | piattaforma Ptv | modulo Rating |
| esempio di riga | `min_gg_asz_tip_cod_escluso_subito = 730` | «Ruolo», `rule = numeric\|min:0\|max:5` |

`criteri_options` **non tiene opzioni di valutazione**: tiene i parametri della
campagna annuale letti dai criteri di esclusione — misurato:
`lista_posiz_tempo_determinato = "2,21"`, `data_presenza_al = 2024-12-31`. Il suo
`type` (`list|int|date`) dice come trasformare `"2,21"` in una lista. È un'altra
domanda, in un'altra fase, con un altro proprietario.

**Quindi il nome `type` non è occupato in questo dominio**, e il campo può chiamarsi
come conviene. Resta una sola ragione per preferire un nome più preciso — `input_type`,
`answer_type` — ed è che `type` da solo non dice *quale* asse descrive: come si legge
un valore o come si inserisce una risposta. Ma è una preferenza, non un vincolo.

## Seconda cosa: metà dell'elenco non sono tipi diversi

L'elenco proposto — intero, decimal, select, radio, testo — mescola tre livelli.

### `intero` e `decimal` sono lo stesso tipo con precisione diversa

La risposta va in `rating_morph.value`, che è `decimal(10,3)`. Un intero è un decimale
con zero cifre. Trattarli come due tipi significa avere due rami che fanno la stessa
cosa e possono divergere; trattarli come **un tipo numerico più una precisione**
(`decimals: 0` = intero) è una descrizione più vera e con meno codice.

C'è anche un fatto che li lega: `RuleEnum` porta già la validazione numerica
(`numeric|min:0|max:5`, `min:0|max:25|not_in:1,2,3`). La differenza intero/decimale è
**già esprimibile lì**, in una colonna che esiste.

### `select` e `radio` sono lo stesso tipo con presentazione diversa

Stessa forma del dato — una scelta fra N — stessa validazione, stesso posto dove
finisce la risposta. Cambia solo come appare: il radio mostra tutte le opzioni
insieme, il select le nasconde in un menu.

È una scelta di **UI**, e in genere si decide da sé: sotto le 5 opzioni il radio è più
comodo, sopra diventa un muro. Se resta un dato, che sia una preferenza separata
(`inline: true`), non un tipo a sé — altrimenti cambiare la resa di un criterio
significa cambiarne il *tipo*, e ogni codice che ragiona sul tipo va toccato.

**Restano quindi tre tipi veri**: `numerico`, `scelta`, `testo`. Più due modificatori:
precisione e resa.

### `testo libero` è l'unico che non ha dove andare

E qui c'è il vincolo duro, misurato:

| colonna del pivot | tipo |
|---|---|
| `value` | `int(11)` in IR e Progressioni, `decimal(10,3)` in Ptv — **divergono** |
| `note` | `text` |
| `percentage` | `decimal(10,3)` |
| `is_winner` | `boolean` |
| `reward` | `decimal(10,3)` |

Una risposta testuale in `value` non ci sta. Le strade sono due:

1. **riusare `note`** — esiste, è testo, costo zero. Ma `note` oggi significa
   «annotazione facoltativa del valutatore»: usarla come risposta le dà due
   significati, e da quel momento nessuna query sa distinguere un commento da un dato.
   È lo stesso errore di `type`, un livello più in là;
2. **una colonna nuova** sul pivot (`answer_text`) — additiva, e tiene separate due
   cose che sono diverse.

La seconda costa una migrazione e non costa ambiguità. Con `rating_morph` che oggi ha
2.298 righe in una sola installazione e zero nelle altre due, aggiungere una colonna
nullable è a rischio basso — ed è **la finestra più economica**, la stessa
osservazione già fatta per `user_id`.

## Dove mettere il campo: le strade

### A. Nuova colonna `input_type` sul rating, con enum PHP

**Pro** — esplicita, tipizzata, validabile dall'analisi statica, e il progetto ha già
la convenzione `->options(MioEnum::class)`. I casi sono pochi e li decide chi
sviluppa, non un amministratore: è esattamente il caso in cui un enum batte una
tabella.

**Contro** — una colonna in più su `ratings`, e soprattutto: **si affianca a `rule`,
che descrive la stessa cosa da un altro lato**. Vedi sotto, è il problema vero.

### B. Estendere `RuleEnum`

`rule` c'è già, è per criterio, e la validazione **implica** il tipo: `numeric|min:0`
dice che è numerico.

**Pro** — nessuna colonna nuova, nessuna doppia sorgente.

**Contro** — `RuleEnum` oggi contiene stringhe di validazione Laravel, non nomi di
widget. Metterci `select` significa cambiarne la natura: un valore che non è una regola
di validazione dentro un enum di regole di validazione. E le combinazioni esplodono:
serve un caso per ogni incrocio tipo × range.

### C. Derivarlo: «se ha opzioni è un select»

**Pro** — zero campi.

**Contro** — fragile in modo silenzioso: un criterio a cui si tolgono tutte le opzioni
torna campo numerico senza che nessuno l'abbia deciso. E non distingue comunque
numerico da testo.

### D. `extra_attributes`

**Pro** — costo zero, la colonna schemaless c'è.

**Contro** — invisibile a chi legge lo schema, non validabile, e già usata per altro.
Va bene per un prototipo, non per un campo che decide come si compila mezzo sistema.

## Il problema vero: due campi che descrivono la stessa cosa

`input_type` e `rule` possono **contraddirsi**: `input_type = testo` con
`rule = numeric|min:0|max:5`. Nessuno dei due è sbagliato da solo; insieme non hanno
senso, e nulla oggi lo impedirebbe.

È la forma di errore che questo repo ha già pagato più volte: `txt` dichiarato `text`
in creazione e `string` in update, `parent()` scritto a mano sopra quello del trait,
`model_type` in due forme. **Ogni volta che la stessa informazione ha due sorgenti,
prima o poi divergono, e il sintomo compare lontano da dove è nato il problema.**

Tre modi di evitarlo, in ordine di solidità:

1. **una sorgente sola**: il tipo *deriva* la validazione. `input_type = numerico,
   min 0, max 25` genera la stringa di regole; `rule` smette di essere scritta a mano.
   È la più pulita e la più invasiva, perché tocca i criteri esistenti;
2. **due campi ma un invariante verificato**: restano entrambi, e un test garantisce
   che concordino. Costo basso, e il progetto ha già questa forma di guardia;
3. **due campi e speranza**: quello che succede se non si sceglie fra 1 e 2.

Qualunque strada si prenda per il campo, **questo va deciso insieme**, non dopo.

## «Se scegli Altro, il testo è obbligatorio»

È una regola condizionale fra due campi, ed è la parte più interessante perché decide
se le opzioni sono davvero dati.

### Dove vive la condizione

- **Nei dati**: un flag sull'opzione — «questa opzione richiede un testo». Chi
  configura le opzioni decide anche quali aprono il campo libero, senza rilasci.
  Vive sulla riga dell'opzione, quindi **si aggancia alla scelta del documento
  precedente**: se le opzioni finiscono in `options` come morph del criterio, il flag
  sta lì — e quella tabella ha già `txt` e `txt1` liberi, da capire prima di
  aggiungere una colonna.
- **Nel codice**: «l'opzione che si chiama *Altro* apre il testo». Semplice finché
  qualcuno non la rinomina in «Altro (specificare)» e la regola smette di scattare in
  silenzio. Riconoscere un comportamento **dal testo di un'etichetta** è fragile per
  costruzione.

La prima è più solida e costa una colonna. La seconda è gratis e si rompe alla prima
traduzione — e questo sistema le etichette le traduce.

### Cosa comporta a valle

Non è solo un campo che appare: cambia **cosa vuol dire completo**. Una scheda con
«Altro» scelto e testo vuoto non è compilata, e la cosa deve valere in tre punti —
il form, il salvataggio, e qualunque conteggio di «quante schede sono valutate».

Nota concreta su questo: il filtro e la colonna costruiti oggi decidono «valutata» da
`value` non nullo e diverso da zero. Se una risposta valida può essere **solo testo**,
quel criterio smette di funzionare — un criterio testuale compilato non ha `value`.
Va rivisto nello stesso momento, altrimenti la lista dirà «non valutata» a schede che
lo sono.

## Riepilogo delle scelte

| decisione | opzioni | nota |
|---|---|---|
| nome del campo | `input_type` / `answer_type` / `widget` | `type` è libero in questo dominio, ma non dice quale asse descrive |
| intero vs decimale | tipo unico + precisione | oppure due tipi che fanno la stessa cosa |
| select vs radio | tipo unico + resa | la resa non è un tipo |
| dove sta il campo | colonna + enum · `RuleEnum` · derivato · schemaless | A più esplicita, B senza doppia sorgente |
| coerenza con `rule` | derivare · verificare · sperare | **da decidere insieme al campo** |
| risposta testuale | `note` riusata · colonna nuova | `note` è già «annotazione» |
| «Altro» → testo | flag sull'opzione · nome dell'opzione | il nome si traduce, il flag no |

## Domande aperte

- Il tipo lo decide uno sviluppatore o un amministratore dalla UI? Se sviluppatore,
  un enum chiude la questione.
- Un criterio testuale contribuisce al **punteggio**? Se no, `value` resta NULL e
  tutto ciò che conta «valutata» va rivisto. Se sì, come si converte un testo in un
  numero?
- «Altro» è **un'opzione fra le altre** o uno stato speciale del criterio?
- Un criterio può cambiare tipo dopo che qualcuno ha già risposto? Se sì, le risposte
  vecchie in che tipo restano?

## Riferimenti

- [`select-nelle-valutazioni-analisi.md`](select-nelle-valutazioni-analisi.md)
- `laravel/Modules/Rating/docs/stories/ratings-column-section-filter.story.md` — la
  definizione attuale di «valutata», che questa analisi mette in discussione
