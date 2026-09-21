# Criteri a scelta: opzioni in JSON, figli del criterio, o altro

## Risposta breve

Fra le due strade proposte, i **figli** reggono meglio delle **opzioni in JSON**, ma nessuna
delle due va decisa per prima: prima il criterio deve dichiarare **di che tipo e'**, perche'
oggi lo si deduce confrontando il titolo con una lista di stringhe italiane dentro
un'Action. Senza quel dato, un criterio a scelta entra nel totale senza che nessuno lo abbia
deciso.

## Le sei strade a colpo d'occhio

| | dove stanno le opzioni | l'opzione e' | sopravvive a un rename | costo | quando e' la scelta giusta |
|---|---|---|---|---|---|
| **A** | `extra_attributes.options` (JSON) | un valore | no | nullo | poche voci, nessuno che debba puntarle |
| **B1** | figli del criterio, voto sul figlio | un'entita' | si | basso, tutto gia' in casa | le voci le configura un amministratore |
| **B2** | figli, `value` = id del figlio | un'entita' | si | nullo | **mai** (falsifica le somme) |
| **C** | tabella `rating_options` | un'entita' | si | alto | opzione e criterio sono concetti davvero diversi |
| **D** | enum PHP | una costante | si | nullo | le voci le fissa un contratto, non l'utente |
| **E** | `rule = in:2,4,6` | un vincolo | non applicabile | nullo | complemento, non soluzione (mancano i nomi) |
| **F** | (non e' un'alternativa: e' la premessa) | | | basso | **sempre**, prima delle altre |

Analisi, non implementazione. Nessuna riga di codice scritta, nessuna migrazione proposta
per l'esecuzione. Serve a scegliere, e a rendere visibile cosa si sta scegliendo.

## La domanda

Fra le valutazioni serve anche un criterio a scelta (un `Select`). Due strade sul tavolo:

1. aggiungere al criterio un campo dati di tipo JSON con dentro i valori del select;
2. usare padre e figlio: `parent_id` c'e' gia', i figli diventano le opzioni.

Sotto ci sono anche altre strade, e una osservazione che viene prima di tutte e tre.

## Cosa c'e' oggi, misurato

Installazione `indennita_responsabilita`, letta il giorno dell'analisi.

| fatto | valore |
|---|---|
| criteri in `ratings` | 38 (37 radici piu' un figlio di prova, id 54) |
| struttura | **4 gruppi identici da 9 criteri**, ripetuti |
| cosa distingue i gruppi | `extra_attributes`, es. `{"anno": 2026, "type": "polizia"}` e `{"type":"dip","anno":"2026"}` |
| dei 9, quanti sono giudizi | **5** (Autonomia, Responsabilita', Responsabilita' di spesa, Realizzazione piani e programmi, Supporto decisioni del Dirigente), `rule = numeric\|min:0\|max:5` |
| gli altri 4 | `tot` (`min:0\|max:25\|not_in:1,2,3`) e tre importi, tutti senza `rule` |
| il criterio nuovo | id 52, «ruolo», `{"type":"dip","anno":"2026"}`, **nessuna rule**: e' il caso che apre questa analisi |
| dove finisce il voto | `rating_morph.value`, **`int(11)`** qui e in `progressione_new`, **`decimal(10,3)`** in `ptv_lara`: una riga per criterio e per record valutato |
| ordini di grandezza in `value` | 0..5 per i giudizi, 0..25 per `tot`, 0..250 e 0..3000 per gli importi |
| righe pivot per criterio | fino a 193, di cui 83 con `value` NULL (preparate e mai compilate) |

Due cose si leggono gia' qui, e contano per la scelta.

**Il raggruppamento esiste gia', ed e' fatto per copia.** I 9 criteri non sono condivisi fra
anni e tipologie: sono **duplicati** e distinti da `extra_attributes`. Qualunque cosa si
decida sulle opzioni, seguira' la stessa sorte: le opzioni di «ruolo 2026 dip» non saranno le
stesse di «ruolo 2027 dip», perche' il criterio stesso e' un altro record.

**`value` e' una colonna sola per cose diverse.** Ci convivono un punteggio da 0 a 5, un
totale da 0 a 25 e un importo in euro fino a 3000. `RatingsColumn` fa
`sum('ratingMorphs','value')`: quel totale somma punti ed euro insieme. Non e' il tema di
questa analisi, ma e' il precedente esatto di cio' che succede se in `value` si mette anche
l'identificativo di un'opzione.

## Il fatto che cambia la domanda

Il tipo di un criterio oggi e' scritto **nel titolo**, in italiano, e viene riconosciuto
confrontando stringhe:

```php
// Modules/IndennitaResponsabilita/app/Actions/PrepareCompilaDataAction.php:68
$readonlyTitles = ['tot', 'importo mensile calcolato', 'importo mensile attribuito', 'importo annuale attribuito'];
```

Tutto cio' che non e' in quella lista viene sommato in `tot`, e da `tot` discendono per
moltiplicazione i tre importi. Quindi:

- un criterio nuovo entra **automaticamente** nel totale, qualunque cosa sia;
- se «ruolo» diventa un select e il suo valore e' un numero, quel numero finisce in `tot`;
- rinominare «tot» in «Totale» spegne il calcolo, in silenzio;
- non esiste modo di dire «questo criterio ha un valore, ma non si somma», **benche' il dato
  ci sia**: `is_readonly` e' `true` su tutti e sedici i campi calcolati e `false` sui giudizi,
  e nessuna riga di codice la interroga.

La domanda «JSON o figli» riguarda **dove stanno le opzioni**. La lacuna vera e' un'altra e
sta prima: **il criterio non dichiara che tipo di campo e'**. Numero, scelta, importo
calcolato, testo. Finche' quel dato non esiste, ogni soluzione sulle opzioni va appoggiata su
un titolo confrontato per stringa.

## Le strade

### A. Opzioni in JSON sul criterio

Le opzioni dentro `extra_attributes`, che e' gia' li' e gia' usato:

```json
{"anno": 2026, "type": "dip", "options": {"2": "Responsabile di servizio", "4": "Alta professionalita'"}}
```

**A favore.** Zero migrazioni e zero tabelle: `extra_attributes` esiste, e' castato
`SchemalessAttributes`, ha gia' lo scope `withExtraAttributes()`. Le opzioni restano quello
che sono, configurazione di quel criterio, e seguono il criterio nella duplicazione per anno
e tipologia senza fare nulla. Il pivot non cambia: una riga, `value` numerico, la somma e i
filtri continuano a funzionare come oggi. In Filament e' un `Select` con `->options(...)`
preso dal record.

**Contro.** L'opzione non e' referenziabile: nessuno puo' puntarla, contarla, disattivarla
senza cancellarla. Non ha ordine, colore, icona, traduzione, se non inventando altra
struttura dentro il JSON. Nessuna validazione a livello di database: `value = 7` su un
criterio con opzioni 2 e 4 e' accettato senza un fiato. Cercare «tutti i valutati come Alta
professionalita'» diventa una query sul JSON piu' una sul pivot. E se l'etichetta cambia,
cambia **anche per le valutazioni gia' fatte**, perche' il pivot conserva solo il numero.

### B. Le opzioni sono figli del criterio

`parent_id` c'e', `HasRecursiveRelationships` c'e', il RelationManager dei figli pure.

**A favore.** Ogni opzione diventa un'entita' con quello che un criterio ha gia': `title`,
`order_column` per l'ordine, `color` e `icon` per la resa, `is_disabled` per ritirarla senza
cancellare la storia, `extra_attributes` per il peso, i media, le traduzioni. La scelta
diventa **referenziale**: si sa esattamente quale opzione e' stata scelta, e resta vera anche
se domani la si rinomina. La UI di gestione e' gia' scritta. E il vincolo del punto
precedente si risolve da se': i figli di un criterio duplicato per anno e tipologia sono
gia' i figli di quel criterio.

**Contro.** `children()` diventa ambiguo. Un figlio e' un sotto-criterio (che si valuta e si
somma) o un'opzione (che si sceglie e non si somma)? Oggi nulla lo distingue, e ogni
consumatore dovrebbe saperlo per conto suo. Serve un discriminante esplicito sul padre (il
tipo di campo) o sui figli. In piu' le opzioni finiscono in `ratings` insieme ai criteri:
ogni conteggio, ogni lista, ogni filtro che oggi dice «i criteri» dovra' dire quali.

Due sotto-varianti, e non sono equivalenti.

**B1, la riga pivot sta sul figlio scelto.** `rating_id` punta all'opzione. E' la forma
referenziale piena: la scelta e' un fatto verificabile con una join. Ma il criterio padre
non ha piu' una riga propria, e i conteggi «quanti criteri compilati» cambiano significato.

**B2, la riga pivot sta sul padre e `value` contiene l'id del figlio.** Da scartare, e vale
la pena dire perche': `value` e' la colonna che `RatingsColumn` somma e su cui
`HasRatingValuesFilter` decide se un record e' valutato. Metterci un identificativo significa
che l'id 54 vale come 54 punti. Non si rompe niente subito, il che e' esattamente il modo in
cui questi difetti sopravvivono.

### C. Una tabella dedicata alle opzioni

`rating_options`: `rating_id`, `label`, `value`, `order_column`, `is_disabled`.

Sul nome serve attenzione: negli stessi database esiste gia' `criteri_options`, che **non
c'entra** (vedi la sezione sulle tabelle vicine, in fondo). Una tabella nuova va chiamata in
modo che non la si confonda: `rating_options` va bene, `criteri_*` no.

**A favore.** Separazione netta fra «criterio» e «opzione», chiave esterna vera, nessuna
ambiguita' su `children()`, e la liberta' di dare all'opzione i campi che servono a
un'opzione e nient'altro (per esempio un punteggio associato, distinto dall'etichetta).

**Contro.** Una tabella, un modello, una Resource e una migrazione per ognuna delle
installazioni, per ottenere il 90 per cento di quello che `ratings` sa gia' fare. E i figli
esistono gia'. Ha senso solo se si decide che opzione e criterio sono concetti davvero
diversi, e che quella differenza vale il costo.

### D. Le opzioni come enum PHP

Come `RuleEnum`, che gia' vive nel modulo. Il criterio dichiara quale enum usa, Filament
riceve la classe (`->options(MioEnum::class)`, che e' anche la regola del progetto) e ne trae
etichetta, colore e icona.

**A favore.** Tipizzato, verificabile staticamente, traduzioni via `HasLabel`, zero righe in
tabella, nessuna ambiguita'.

**Contro.** Le opzioni le cambia uno sviluppatore con un deploy, non un amministratore da
pannello. Se «ruolo» ha le stesse voci per contratto e per legge, e' un pregio, perche' quelle
voci **non devono** essere modificabili a mano. Se ogni ente o ogni anno ha voci diverse,
diventa una classe per contesto e non regge.

### E. Le opzioni come regola di validazione

Osservazione: `rule` e' gia' una stringa di regole Laravel, e in Laravel «un insieme chiuso di
valori ammessi» si scrive `in:0,2,4`. Un select e' esattamente questo. La strada minima
sarebbe `rule = 'in:2,4,6'`, e la validazione funzionerebbe da subito.

**Il limite e' che restano i numeri senza i nomi.** `in:2,4,6` dice cosa e' ammesso, non come
si chiama. Le etichette tornano a essere il problema, e vanno comunque in JSON o nei figli.
Utile come complemento (rende il vincolo vero anche fuori dal form), non come soluzione.

### F. Prima di tutte: dichiarare il tipo di campo

Un campo sul criterio che dica cosa e': `numero`, `scelta`, `calcolato`, `testo`. E' la cosa
che manca davvero, quella che oggi si deduce confrontando il titolo con una lista italiana
scritta in un'Action. Con quel dato:

- `PrepareCompilaDataAction` smette di riconoscere gli importi dal nome;
- diventa dicibile «questo criterio ha un valore ma non entra nel totale», che oggi non e'
  esprimibile;
- il posto delle opzioni diventa una conseguenza, non una premessa: si leggono solo quando il
  tipo e' `scelta`.

Da solo non risolve il select, ma senza di lui qualunque soluzione sul select nasce appoggiata
a un confronto di stringhe.

## Le domande che decidono

Non e' una preferenza di stile: quattro risposte determinano la strada.

1. **Chi crea le opzioni?** Un amministratore da pannello, o uno sviluppatore? Se il primo,
   D esce. Se il secondo, D e' la piu' sicura.
2. **L'opzione scelta deve restare identificabile nel tempo, anche dopo un cambio di
   etichetta?** Se si', serve un'entita' (B o C). Se le valutazioni passate possono seguire il
   nuovo nome, basta A.
3. **La scelta pesa sul punteggio?** Se «ruolo» concorre a `tot`, il valore deve essere il
   punteggio dell'opzione e la cosa va detta esplicitamente. Se non concorre, oggi **non c'e'
   modo di dirlo**: serve prima F.
4. **Quante opzioni, e cambiano per anno o per tipologia?** Poche e stabili: A. Tante, con
   ordine e stato: B o C.

## Dove porta l'analisi

Se le opzioni le configura un amministratore e la scelta deve restare leggibile fra tre anni,
**B con la riga pivot sul figlio (B1) e' la piu' solida**, e costa meno delle altre perche'
l'albero, il modello e la UI sono gia' in casa. A una condizione non negoziabile: che il
criterio dichiari di essere una scelta (F), altrimenti `children()` porta due cose diverse con
lo stesso nome e la distinzione vive nella testa di chi legge.

Se invece le voci di «ruolo» sono fissate da contratto e non cambiano per ente, **D e' piu'
onesta**: mette le opzioni dove nessuno le modifica per sbaglio, con traduzioni e colori
gratis.

**A e' la scorciatoia buona per un caso solo**: poche voci, nessuno che debba puntarle,
nessuna esigenza di ordine o disattivazione, e la consapevolezza che rinominare riscrive
anche il passato. Ha il pregio di non toccare niente, ed e' il motivo per cui va valutata sul
serio e non liquidata.

**B2 va scartata esplicitamente**, perche' e' la piu' facile da scrivere: un id dentro `value`
non rompe nulla il primo giorno e falsifica ogni somma da li' in avanti.

## Conseguenze da sorvegliare, qualunque strada si prenda

- `PrepareCompilaDataAction::calculateTotals()` somma **tutto** cio' che non e' nella lista
  dei titoli readonly. Un criterio a scelta entra nel totale senza che nessuno lo decida.
- `RatingsColumn::isRated()` considera valutato un record con somma diversa da zero. Una
  scelta che vale 0 e' indistinguibile da «non risposto».
- I 9 criteri sono duplicati per `anno` e `type`: le opzioni si duplicheranno con loro. Con B
  e' automatico, con D e' impossibile per costruzione (le opzioni sono globali), con A e C va
  verificato che la copia le porti dietro.
- `rating_morph.value` e' `int(11)` qui e in `progressione_new`, `decimal(10,3)` in
  `ptv_lara`, e la classe `RatingMorphData` la dichiara `decimal(10,3)` in creazione e
  `integer` in aggiornamento: quattro dichiarazioni per una colonna sola. Dove stiamo
  lavorando, qualunque cosa ci si metta viene letta come intero da chi somma. Vedi
  [tipo-di-campo-del-criterio.md](tipo-di-campo-del-criterio.md).

## Cosa non fare

- Non decidere il posto delle opzioni prima di aver deciso se il criterio dichiara il proprio
  tipo. E' la premessa, non un dettaglio successivo.
- Non usare `value` per contenere un identificativo.
- Non aggiungere «ruolo» ai `readonlyTitles` per tenerlo fuori dal totale: allungherebbe la
  lista di stringhe italiane che questa analisi indica come il difetto da chiudere.

## Lo stesso criterio nelle quattro forme

«ruolo» (id 52, `{"type":"dip","anno":"2026"}`), tre voci con pesi diversi. Cosi' si vede la
differenza dove conta, cioe' nei dati.

**A, JSON sul criterio.** Un record, nessuna riga in piu'.

```
ratings:      52  ruolo  extra_attributes = {"anno":"2026","type":"dip",
                          "options":{"2":"Responsabile di servizio","4":"Alta professionalita'","6":"Dirigente"}}
rating_morph: rating_id=52  model=Scheda#1234  value=4
```
La scheda 1234 ha scelto «Alta professionalita'». Domani quella voce diventa «Elevata
qualificazione»: la scheda 1234 risultera' aver scelto «Elevata qualificazione», perche' nel
pivot c'e' solo il 4.

**B1, figli con voto sul figlio.** Tre record in piu', una riga pivot che punta alla voce.

```
ratings:      52  ruolo                      parent_id=NULL
              55  Responsabile di servizio   parent_id=52  order_column=1  extra_attributes={"peso":2}
              56  Alta professionalita'      parent_id=52  order_column=2  extra_attributes={"peso":4}
              57  Dirigente                  parent_id=52  order_column=3  is_disabled=1
rating_morph: rating_id=56  model=Scheda#1234  value=4
```
La scelta e' una join, non un numero da interpretare. «Dirigente» si ritira mettendo
`is_disabled`, e le schede che l'avevano scelta restano leggibili. Il rename di 56 non tocca
il fatto che la scheda 1234 abbia scelto **la voce 56**.

**C, tabella dedicata.** Come B1, ma le tre voci non stanno in `ratings`: nessuna lista di
criteri le vede per sbaglio, al prezzo di una tabella, un modello e una Resource per ogni
installazione.

**D, enum.** Nessuna riga: le tre voci sono `case` di una classe, e `value=4` e' il
`RuoloEnum::AltaProfessionalita`. Nessun amministratore le puo' cambiare, il che e' il pregio
o il difetto a seconda della risposta alla domanda 1.

## Il peso dell'opzione e' una decisione a parte

In tutti gli esempi sopra `value = 4` fa due lavori insieme: dice **quale voce** e dice
**quanto vale**. Finche' i due coincidono nessuno se ne accorge. Si separano il giorno in cui
la stessa voce vale 4 per il personale dipendente e 6 per la polizia locale, oppure il giorno
in cui una voce non deve valere niente.

Con A e D il peso e' il valore, e separarli richiede di cambiare forma. Con B1 e C il peso e'
un attributo dell'opzione (`extra_attributes.peso`, o una colonna), e il pivot puo' registrare
la scelta, il peso, o entrambi. E' un altro argomento a favore dell'entita', e vale la pena
deciderlo adesso e non quando il primo ente chiedera' pesi diversi.

## Da leggere accanto

- `Modules/Rating/docs/stories/rating-gerarchia-parent-id-e-figli-in-edit.story.md`, che ha
  introdotto `parent_id` e i figli nell'edit
- `Modules/Rating/docs/stories/ratings-column-section-filter.story.md`, per come somma e
  filtro leggono `value`
- `Modules/IndennitaResponsabilita/app/Actions/PrepareCompilaDataAction.php`, riga 68, la
  lista di titoli italiani da cui parte tutto

## Tabelle vicine che non c'entrano, e cosa insegnano

Negli stessi database vivono tabelle con nomi che sembrano parlare di questo. **Non sono di
`ratings` e non vanno riusate**, ma vale la pena sapere cosa sono, perche' due di loro hanno
gia' affrontato gli stessi problemi con soluzioni diverse.

| tabella | dove | righe | che cos'e' |
|---|---|---|---|
| `criteri_esclusione` | `indennita_responsabilita`, `progressione` | 91 e oltre | le regole che escludono un dipendente dalla graduatoria. Sono **dati di modulo**, elenchi diversi fra installazioni |
| `criteri_options` | entrambe | 40 in `progressione`, 0 in `indennita_responsabilita` | **configurazione delle regole**, non opzioni di un select: `name`, `value`, `type`, `anno`, con righe come `lista_posiz_ruolo = 1` e `lista_posiz_tempo_determinato = 2,21`. Alimenta le Action in `Ptv/app/Actions/CriteriEsclusione/` |
| `criteri_valutazione` | `progressione` | 15 | i criteri di valutazione della progressione: `name`, `label`, `descr`, `post_type`, `posizione`, `anno`, `parent_id` |
| `criteri_precedenza` | `progressione` | 25 | i criteri di ordinamento a parita' di punteggio, stessa forma piu' `order_direction` |

Tre cose da portare via.

**`criteri_options` e' l'omonimo pericoloso.** Il nome suggerisce «le opzioni di un criterio»,
il contenuto sono liste di codici per anno che servono a decidere chi e' escluso. Chi cercasse
un posto dove mettere le voci di un select lo troverebbe per nome e sbaglierebbe bersaglio.

**`criteri_valutazione` ha `parent_id`, e vale `0` in tutte e quindici le righe.** Una colonna
di gerarchia introdotta e mai usata. E' esattamente lo stato in cui era `ratings.parent_id`
fino a questa settimana, ed e' il modo in cui una struttura predisposta muore: nessuno la
popola, nessuno se ne accorge, e dieci anni dopo sembra che ci sia un albero.

**`criteri_valutazione` separa `name` da `label`, e risolve un problema che qui e' aperto.**
`name` e' una chiave tecnica stabile (`excellences_count_last_3_years`), `label` e' il testo
italiano che si mostra. Cambiare la scritta non tocca l'identita' della riga. Nel documento
sopra, il contro principale della strada A e' che rinominare un'opzione riscrive anche il
passato: e' vero **perche' nel JSON l'identita' e' l'etichetta**. Con una chiave stabile
accanto (`{"resp_servizio": {"label": "Responsabile di servizio", "peso": 2}}`) quel contro si
attenua parecchio, e la distanza fra A e le entita' si riduce. Va detto, perche' cambia il
peso di un argomento.

Nota di metodo: `criteri_valutazione` duplica per anno esattamente come `ratings` duplica per
`anno` e `type`. Non e' una stranezza dei rating: e' come questo dominio tratta il tempo, e
qualunque struttura nuova ci si dovra' adattare.
