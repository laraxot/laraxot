# Accettazione e Governance

## Stato documento
- Stato: `Bozza cliente`
- Dipende da: `02-visione-e-perimetro.md`, `03-flussi-operativi.md`, `04-ui-schermate.md`, `05-modello-dati-e-regole.md`

## Scopo
Questo documento definisce come il cliente approva il perimetro, come si valuta il collaudo e come si gestiscono eventuali richieste di modifica.

L'obiettivo e evitare che il progetto venga valutato in modo ambiguo o mobile nel tempo.

## Regola generale di accettazione
Si considera conforme quanto:

- rientra esplicitamente nel perimetro approvato;
- rispetta i flussi operativi descritti;
- espone le schermate concordate con contenuti e azioni coerenti;
- tratta i dati minimi del libro soci e dei movimenti come definiti;
- non introduce in automatico funzioni escluse o mai approvate.

## Criteri di accettazione per area

### 1. Iscrizione e onboarding
Il cliente puo considerare accettata l'area se:

- e possibile compilare una nuova domanda socio;
- i campi minimi obbligatori sono presenti;
- il sistema distingue pratica completa da pratica incompleta;
- la pratica arriva alla coda backoffice in modo leggibile.

### 2. Delibera e libro soci
Il cliente puo considerare accettata l'area se:

- la segreteria puo registrare data delibera;
- l'approvazione crea il socio attivo;
- il libro soci mostra i campi minimi del foglio condiviso;
- attivi e cessati risultano distinguibili;
- il progressivo libro soci e gestito in modo coerente.

### 3. Rinnovi, quote e ricevute
Il cliente puo considerare accettata l'area se:

- il rinnovo puo essere avviato per una stagione;
- l'importo quota viene registrato;
- e possibile associare una ricevuta;
- lo storico rinnovi del socio e consultabile.

### 4. Entrate e uscite
Il cliente puo considerare accettata l'area se:

- e possibile registrare almeno le tipologie economiche incluse nel perimetro;
- ogni movimento ha data, causale e importo;
- i movimenti sono filtrabili e consultabili.

### 5. Eventi
Il cliente puo considerare accettata l'area se:

- e possibile creare un evento;
- il socio puo aderire;
- il backoffice vede l'elenco iscritti;
- la partecipazione resta consultabile a storico.

### 6. Export
Il cliente puo considerare accettata l'area se:

- il libro soci e esportabile;
- gli export risultano leggibili e riutilizzabili;
- il contenuto corrisponde ai dati presenti a sistema.

### 7. Sicurezza
Il cliente puo considerare accettata l'area se:

- l'accesso utente funziona;
- i ruoli limitano le aree visibili;
- la 2FA e utilizzabile almeno sui profili interni previsti.

## Test di collaudo consigliati

### Scenario A - Nuovo socio
1. Creare una nuova domanda.
2. Verificare completezza dati.
3. Registrare delibera positiva.
4. Controllare presenza del socio nel libro soci.

### Scenario B - Rinnovo
1. Aprire un socio esistente.
2. Avviare rinnovo per nuova stagione.
3. Registrare quota e ricevuta.
4. Controllare storico aggiornato.

### Scenario C - Cessazione
1. Aprire scheda socio attivo.
2. Inserire data e causa cessazione.
3. Verificare che il socio esca dagli attivi ma resti nello storico.

### Scenario D - Evento
1. Creare evento.
2. Far aderire un socio.
3. Verificare presenza dell'iscrizione nella lista partecipanti.

### Scenario E - Export
1. Generare export libro soci.
2. Controllare che i campi minimi siano presenti.
3. Verificare coerenza con i dati mostrati a video.

## Assunzioni di collaudo
- Il collaudo riguarda il comportamento funzionale e non l'implementazione interna.
- Micro-variazioni estetiche non cambiano l'accettazione se non alterano la funzione.
- Dati demo o seed di prova sono ammessi per il collaudo.
- Le integrazioni esterne non dettagliate vengono validate come simulazione o placeholder, non come obbligo di esercizio reale.

## Gestione modifiche
Ogni richiesta successiva alla validazione del presente pacchetto deve essere classificata in uno dei seguenti modi:

### 1. Chiarimento
Non cambia perimetro, tempi o logiche. Serve solo a esplicitare meglio quanto gia approvato.

### 2. Correzione
Allinea il sistema a quanto gia approvato ma implementato in modo difforme.

### 3. Estensione
Introduce una nuova funzione, un nuovo report, una nuova integrazione o una variazione sostanziale del flusso.

### 4. Cambio di perimetro
Sostituisce o altera in modo significativo una funzione gia approvata. Richiede rivalutazione di tempi e costo.

## Esempi pratici di change request
Sono da considerare `estensione` o `cambio di perimetro`, salvo diverso accordo:

- app nativa pubblicata sugli store;
- integrazione completa e bidirezionale con Fatture in Cloud;
- export ufficiale verso un ente con tracciato non ancora definito;
- contabilita avanzata;
- automazioni massive email, SMS o WhatsApp;
- multi-associazione;
- ecommerce merchandising completo.

Sono invece da considerare `chiarimento` o `correzione`:

- rinomina di etichette UI;
- aggiustamento ordine colonne nell'export gia approvato;
- chiarimento di un testo utente;
- miglioramento minore di validazioni coerenti col perimetro gia definito.

## Deliverable approvativi
Il pacchetto approvativo minimo e composto da:

- `README.md`
- `01-mockup-app-soci.md`
- `02-visione-e-perimetro.md`
- `03-flussi-operativi.md`
- `04-ui-schermate.md`
- `05-modello-dati-e-regole.md`
- `06-accettazione-e-governance.md`

## Ordine di prevalenza
In caso di conflitto interpretativo vale il seguente ordine:

1. `06-accettazione-e-governance.md`
2. `02-visione-e-perimetro.md`
3. `03-flussi-operativi.md`
4. `04-ui-schermate.md`
5. `05-modello-dati-e-regole.md`
6. `01-mockup-app-soci.md`

## Cosa approva il cliente in questo documento
- il metodo di collaudo;
- i criteri di accettazione;
- il significato di chiarimento, correzione, estensione e cambio di perimetro;
- le regole di prevalenza fra i documenti del pacchetto.
