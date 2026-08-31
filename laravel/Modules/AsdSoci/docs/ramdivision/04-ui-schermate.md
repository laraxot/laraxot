# Specifica UI e Schermate

## Stato documento
- Stato: `Bozza cliente`
- Dipende da: `01-mockup-app-soci.md`, `02-visione-e-perimetro.md`, `03-flussi-operativi.md`

## Scopo
Questo documento descrive le schermate principali del sistema, le azioni disponibili previste in Filament e il comportamento responsive atteso su desktop, tablet e telefono.

Non definisce un layout pixel-perfect. Definisce invece:

- struttura funzionale delle schermate;
- contenuti e campi visibili;
- azioni disponibili lato utente;
- componenti Filament consigliati per realizzarle;
- comportamento responsive e priorita mobile.

## Attori UI
- `Aspirante socio`
- `Socio`
- `Segreteria`
- `Tesoreria`
- `Amministratore`
- `Responsabile eventi`

## Principi UX approvati
- schermate semplici, orientate a segreteria e soci;
- priorita alla leggibilita del libro soci e dello stato pratica;
- azioni principali ben visibili;
- uso da telefono supportato per iscrizione, rinnovo ed eventi;
- riduzione dei passaggi inutili in backoffice;
- uso prevalente di pattern nativi Filament per avere coerenza, velocita e responsive gia solido.

## Principi implementativi Filament

### Pattern principali
- `Resource` per anagrafiche, libro soci, eventi e movimenti;
- `ManageRecords` o pagine indice per liste operative;
- `CreateRecord` e `EditRecord` per schermate guidate;
- `ViewRecord` con `Infolist` per schede di consultazione;
- `Table` con filtri, ricerca e azioni riga;
- `Form` con `Section`, `Grid`, `Fieldset`, `Wizard` e `Repeater` quando serve;
- `HeaderAction`, `BulkAction`, `Action` di riga e `Modal` o `SlideOver` per azioni veloci;
- `Placeholder`, `Badge`, `Tabs`, `StatsOverview` e `Section` per rendere leggibile il contesto.

### Criteri responsive Filament
- smartphone: layout a colonna unica e pulsanti principali ben visibili;
- tablet: griglia a 2 colonne dove utile;
- desktop: griglia piu ampia con lista + dettaglio o piu sezioni affiancate;
- tabelle complesse: su mobile si privilegia card/list view o meno colonne visibili;
- le azioni piu frequenti devono essere disponibili senza menu troppo profondi.

## Catalogo schermate

| Codice | Schermata | Utenti | Obiettivo | Priorita mobile |
|--------|-----------|--------|-----------|-----------------|
| UI-01 | Landing iscrizione e rinnovo | Aspirante socio, Socio | Avviare iscrizione o rinnovo | Molto alta |
| UI-02 | Form domanda socio | Aspirante socio | Raccogliere i dati minimi | Molto alta |
| UI-03 | Login, 2FA e recupero account | Tutti | Accesso sicuro | Alta |
| UI-04 | Coda domande e delibere | Segreteria, Amministratore | Verificare e approvare pratiche | Media |
| UI-05 | Libro soci | Segreteria, Amministratore | Consultare soci attivi e cessati | Media |
| UI-06 | Scheda socio | Segreteria, Tesoreria, Amministratore | Vedere posizione completa del socio | Media |
| UI-07 | Rinnovi, quote e ricevute | Socio, Segreteria, Tesoreria | Gestire rinnovi e pagamenti | Alta |
| UI-08 | Entrate e uscite associazione | Tesoreria, Amministratore | Registrare movimenti economici base | Bassa |
| UI-09 | Calendario eventi | Socio, Responsabile eventi, Segreteria | Consultare e pubblicare eventi | Alta |
| UI-10 | Adesioni evento e check-in | Responsabile eventi, Segreteria | Gestire iscritti e presenze | Media |
| UI-11 | Export e trasmissioni | Segreteria, Amministratore | Generare file per uso esterno | Bassa |
| UI-12 | Configurazioni stagioni e causali | Amministratore | Governare il sistema | Bassa |

## UI-01 Landing iscrizione e rinnovo

### Scopo
Mostrare i due percorsi principali per l'utente finale: nuova iscrizione o rinnovo socio.

### Pattern Filament consigliato
- pagina custom pubblica o area auth dedicata
- card con CTA principali

### Informazioni visibili
- presentazione sintetica associazione;
- pulsante `Nuova iscrizione`;
- pulsante `Rinnova quota`;
- link accesso area socio;
- eventuali avvisi stagione aperta o chiusa.

### Azioni disponibili
- `Nuova iscrizione`;
- `Rinnova`;
- `Accedi`;
- `Recupera account`.

### Responsive
- progettata prima di tutto per telefono;
- card verticali e CTA grandi.

## UI-02 Form domanda socio

### Scopo
Consentire la compilazione della domanda di iscrizione.

### Pattern Filament consigliato
- `Wizard` pubblico o autenticato
- `Form` con `Section` e `Grid`

### Campi principali
- nome e cognome;
- luogo e data di nascita;
- codice fiscale;
- indirizzo di residenza e CAP;
- email e telefono;
- eventuale qualifica;
- eventuale patente o licenza sportiva;
- consensi privacy;
- allegati opzionali se approvati.

### Azioni disponibili
- `Salva bozza`;
- `Invia domanda`;
- `Annulla`.

### Validazioni
- dati anagrafici obbligatori;
- codice fiscale obbligatorio;
- almeno un contatto valido;
- privacy obbligatoria;
- segnalazione possibile duplicato su email o codice fiscale.

### Responsive
- smartphone: form verticale a colonna unica;
- tablet: 2 colonne dove utile;
- desktop: sezioni separate.

## UI-03 Login, 2FA e recupero account

### Scopo
Gestire accesso, secondo fattore e recupero credenziali.

### Pattern Filament consigliato
- pagine auth dedicate
- step di verifica 2FA

### Azioni disponibili
- `Accedi`;
- `Verifica codice`;
- `Recupera password`;
- `Reinvia codice`.

### Responsive
- priorita alta su mobile;
- schermata semplice e essenziale.

## UI-04 Coda domande e delibere

### Scopo
Consentire alla segreteria di lavorare le domande in ingresso.

### Pattern Filament consigliato
- `MemberApplicationResource`
- pagina `ListRecords`
- `Table` con filtri e azioni riga

### Informazioni visibili
- data domanda;
- nominativo;
- email o telefono;
- stagione;
- stato pratica;
- completezza dati;
- eventuali duplicati segnalati.

### Azioni disponibili
- `Apri pratica`;
- `Richiedi integrazione`;
- `Registra delibera`;
- `Approva`;
- `Respingi`.

### Azioni Filament consigliate
- `ViewAction`;
- `Action::make('requestIntegration')`;
- `Action::make('recordBoardDecision')`;
- `Action::make('approve')`;
- `Action::make('reject')`.

### Responsive
- desktop: tabella completa;
- mobile: elenco card con badge stato e azione primaria.

## UI-05 Libro soci

### Scopo
Mostrare il libro soci con struttura coerente al foglio condiviso dal cliente.

### Pattern Filament consigliato
- `MemberRegisterResource`
- `Table` con filtri e export

### Informazioni visibili
- numero d'ordine;
- data domanda;
- data delibera consiglio;
- cognome e nome;
- luogo e data di nascita;
- codice fiscale;
- indirizzo di residenza e CAP;
- email e telefono;
- quota versata;
- data e numero ricevuta;
- data cessazione;
- causa cessazione;
- note o qualifica.

### Azioni disponibili
- `Apri scheda socio`;
- `Esporta`;
- `Filtra attivi`;
- `Filtra cessati`;
- `Registra cessazione`;
- `Ristampa ricevuta`.

### Responsive
- uso principale desktop o tablet;
- su mobile resta consultabile con card e campi principali.

## UI-06 Scheda socio

### Scopo
Mostrare il contesto completo del singolo socio.

### Pattern Filament consigliato
- `ViewRecord`
- `Infolist`
- `Tabs`: profilo, quote, eventi, documenti, storico

### Informazioni visibili
- dati anagrafici completi;
- stato socio;
- stagione corrente;
- storico rinnovi;
- ricevute;
- qualifica;
- eventuale patente o licenza;
- eventi a cui ha aderito;
- cessazione se presente.

### Azioni disponibili
- `Modifica dati ammessi`;
- `Registra rinnovo`;
- `Registra cessazione`;
- `Scarica ricevuta`;
- `Apri storico`.

### Responsive
- su mobile testata compatta, badge e azioni visibili subito;
- su desktop tabs piu ricche.

## UI-07 Rinnovi, quote e ricevute

### Scopo
Gestire rinnovi annuali, quote e documenti associati.

### Pattern Filament consigliato
- `MembershipRenewalResource`
- `CreateRecord` o pagina custom guidata

### Campi principali
- socio;
- stagione;
- importo quota;
- data pagamento;
- modalita pagamento;
- riferimento ricevuta;
- stato rinnovo;
- note amministrative.

### Azioni disponibili
- `Avvia rinnovo`;
- `Conferma pagamento`;
- `Genera ricevuta`;
- `Invia ricevuta`;
- `Segna come scaduto`.

### Validazioni
- socio obbligatorio;
- stagione obbligatoria;
- importo obbligatorio;
- divieto di doppio rinnovo confermato sulla stessa stagione salvo override autorizzato.

### Responsive
- alta priorita mobile per il socio;
- backoffice piu comodo da desktop.

## UI-08 Entrate e uscite associazione

### Scopo
Consentire la registrazione dei movimenti economici operativi.

### Pattern Filament consigliato
- `AccountingEntryResource`
- `Table` + `CreateAction`

### Informazioni visibili
- data movimento;
- tipologia;
- causale;
- soggetto collegato;
- importo;
- stato documento;
- note.

### Azioni disponibili
- `Nuova entrata`;
- `Nuova uscita`;
- `Collega a socio`;
- `Collega a evento`;
- `Prepara per fatturazione`;
- `Esporta`.

### Responsive
- uso principale desktop;
- consultazione sintetica da mobile.

## UI-09 Calendario eventi

### Scopo
Mostrare eventi e consentire adesione.

### Pattern Filament consigliato
- pagina custom calendario
- `Table` o vista card per mobile

### Informazioni visibili
- titolo evento;
- data;
- luogo;
- stato pubblicazione;
- eventuale quota;
- posti disponibili, se previsti.

### Azioni disponibili
- `Aderisci`;
- `Apri dettaglio`;
- `Pubblica evento`;
- `Modifica evento`.

### Responsive
- priorita mobile alta;
- lista eventi in card.

## UI-10 Adesioni evento e check-in

### Scopo
Gestire l'elenco degli iscritti e la presenza.

### Pattern Filament consigliato
- `RelationManager` o pagina dedicata per evento
- `Table` con filtri

### Informazioni visibili
- nominativo socio;
- stato adesione;
- stato pagamento quota evento;
- presenza confermata;
- note organizzative.

### Azioni disponibili
- `Conferma adesione`;
- `Segna presente`;
- `Segna assente`;
- `Registra quota`;
- `Esporta partecipanti`.

### Responsive
- tablet e desktop preferiti;
- mobile adatto a check-in rapido.

## UI-11 Export e trasmissioni

### Scopo
Generare export riutilizzabili fuori dal sistema.

### Pattern Filament consigliato
- pagina custom reportistica
- form filtri in testata

### Informazioni visibili
- tipo export;
- periodo;
- stagione;
- stato generazione;
- ultimo file prodotto.

### Azioni disponibili
- `Genera export libro soci`;
- `Genera export attivi`;
- `Genera export cessati`;
- `Genera export movimenti`;
- `Scarica file`.

### Responsive
- uso principale desktop.

## UI-12 Configurazioni stagioni e causali

### Scopo
Consentire all'amministratore di governare stagione, quote e liste base.

### Pattern Filament consigliato
- `Settings` o `Resource` dedicate

### Informazioni gestite
- stagioni associative;
- importi standard quote;
- categorie entrata e uscita;
- tipi evento;
- parametri export;
- testi base ricevute.

### Azioni disponibili
- `Crea stagione`;
- `Imposta quota standard`;
- `Aggiungi causale`;
- `Aggiorna template ricevuta`.

## Regole trasversali di interfaccia
- I testi visibili all'utente devono essere chiari e non tecnici.
- Gli errori devono indicare cosa manca o cosa non e coerente.
- Le azioni distruttive o irreversibili, se previste, richiedono conferma esplicita.
- I campi obbligatori devono essere evidenti.
- Il libro soci deve restare sempre leggibile e filtrabile.
- Le schermate ad alta frequenza per utenti finali devono minimizzare la digitazione.

## Cosa approva il cliente in questo documento
- elenco schermate previste;
- funzioni disponibili per schermata;
- azioni utente principali e pattern Filament previsti;
- comportamento responsive;
- priorita d'uso da mobile.
