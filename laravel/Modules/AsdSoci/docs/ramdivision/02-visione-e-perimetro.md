# Visione e Perimetro

## Stato documento
- Stato: `Bozza cliente`
- Ambito: web app soci ASD Ramdivision
- Documenti collegati: `01-mockup-app-soci.md`, materiale cliente PDF e foglio libro soci

## Obiettivo del progetto
Il progetto ha lo scopo di trasformare la gestione dei soci dell'associazione da processo frammentato in uno strumento operativo unico, utilizzabile da segreteria, tesoreria e soci.

Il sistema deve aiutare il cliente a:

- raccogliere domande di iscrizione e rinnovo;
- mantenere aggiornato il libro soci;
- tracciare quote, ricevute ed entrate principali;
- registrare le spese ricorrenti dell'associazione;
- gestire adesioni a eventi;
- produrre esportazioni utili agli adempimenti amministrativi o sportivi;
- migliorare sicurezza accessi e continuita operativa.

## Problema che il progetto risolve
Oggi il processo rischia di restare disperso tra moduli, email, fogli Excel, ricevute separate e aggiornamenti manuali.

Il progetto punta quindi a ridurre:

- duplicazione dei dati socio;
- errori nel libro soci;
- ritardi tra domanda, delibera e attivazione;
- difficolta nel ricostruire quote pagate, ricevute e cessazioni;
- tempo perso per preparare esportazioni o invii massivi;
- dipendenza da una sola persona per sapere "a che punto siamo".

## Principi guida
- Il sistema deve essere comprensibile anche a utenti non tecnici.
- Il libro soci deve restare il riferimento centrale del dominio.
- Il flusso iscrizione -> delibera -> attivazione -> rinnovo deve essere tracciabile.
- Le funzioni economiche devono coprire i bisogni operativi base senza trasformarsi in ERP completo.
- L'uso da telefono deve essere semplice per soci e segreteria.
- Dove il requisito non e definito in modo univoco, il pacchetto deve esplicitare assunzioni e limiti.

## Attori previsti

### Socio o aspirante socio
Utente che compila la domanda di iscrizione, rinnova la quota, aggiorna i propri dati ammessi, aderisce agli eventi e consulta ricevute e stato della propria posizione.

### Segreteria
Utente che gestisce domande, approvazioni operative, aggiornamento del libro soci, ricevute, documenti e assistenza agli utenti.

### Tesoreria
Utente che registra quote, tesseramenti, sponsorizzazioni, versamenti liberali, vendite merchandising e uscite operative dell'associazione.

### Consiglio direttivo o amministratore
Utente con visione completa del sistema. Conferma delibere, gestisce parametri, configurazioni, export e supervisione generale.

### Responsabile eventi
Utente che pubblica eventi, verifica adesioni, presenze e informazioni organizzative.

## Ambito funzionale incluso
Il perimetro approvato della prima fase comprende almeno le seguenti macro-funzioni.

### 1. Registrazione e rinnovo soci
- form di iscrizione online;
- form di rinnovo quota annuale;
- raccolta anagrafica utente;
- raccolta codice fiscale;
- raccolta contatti principali;
- raccolta indirizzo di residenza;
- gestione di eventuale patente o licenza sportiva quando richiesta;
- consenso privacy e presa visione informative;
- accesso tramite browser desktop e mobile.

### 2. Domande di ammissione e delibera
- coda domande in attesa;
- consultazione dati inseriti;
- registrazione data domanda;
- registrazione data delibera consiglio;
- esito ammesso o non ammesso;
- produzione posizione socio attiva dopo approvazione;
- gestione motivazione o nota interna dove necessario.

### 3. Libro soci
- archivio soci attivi;
- archivio soci cessati;
- storico ordinato per numero progressivo;
- allineamento dei campi minimi al foglio Excel condiviso;
- visualizzazione di quota versata, ricevuta, data cessazione e causa cessazione;
- export del libro soci in formato tabellare.

### 4. Quote, tesseramenti e ricevute
- registrazione quote associative;
- rinnovi stagionali o annuali;
- registrazione tesseramenti;
- emissione o registrazione ricevute;
- associazione pagamento -> socio -> stagione;
- consultazione storico pagamenti del socio.

### 5. Entrate ulteriori
- sponsorizzazioni;
- versamenti liberali;
- vendita merchandising;
- partecipazioni a eventi con eventuale importo;
- categorizzazione causale;
- consultazione storica per periodo.

### 6. Uscite principali associazione
- spese amministrative;
- spese banca;
- telefono;
- imposte e tasse;
- assicurazioni;
- affitti;
- segreteria;
- spese social;
- acquisti merchandising;
- spese varie di gestione.

### 7. Eventi
- calendario eventi;
- pubblicazione scheda evento;
- adesione all'evento da parte del socio;
- registrazione stato adesione;
- registrazione eventuale quota evento;
- lista partecipanti per backoffice.

### 8. Sicurezza e accessi
- autenticazione utente;
- recupero accesso;
- sicurezza a 2 fattori;
- ruoli base distinti;
- storico minimo di accessi o eventi sensibili lato amministrativo.

### 9. Export e trasmissioni
- esportazione libro soci;
- esportazione elenco soci attivi/cessati;
- esportazione movimenti economici di base;
- esportazioni massive per trasmissioni amministrative o sportive secondo formato da confermare col cliente;
- preparazione dati per invii verso CONI o registro equivalente nel linguaggio operativo del cliente.

### 10. Integrazione con fatturazione
- predisposizione di un collegamento con `Fatture in Cloud`;
- invio manuale o semi-guidato dei dati economici rilevanti;
- visibilita dello stato di sincronizzazione o di preparazione documento;
- gestione come integrazione base da validare nel dettaglio.

## Ambito esplicitamente escluso
Restano fuori dal perimetro base, salvo successiva estensione approvata, le seguenti funzionalita:

- contabilita generale completa;
- bilanci civilistici o rendiconti ufficiali generati in autonomia dal sistema;
- riconciliazione bancaria automatica;
- gestione fiscale avanzata;
- integrazione bidirezionale completa con Fatture in Cloud senza analisi dedicata;
- integrazioni real-time con federazioni, CONI o enti terzi senza specifiche tecniche validate;
- firma elettronica qualificata;
- app mobile nativa con pubblicazione store come requisito implicito della prima fase;
- wallet, badge NFC o tessera digitale evoluta;
- ecommerce merchandising completo;
- gestione magazzino merchandising avanzata;
- CRM sponsor evoluto;
- multi-associazione;
- workflow complessi di approvazione multi-step;
- reportistica direzionale avanzata oltre agli export base;
- automazioni email o WhatsApp complesse non definite in questi documenti.

## Assunzioni di progetto
Le seguenti assunzioni sono parte del presente pacchetto e devono essere considerate valide salvo diversa approvazione scritta:

- il foglio Excel condiviso rappresenta il modello minimo del libro soci;
- il numero progressivo del libro soci resta un dato centrale e non solo decorativo;
- la delibera consiglio e un passaggio registrato nel sistema, anche se il verbale formale puo restare esterno;
- il sistema gestisce quote e movimenti economici di base, non sostituisce il commercialista;
- l'integrazione con Fatture in Cloud richiede definizione successiva del livello di automazione;
- la prima fase copre una singola associazione;
- il sistema e usato da browser desktop e mobile;
- il linguaggio operativo del cliente fa riferimento a `CONI`, ma il tracciato effettivo dovra essere validato secondo procedura aggiornata del cliente;
- i casi rari o fortemente eccezionali non descritti in questi documenti non sono inclusi automaticamente.

## Vincoli operativi dichiarati
- Il progetto deve restare coerente con il materiale cliente gia condiviso.
- La prima fase non deve richiedere una infrastruttura troppo complessa per essere usata dalla segreteria.
- Il cuore del progetto e la corretta tenuta del libro soci.
- La UX deve essere semplice abbastanza da consentire rinnovo e iscrizione anche da smartphone.
- Le esportazioni devono essere facilmente riutilizzabili fuori dal sistema.

## Roadmap consigliata

### MVP 1
- registrazione utente;
- domanda di iscrizione;
- delibera e attivazione socio;
- libro soci con campi minimi;
- rinnovo quota annuale;
- ricevuta base;
- elenco entrate e uscite principali;
- calendario eventi con adesioni;
- ruoli base;
- 2FA;
- export libro soci.

### MVP 2
- miglioramento ricevute e documenti;
- miglioramento integrazione Fatture in Cloud;
- export massivi piu strutturati;
- storico accessi e audit base;
- raffinamento UX socio da mobile;
- parametri piu ricchi per quote, causali ed eventi.

### Extra o evoluzioni successive
- app scaricabile o nativa;
- integrazioni enti terzi;
- firma elettronica avanzata;
- dashboard economiche evolute;
- portale sponsor;
- tessera digitale o badge.

## Cosa approva il cliente in questo documento
Con l'approvazione di questo documento il cliente approva:

- obiettivo del progetto;
- attori previsti;
- elenco delle macro-funzioni incluse;
- elenco delle esclusioni;
- assunzioni operative;
- vincoli di progetto;
- segmentazione per fasi.

## Cosa non viene approvato qui
Questo documento non approva nel dettaglio:

- struttura database definitiva;
- layout dettagliato delle schermate;
- naming tecnico finale delle entita applicative;
- casi di test di collaudo;
- dettaglio delle singole validazioni di campo.

Questi aspetti sono approvati nei documenti successivi del pacchetto.
