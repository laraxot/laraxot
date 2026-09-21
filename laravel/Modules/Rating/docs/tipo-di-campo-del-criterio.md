# Il tipo di campo di un criterio: intero, decimale, select, radio, testo libero

Analisi, non implementazione. Prosegue
[criteri-a-scelta-multipla.md](criteri-a-scelta-multipla.md), che si fermava dicendo «prima
il criterio deve dichiarare di che tipo e'». Questo documento apre quella frase.

## Risposta breve

Il campo serve, ma **non e' un campo solo**. La frase «di che tipo e' questo criterio»
nasconde tre decisioni che oggi collassano in una:

1. **che cosa si chiede** (numero, scelta, testo): il tipo di input;
2. **dove atterra la risposta** (`value`, il figlio scelto, `note`): oggi c'e' una colonna
   sola per i numeri, e per il testo una colonna vuota che nessuno usa;
3. **quando una risposta e' obbligatoria** (il «se scegli Altro, il testo diventa
   obbligatorio»): e questa regola non appartiene al criterio, appartiene **all'opzione**.

Aggiungere `input_type` senza rispondere alla seconda e' la mossa che sembra risolvere e non
risolve: il criterio direbbe «sono un testo libero» e il testo non avrebbe dove andare.

## Fatti misurati

| fatto | valore |
|---|---|
| `rating_morph.value` in `indennita_responsabilita` | **`int(11)`** |
| `rating_morph.value` in `progressione_new` | **`int(11)`** |
| `rating_morph.value` in `ptv_lara` (connessioni `ptv` e `rating`) | **`decimal(10,3)`** |
| `RatingMorphData::createColumns()` dichiara | `decimal('value', 10, 3)` |
| `RatingMorphData::updateColumns()` dichiara | `integer('value')` |
| `rating_morph.note` | `text`, nullable, esiste in entrambe |
| righe pivot in `indennita_responsabilita` | 2298 |
| di cui con `note` valorizzata | **0** |
| `ratings.rule` | stringa di regole Laravel, es. `numeric\|min:0\|max:5` |
| `Modules/UI/app/Enums/FieldTypeEnum.php` | **esiste gia'**: TEXT, EMAIL, TEXTAREA, SELECT, RADIO, CHECKBOX, DATE, TIME, DATETIME |
| suoi consumatori fuori dai test | **zero** |
| `case NUMBER` in quell'enum | **commentato** |
| `ratings.is_readonly` sui 4 campi calcolati (`tot` e i tre importi) | **`true`, su tutti e 16** (4 gruppi per 4 campi) |
| `ratings.is_readonly` sui 5 criteri di giudizio e su «ruolo» | `false` |
| `ratings.is_disabled` | `false` ovunque |
| chi legge `is_readonly` per decidere un comportamento | **nessuno**: e' scritta, mostrata in tabella, infolist e form, mai interrogata |
| come si riconosce oggi un criterio calcolato | confronto del titolo con una lista italiana (`PrepareCompilaDataAction:68`) |
| `Modules/Rating` dipende da `Modules/UI` | **no** (composer: solo `spatie/laravel-schemaless-attributes`) |

Tre di questi meritano di essere letti due volte.

**`value` ha tre tipi, non uno.** `int(11)` in `indennita_responsabilita` e in
`progressione_new`, `decimal(10,3)` in `ptv_lara`. Quindi la domanda «intero o decimale» non
e' una questione di validazione, ed e' peggio di come sembrava: **dove stiamo lavorando il
decimale non entra**, mentre in una terza installazione entra. Un criterio da 0 a 5 con mezzi
punti verrebbe troncato in silenzio, che e' il modo peggiore di perdere un dato.

**Quattro dichiarazioni per una colonna sola.** Due in produzione (`int(11)`,
`decimal(10,3)`) e due nel codice: `createColumns()` dice `decimal(10,3)`,
`updateColumns()` dice `integer`. Un'installazione nuova nascerebbe decimale come
`ptv_lara`, mentre le due dove si sta lavorando restano intere. E' lo stesso difetto silente gia' segnalato per i
morphs nella story 18.40: non rompe niente qui, produce installazioni diverse fra loro.

**Il posto per il testo libero esiste ed e' vuoto.** `note` e' `text`, nullable, presente
ovunque, e su 2298 righe non e' mai stata valorizzata. Non serve inventare dove mettere il
testo: serve decidere se `note` e' quel posto o se e' altro (una nota del valutatore e' una
cosa, la risposta a «Altro, specificare» e' un'altra, e usare una colonna per entrambe le
rende indistinguibili).

## La prima domanda: che cosa si chiede

L'elenco proposto e' `intero, decimal, select, radio, testo libero`. Va guardato con
attenzione, perche' mescola tre piani diversi.

**Intero e decimale non sono due tipi: sono lo stesso tipo con una precisione diversa.**
«Numero con zero decimali» e «numero con due decimali» chiedono la stessa cosa all'utente e
si differenziano per un parametro. Tenerli come due `case` distinti raddoppia le
combinazioni per ogni cosa che verra' dopo (valuta? percentuale? con quante cifre?). Un
tipo `numero` con accanto la precisione dice la stessa cosa e non si moltiplica.

**Select e radio non sono due tipi: sono la stessa cosa disegnata in due modi.** Sotto c'e'
identicamente «un insieme chiuso di opzioni, se ne sceglie una». La differenza e' di resa, e
dipende da quante opzioni ci sono, non da cosa sia il criterio. Metterle come due `case`
significa che chi configura decide anche il disegno; tenerle come una sola (`scelta`) e
lasciare la resa a una regola di presentazione significa che il dato dice **cosa e'** e
l'interfaccia decide **come si vede**. Nessuna delle due e' sbagliata, ma va scelta
consapevolmente: e' la differenza fra un campo di dominio e un campo di presentazione.

**Il quarto caso, `calcolato`, e' gia' dichiarato nei dati, e nessuno lo legge.** `tot`,
`importo mensile calcolato`, `importo mensile attribuito` e `importo annuale attribuito` non
sono input: sono risultati. E il database lo dice gia': `is_readonly = true` su tutte e
sedici quelle righe (quattro campi per quattro gruppi), `false` sui cinque giudizi e su
«ruolo». Il dato e' completo e corretto.

Solo che **non lo interroga nessuno**. `is_readonly` e' fillable, castata a booleano, mostrata
come badge nelle tabelle e come toggle nel form, e non compare in una sola condizione:
`PrepareCompilaDataAction` continua a riconoscere i calcolati confrontando il titolo con
quattro stringhe italiane. Il campo che serviva c'era gia', ed e' stato aggirato.

Questo cambia la conclusione di prima. Non e' «manca il tipo calcolato»: e' che **la
modificabilita' e' gia' un dato, ed e' un dato diverso dal tipo**.

Un vocabolario che regge i dati veri assomiglia piu' a questo:

| tipo | cosa chiede | dove atterra oggi |
|---|---|---|
| `numero` (con precisione) | un valore numerico | `value` |
| `scelta` | una fra N opzioni | `value` (peso) oppure il figlio scelto |
| `testo` | testo libero | `note`, o una colonna nuova |

## Tipo e modificabilita' sono due assi, non uno

`tot` non e' «di tipo calcolato»: e' **un numero da 0 a 25 che l'utente non scrive**. Il tipo
dice che cosa e' il valore, la modificabilita' dice chi lo mette. Sono indipendenti, e i dati
lo confermano gia': `tot` ha `rule = min:0|max:25|not_in:1,2,3` (quindi un tipo pieno, con
vincoli) **e** `is_readonly = true`.

Tenerli su un asse solo produce subito una combinazione impossibile da dire. Una scelta
calcolata, per esempio: la fascia assegnata automaticamente in base al punteggio, che si
mostra come voce ma non si sceglie. Con un enum che ha `numero | scelta | testo | calcolato`
quella cosa non ha nome. Con due assi e' `scelta` piu' `is_readonly`, e non serve inventare
niente.

Quindi la strada piu' economica non e' aggiungere `calcolato` all'elenco dei tipi: e'
**cominciare a leggere `is_readonly`** dove oggi si confrontano i titoli, e aggiungere accanto
il tipo per la sola cosa che `is_readonly` non sa dire, cioe' che cosa si chiede.

Resta una domanda a valle, che va posta ora perche' cambia il nome giusto del campo: chi
calcola, e quando? `is_readonly` dice «non lo scrive l'utente». Non dice **chi** lo scrive ne'
con quale formula. Oggi la formula e' in `calculateTotals()`, in codice, e va bene finche' e'
una sola. Se domani ogni ente ha la sua, `is_readonly` restera' vera e insufficiente.


## La seconda domanda: dove atterra la risposta

E' la parte che il tipo di campo trascina con se', ed e' quella che si dimentica.

Oggi la risposta ha **un solo bersaglio numerico**, `value`. Dichiarare un criterio di tipo
`testo` senza decidere il bersaglio produce un criterio che si compila e non si salva, o che
si salva in un posto scelto da chi implementa quel giorno. Le combinazioni possibili:

- **testo in `note`.** Costo zero, colonna gia' presente e vuota. Rischio: `note` diventa
  ambigua, perche' e' anche il posto naturale per un commento del valutatore. Se un giorno
  servono entrambi, non si distinguono piu'.
- **testo in una colonna nuova** (`text_value` o simile). Costo: una migrazione additiva su
  tutte le installazioni. Vantaggio: `note` resta la nota, la risposta resta la risposta.
- **testo dentro `extra_attributes` del pivot.** Il pivot non ce l'ha oggi. Sarebbe la
  soluzione piu' elastica e la meno interrogabile.

E per il numero decimale: **finche' `value` e' `int(11)`, il tipo `decimale` e' dichiarabile
ma non rappresentabile.** O si allinea la colonna, o quel tipo non va offerto. Offrirlo senza
allineare e' peggio che non averlo, perche' l'utente configura un criterio con due decimali e
il sistema ne salva zero senza dire niente.

## La terza domanda: «se scegli Altro, il testo e' obbligatorio»

Qui c'e' il punto piu' interessante, ed e' anche quello che decide fra le strade del
documento precedente.

**Quella regola non appartiene al criterio, appartiene all'opzione.** Non e' «il criterio
ruolo richiede un testo»: e' «**la voce Altro** richiede un testo». Le altre voci no. Quindi
il posto dove scriverla dipende da cosa e' un'opzione:

- **se le opzioni sono figli** (entita'), la regola e' un attributo del figlio «Altro»:
  `extra_attributes.requires_text = true`, oppure una colonna. Naturale, e si legge dove sta
  la cosa che la genera;
- **se le opzioni sono JSON**, diventa un'altra chiave dentro lo stesso JSON:
  `{"4": {"label": "Altro", "requires_text": true}}`, e la struttura del JSON cresce da mappa
  a oggetto;
- **se le opzioni sono un enum**, e' un metodo sull'enum (`requiresText(): bool`), che e'
  la forma piu' pulita delle tre ma la meno configurabile.

E' un argomento che pesa a favore delle opzioni come entita': una regola per opzione ha
bisogno di un posto per opzione.

**Poi c'e' il livello a cui la regola vive.** Sono due, e servono entrambi per motivi diversi:

1. **nel form** (`required` condizionato alla scelta): e' quello che l'utente vede, e senza
   il quale l'esperienza e' sbagliata;
2. **nella validazione lato dati**: e' quello che regge quando la risposta non arriva dal
   form. Import massivi, seed, API, correzioni via console. Se la regola sta solo nel form,
   quelle strade la aggirano in silenzio e nel database restano «Altro» senza testo.

`rule` e' gia' una stringa di regole Laravel, e Laravel ha gia' `required_if`. La strada di
esprimere li' anche la condizione esiste, e ha il pregio di non inventare un formato nuovo. Il
limite e' che `required_if:campo,valore` deve nominare un campo, e oggi i «campi» di una
valutazione non hanno nomi stabili: sono righe di pivot identificate da `rating_id`.

## Dove mettere la dichiarazione del tipo

| | dove | a favore | contro |
|---|---|---|---|
| **A** | colonna `input_type` su `ratings` | esplicita, indicizzabile, si vede nello schema, si filtra | migrazione additiva su tutte le installazioni |
| **B** | chiave in `extra_attributes` | zero migrazioni, sta accanto ad `anno` e `type` che sono gia' li' | e' struttura, non etichetta: nascosta in un JSON, non filtrabile senza query JSON, nessun vincolo |
| **C** | dedurlo da `rule` | zero campi nuovi, e per i numeri **funziona gia'** (`numeric\|min:0\|max:5` dice tutto) | per scelta, testo e calcolato `rule` non dice niente: i quattro criteri calcolati hanno `rule` vuota, esattamente come «ruolo» |

C merita un momento in piu' perche' e' seducente: sembra che il dato ci sia gia'. Ma `rule`
vuota oggi significa tre cose diverse (un importo calcolato, un criterio a scelta non ancora
definito, un criterio senza vincoli), e un campo che significa tre cose non e' un campo.

## Riusare `FieldTypeEnum` di `Modules/UI`, o farne uno di `Rating`

Esiste gia' e nessuno lo usa. Prima di scriverne un altro, la domanda giusta e' se e' lo
stesso concetto.

**A favore del riuso.** C'e', implementa `HasLabel`, `HasIcon`, `HasColor`, quindi in Filament
si passa come classe (`->options(FieldTypeEnum::class)`) che e' la forma richiesta dal
progetto. Copre TEXT, TEXTAREA, SELECT, RADIO, CHECKBOX, DATE. Duplicarlo significa avere due
vocabolari per la stessa cosa, che e' il difetto che questi documenti cercano di chiudere.

**Contro.** Gli manca il numero (il `case NUMBER` e' **commentato**), e gli mancano precisione
e valuta. Non gli manca `calcolato`, ed e' giusto cosi': quello e' `is_readonly`, un asse
diverso. `FieldTypeEnum` risponde a «che widget disegno», `Rating` ha bisogno di rispondere a
«che cosa si chiede». Sono due domande vicine e non identiche, ma piu' vicine di quanto
sembrasse prima di separare i due assi: tolto `calcolato`, la distanza si riduce a numero e
precisione.

**E c'e' una dipendenza da aprire.** `Modules/Rating` oggi non dipende da `Modules/UI`. Usare
quell'enum la crea. Va verificato che la direzione sia consentita prima di darla per scontata:
e' lo stesso tipo di vincolo della regola su Sigma e Ptv.

Le tre uscite oneste sono: riusare **completando** l'enum di UI (decommentare NUMBER e
aggiungere cio' che manca), oppure scriverne uno in `Rating` **dicendo nel docblock perche'
non e' quello di UI**, oppure spostare il vocabolario comune in `Xot` se serve a piu' moduli.
Quello che non va fatto e' scriverne uno nuovo in silenzio, lasciando due enum quasi uguali a
due passi di distanza.

## Conseguenze da sorvegliare

- **Il totale.** `PrepareCompilaDataAction::calculateTotals()` somma ogni criterio che non sia
  nella lista dei titoli readonly. Un criterio di tipo `testo` non ha valore numerico e non
  disturba; un criterio di tipo `scelta` **entra nel totale** con il numero che porta.
- **«Valutato» diventa falso per i criteri non numerici.** `RatingsColumn::isRated()` dice
  valutato se la somma e' diversa da zero. Un criterio testuale compilato e un criterio a
  scelta che vale 0 risultano entrambi «non valutati». Aggiungere tipi non numerici senza
  toccare quel criterio significa che la lista dira' il falso.
- **La precisione decimale non e' retroattiva.** Anche allineando la colonna, i valori gia'
  scritti come interi restano interi: nessuna perdita, ma nessun recupero.
- **Un tipo dichiarato e non implementato e' peggio di un tipo assente.** Se l'enum offre
  `decimale` e la colonna e' `int`, chi configura ha ragione a fidarsi e il dato si perde.

## Cosa non fare

- Non aggiungere un tipo `calcolato` all'elenco: quel dato esiste gia' ed e' corretto su tutte
  e sedici le righe. Il lavoro non e' dichiararlo un'altra volta, e' **leggerlo** dove oggi si
  confrontano i titoli.
- Non usare `rule` come tipo del campo: `rule` dice **come si valida**, non **che cosa e'**.
  Oggi la sua assenza significa gia' tre cose diverse.
- Non introdurre `input_type` senza decidere contestualmente il bersaglio della risposta per
  ogni tipo. Il tipo senza il bersaglio e' una dichiarazione senza effetto.
- Non lasciare l'obbligatorieta' condizionale solo nel form: import, seed e API la aggirano
  senza rumore.
- Non mettere la regola «richiede testo» sul criterio: e' della singola opzione, e metterla
  sul criterio obbligherebbe il testo anche per le voci che non lo prevedono.
- Non aggiungere un `case` per ogni sfumatura numerica (intero, decimale, valuta,
  percentuale): la precisione e' un parametro, non un tipo.

## Le domande che decidono

1. Il decimale serve **davvero** su un criterio esistente, o e' una previsione? Se serve, la
   colonna `value` va allineata prima di offrire il tipo.
2. Il testo libero e' una **risposta** o una **nota**? Se sono due cose, servono due posti, e
   `note` e' gia' occupata concettualmente da una delle due.
3. Chi sceglie fra select e radio: chi configura il criterio, o l'interfaccia in base al
   numero di opzioni?
4. `is_readonly` resta il modo di dire «non lo scrive l'utente», o serve sapere anche **chi**
   lo scrive e con quale formula? Se la formula diventa configurabile per ente, il flag
   booleano non basta piu'.
5. La condizione «Altro richiede testo» deve valere anche fuori dal form? Se si', va espressa
   sul dato, non solo nell'interfaccia.

## Un precedente sullo stesso problema, in un'altra tabella

`criteri_valutazione` in `progressione` (15 righe) e `criteri_precedenza` (25) descrivono
criteri con `name`, `label`, `descr`, `post_type`, `posizione`, `anno`. Non sono di `ratings`
e non vanno riusate, ma hanno gia' preso una decisione che qui e' aperta: **la chiave tecnica
e' separata dall'etichetta**. `name` e' stabile e in inglese, `label` e' il testo che si
mostra e si puo' cambiare senza conseguenze.

Questo e' rilevante per il tipo di campo. Se il tipo verra' dichiarato, va dichiarato con un
valore stabile (un `case` di enum, non una scritta), per la stessa ragione per cui li' `name`
non e' `label`. E vale come conferma indiretta del difetto principale di oggi: riconoscere i
campi calcolati confrontando titoli italiani significa aver usato la `label` dove serviva il
`name`.

`criteri_options`, malgrado il nome, non e' una tabella di opzioni di un criterio: contiene
liste di codici per anno che alimentano le regole di esclusione. Non e' il posto delle voci di
un select.

## Da leggere accanto

- [criteri-a-scelta-multipla.md](criteri-a-scelta-multipla.md), che questo documento prosegue
- `Modules/Rating/app/Datas/RatingMorphData.php`, dove `value` e' dichiarata due volte in due modi
- `Modules/UI/app/Enums/FieldTypeEnum.php`, l'enum che esiste e nessuno usa
- `Modules/IndennitaResponsabilita/app/Actions/PrepareCompilaDataAction.php`, riga 68
