# TwentyOne Homepage UI/UX Audit

Documento operativo per migliorare la homepage `predict.local/it` del tema `TwentyOne` incrociando stato reale del progetto e linee guida UI/UX research-backed.

## Obiettivo

Rendere la homepage piu' bella, piu' chiara e piu' credibile, senza fingere un livello di maturita' del prodotto che il backend non supporta ancora.

## Principi guida

- **chiarezza prima di spettacolo**
- **trust prima di decorazione**
- **una CTA primaria per hero**
- **navigazione semplice e non ridondante**
- **homepage come orientamento, non come dumping di widget**

## Stato percepito attuale

### Punti forti
- il tema ha gia' molti blocchi marketing e market-oriented
- la homepage risponde da guest
- esistono superfici per hero, markets, leaderboard, CTA, stats, trust

### Punti deboli
- il messaggio principale della homepage non emerge subito
- header e footer risultano visivamente ridondanti
- la homepage rischia di sembrare una raccolta di blocchi invece di un percorso
- search, categorie e mercati in evidenza competono troppo tra loro
- alcune superfici possono apparire piu' mature del backend reale

## Findings runtime confermati

### 1. Footer con brand duplicato

La homepage attiva mostrava il logo sia nell'header che nel footer. Questo aumentava la ridondanza senza aggiungere fiducia.

Stato: corretto.

### 2. Mobile tabs con markup non valido

Il file `layouts/mobile_tabs.blade.php` aveva tag `<a>` non chiusi correttamente, con rischio reale di comportamento UI incoerente su mobile.

Stato: corretto.

### 3. CTA footer delle card non coerente

Le card dei mercati mostravano una CTA di puntata secondaria che non era il punto di ingresso migliore nella homepage. La card ha gia' azioni di puntata sul corpo e deve tenere il footer piu' semplice.

Stato: corretto semplificando il footer action layer.

### 4. Homepage senza focus forte su un solo messaggio

Il contenuto attivo porta rapidamente ai mercati, ma manca ancora una hero/intro di livello homepage con proposta di valore esplicita.

Stato: aperto.

### 5. Delta reale rispetto ai competitor

Dall'analisi delle homepage leader del settore emergono quattro pattern ricorrenti:

- promessa di valore leggibile in pochi secondi;
- accesso immediato ai mercati;
- spiegazione rapida di come funziona;
- trust chiaro su regole e rischio.

Stato: corretto in buona parte sulla homepage canonica introducendo hero, blocco onboarding/trust e CTA finale.

## Regole UI/UX da applicare

### 1. Hero con proposta di valore netta

La prima schermata deve spiegare in pochi secondi:

- cos'e' Predict
- perche' e' diverso
- quale azione iniziare

Per `predict.local/it` la hero dovrebbe comunicare:
- prediction market con crediti virtuali a tappi di bottiglia
- mercati chiari e immediati
- partecipazione senza frizione

### 2. Una CTA primaria, una secondaria

Oggi il rischio e' avere troppi punti di ingresso. La hero deve avere:

- CTA primaria: `Esplora i mercati`
- CTA secondaria: `Come funziona`

### 3. Search come fallback, non come protagonista assoluta

La ricerca deve essere molto visibile ma non mangiare il messaggio di valore.

### 4. Navigazione con scope chiaro

La navbar deve chiarire subito i 3 ambiti principali:

- Mercati
- Classifica
- Come funziona

Tutto il resto va secondarizzato o spostato in dropdown meno invasivi.

### 5. Trust immediato sopra la piega

Subito dopo la hero servono segnali di fiducia:

- soldi virtuali / tappi di bottiglia
- nessun rischio reale
- regole trasparenti
- mercati chiusi e risolti in modo verificabile

### 6. Prima i mercati, poi il marketing

La homepage di un prediction market deve mostrare presto mercati utili, non solo claim.

Ordine raccomandato:

1. Hero
2. trust strip
3. mercati in evidenza
4. categorie
5. leaderboard / social proof
6. spiegazione semplice del funzionamento
7. CTA finale

### 7. Ridurre la ridondanza visiva

Se il logo o i blocchi social dominano sia header che footer, l'effetto e' dispersione. Il footer deve chiudere la pagina, non competere con l'header.

### 8. Mobile-first reale

La homepage deve evitare:

- caroselli poco controllabili
- liste troppo lunghe
- hit targets piccoli
- testo compresso

## Interventi consigliati per `TwentyOne`

### Priorita' P0

- semplificare l'header riducendo elementi simultanei
- eliminare o ridurre la duplicazione del brand tra header e footer
- introdurre una hero unica e piu' netta
- aggiungere una trust strip con 3-4 punti chiari
- portare i mercati in evidenza sopra elementi accessori

Stato attuale P0:

- hero unica: `100%`
- blocco onboarding/trust: `100%`
- ordine homepage piu' leggibile: `80%`
- semplificazione header: `40%`
- rifinitura footer: `70%`

### Priorita' P1

- scegliere un solo blocco mercati principale per la homepage
- spostare ricerca e categorie in una sezione ordinata sotto la hero
- usare una gerarchia tipografica piu' marcata
- migliorare spacing verticale per separare i blocchi

### Priorita' P2

- introdurre micro-stati onesti: `mercato aperto`, `in risoluzione`, `demo`, `dati reali`
- migliorare la consistenza tra homepage e pagina mercato
- aggiungere screenshot audit ricorrenti desktop/mobile

## Decisioni di design consigliate

### Header
- meno icone concorrenti
- nav primaria con 3-4 voci
- login/register visibili ma non dominanti

### Hero
- titolo forte e leggibile
- sottotitolo breve
- CTA doppia ben distinta
- immagine o card market reale, non astratta

### Market cards
- mostrare probabilita', volume e stato
- evitare numeri finti
- evidenziare il mercato principale con piu' contrasto

### Footer
- logo piu' piccolo o solo testuale
- link utili, compliance, social secondari
- niente “seconda homepage” nel footer

## Metriche UX da inseguire

- chiarezza del messaggio above the fold: target **90%**
- visibilita' CTA primaria: target **95%**
- ridondanza percepita header/footer: sotto **10%**
- accesso ai mercati in massimo 1 scroll: target **100%**

## Prossima wave consigliata

1. rifare hero + trust strip
2. semplificare header
3. ridurre footer
4. scegliere il blocco mercati migliore
5. verificare desktop e mobile con screenshot comparativi
