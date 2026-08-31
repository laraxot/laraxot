# Flussi Operativi

## Stato documento
- Stato: `Bozza cliente`
- Dipende da: `02-visione-e-perimetro.md`

## Scopo
Questo documento descrive i flussi operativi principali della web app `ASD Soci Ramdivision`.

Non definisce il dettaglio tecnico interno. Definisce invece:

- chi avvia ogni processo;
- quali dati minimi sono richiesti;
- quali stati attraversa la pratica;
- quali esiti produce il sistema;
- quali passaggi restano manuali o da validare.

## Attori coinvolti
- `Aspirante socio`
- `Socio`
- `Segreteria`
- `Tesoreria`
- `Amministratore o consiglio`
- `Responsabile eventi`

## Elenco flussi

| Codice | Flusso | Attori principali | Obiettivo |
|--------|--------|-------------------|-----------|
| FL-01 | Nuova domanda di iscrizione | Aspirante socio, Segreteria, Consiglio | Portare una persona da richiesta iniziale a socio attivo |
| FL-02 | Delibera e attivazione libro soci | Segreteria, Consiglio | Rendere ufficiale l'ammissione e registrarla |
| FL-03 | Rinnovo quota annuale | Socio, Segreteria, Tesoreria | Mantenere attiva la posizione del socio |
| FL-04 | Cessazione socio | Segreteria, Amministratore | Registrare correttamente uscita e motivo |
| FL-05 | Gestione entrate associazione | Tesoreria, Segreteria | Tracciare quote, tesseramenti, sponsorizzazioni e altre entrate |
| FL-06 | Gestione uscite associazione | Tesoreria | Tracciare le spese principali dell'associazione |
| FL-07 | Calendario eventi e adesioni | Socio, Responsabile eventi, Segreteria | Gestire partecipazioni e stato iscritti |
| FL-08 | Export libro soci e trasmissioni | Segreteria, Amministratore | Preparare dati riutilizzabili all'esterno |
| FL-09 | Accesso sicuro e recupero account | Tutti gli utenti | Garantire accesso controllato e continuita operativa |
| FL-10 | Preparazione documenti per fatturazione | Tesoreria, Segreteria | Inviare o predisporre dati verso Fatture in Cloud |

## FL-01 Nuova domanda di iscrizione

### Obiettivo
Consentire a un nuovo utente di inviare la propria richiesta di ammissione compilando i dati essenziali.

### Precondizioni
- il portale iscrizioni e attivo;
- la stagione associativa di riferimento e configurata;
- il candidato non risulta gia presente come socio attivo con gli stessi identificativi.

### Passi principali
1. L'aspirante socio apre la pagina di iscrizione.
2. Compila anagrafica, contatti, codice fiscale, indirizzo e dati aggiuntivi richiesti.
3. Carica o dichiara eventuali informazioni sportive richieste, come patente o licenza, se applicabili.
4. Accetta privacy e condizioni richieste.
5. Invia la domanda.
6. Il sistema crea una pratica in stato `in attesa di verifica`.
7. La segreteria riceve la pratica in backoffice e controlla completezza e possibili duplicati.

### Dati minimi trattati
- nome e cognome;
- luogo e data di nascita;
- codice fiscale;
- indirizzo di residenza;
- email e telefono;
- eventuale qualifica o nota;
- stagione di riferimento;
- data domanda.

### Stati principali
- `bozza`
- `in attesa di verifica`
- `da integrare`
- `pronta per delibera`
- `respinta`

### Esito atteso
La pratica risulta pronta per la delibera oppure viene rinviata all'utente per integrazione.

## FL-02 Delibera e attivazione libro soci

### Obiettivo
Trasformare una domanda valida in una posizione socio ufficiale registrata nel libro soci.

### Precondizioni
- esiste una pratica completata o verificata;
- il consiglio ha espresso l'esito;
- la segreteria dispone dei dati minimi necessari.

### Passi principali
1. La segreteria apre la pratica pronta per delibera.
2. Registra data della delibera del consiglio.
3. Imposta esito `ammesso` o `non ammesso`.
4. Se ammesso, il sistema crea il record socio.
5. Il sistema assegna numero d'ordine progressivo del libro soci.
6. La posizione entra in stato `attivo`.
7. Se prevista, viene generata o collegata la ricevuta iniziale della quota.

### Dati minimi trattati
- data domanda;
- data delibera consiglio;
- nominativo;
- codice fiscale;
- quota versata;
- data e numero ricevuta, se gia disponibile;
- qualifica o note.

### Stati principali
- `pronta per delibera`
- `ammessa`
- `non ammessa`
- `attiva`

### Esito atteso
Il socio compare nel libro soci con storico coerente e campi minimi valorizzati.

## FL-03 Rinnovo quota annuale

### Obiettivo
Consentire al socio di rinnovare la quota e alla segreteria o tesoreria di confermare il rinnovo.

### Precondizioni
- il socio esiste gia nel sistema;
- e configurata la stagione o annualita successiva;
- la quota standard o personalizzata e definita.

### Passi principali
1. Il socio accede alla propria area o riceve il link di rinnovo.
2. Visualizza importo e stagione di riferimento.
3. Conferma i dati personali ammessi e aggiorna eventuali campi modificabili.
4. Effettua il rinnovo o segnala pagamento gia avvenuto.
5. La segreteria o tesoreria conferma l'incasso.
6. Il sistema registra quota e ricevuta.
7. La posizione del socio risulta `rinnovata` per la stagione selezionata.

### Dati minimi trattati
- socio;
- stagione;
- importo quota;
- data pagamento;
- riferimento ricevuta;
- eventuali note amministrative.

### Stati principali
- `da rinnovare`
- `rinnovo avviato`
- `in attesa pagamento`
- `rinnovata`
- `scaduta`

### Esito atteso
Il rinnovo aggiorna stato del socio per la nuova stagione e resta consultabile a storico.

## FL-04 Cessazione socio

### Obiettivo
Registrare in modo corretto la cessazione della posizione socio mantenendo lo storico.

### Precondizioni
- il socio esiste nel libro soci;
- esiste una decisione amministrativa o una comunicazione valida.

### Passi principali
1. La segreteria apre la scheda del socio.
2. Inserisce data cessazione.
3. Inserisce causa cessazione.
4. Conferma l'operazione.
5. Il sistema sposta il socio nella vista `cessati` mantenendo storico quote e ricevute.

### Dati minimi trattati
- data cessazione;
- causa cessazione;
- nota interna eventuale.

### Stati principali
- `attivo`
- `in cessazione`
- `cessato`

### Esito atteso
Il socio non compare piu tra gli attivi ma resta esportabile con storico completo.

## FL-05 Gestione entrate associazione

### Obiettivo
Tracciare le principali entrate economiche dell'associazione.

### Tipologie previste
- quota associativa;
- tesseramento;
- sponsorizzazione;
- versamento liberale;
- vendita merchandising;
- quota evento.

### Passi principali
1. La tesoreria apre la schermata entrate.
2. Seleziona tipologia movimento.
3. Collega il movimento a socio, sponsor o evento quando applicabile.
4. Inserisce importo, data, causale e riferimento documento.
5. Salva il movimento.
6. Il sistema lo rende disponibile per ricevute, riepiloghi ed export.

### Esito atteso
Ogni entrata risulta consultabile per periodo, tipologia e soggetto collegato.

## FL-06 Gestione uscite associazione

### Obiettivo
Registrare le uscite principali senza entrare nella contabilita completa.

### Tipologie previste
- banca;
- telefono;
- imposte o tasse;
- assicurazioni;
- affitti;
- segreteria;
- spese social;
- acquisti merchandising;
- spese varie.

### Passi principali
1. La tesoreria apre la schermata uscite.
2. Seleziona categoria.
3. Inserisce importo, data, fornitore o riferimento libero.
4. Allega nota o documento se previsto.
5. Salva il movimento.

### Esito atteso
Le uscite risultano tracciate e distinguibili per categoria, periodo e nota.

## FL-07 Calendario eventi e adesioni

### Obiettivo
Gestire il ciclo completo evento -> adesione -> lista partecipanti.

### Precondizioni
- l'evento e stato creato e pubblicato;
- l'utente e autenticato se l'evento e riservato ai soci.

### Passi principali
1. Il responsabile eventi crea l'evento con data, luogo e descrizione.
2. Il socio visualizza il calendario eventi.
3. Il socio aderisce all'evento.
4. Il sistema registra la partecipazione in stato `iscritto`.
5. Se l'evento prevede un importo, segreteria o tesoreria registrano il pagamento.
6. Il backoffice consulta lista iscritti e stato di partecipazione.

### Stati principali
- `bozza`
- `pubblicato`
- `iscritto`
- `in lista attesa`
- `annullato`
- `partecipato`

### Esito atteso
L'associazione ha elenco partecipanti aggiornato e storico adesioni per socio.

## FL-08 Export libro soci e trasmissioni

### Obiettivo
Preparare rapidamente dati leggibili e riutilizzabili all'esterno.

### Passi principali
1. La segreteria apre l'area export.
2. Seleziona tipo export: libro soci, attivi, cessati, movimenti, evento.
3. Applica filtri per periodo o stagione.
4. Conferma generazione file.
5. Il sistema produce file scaricabile.

### Output attesi
- export in formato Excel o CSV del libro soci;
- export soci attivi o cessati;
- export movimenti economici base;
- export preparatorio per trasmissioni amministrative o sportive.

### Esito atteso
I dati possono essere controllati, archiviati o usati in sistemi esterni senza ricopiare manualmente.

## FL-09 Accesso sicuro e recupero account

### Obiettivo
Consentire accessi controllati e ridurre il rischio di uso improprio dell'account.

### Passi principali
1. L'utente effettua login.
2. Il sistema richiede secondo fattore se attivo.
3. L'utente completa verifica.
4. In caso di smarrimento accesso, avvia recupero account.
5. Il sistema registra gli eventi di sicurezza minimi.

### Esito atteso
L'accesso e protetto e i ruoli limitano la visibilita dei dati in base al profilo.

## FL-10 Preparazione documenti per fatturazione

### Obiettivo
Predisporre i dati economici utili per Fatture in Cloud o processo equivalente del cliente.

### Passi principali
1. La tesoreria seleziona i movimenti idonei a documentazione fiscale o amministrativa.
2. Verifica anagrafica collegata e causale.
3. Prepara l'invio o esportazione verso Fatture in Cloud.
4. Registra esito `preparato`, `inviato` o `da correggere`.

### Esito atteso
Il passaggio verso fatturazione risulta tracciato senza pretendere automazione completa nella prima fase.

## Regole trasversali di processo
- Nessun socio entra nel libro soci come `attivo` senza passaggio di delibera registrato.
- La cessazione non cancella lo storico.
- Un pagamento o movimento economico deve avere almeno data, importo e causale.
- Ogni export deve essere filtrabile almeno per periodo o stagione.
- Le funzioni economiche restano operative e non sostituiscono la contabilita professionale.

## Cosa approva il cliente in questo documento
- i flussi principali inclusi nel progetto;
- gli attori coinvolti;
- gli stati principali delle pratiche;
- gli esiti attesi;
- il livello di automazione previsto nella prima fase.
