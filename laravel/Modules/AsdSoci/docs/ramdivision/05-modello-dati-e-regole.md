# Modello Dati e Regole

## Stato documento
- Stato: `Bozza cliente`
- Dipende da: `02-visione-e-perimetro.md`, `03-flussi-operativi.md`, `04-ui-schermate.md`

## Scopo
Questo documento formalizza il modello concettuale del dominio `ASD Soci Ramdivision`, usando come riferimento principale il foglio `Libro dei Soci` condiviso dal cliente e i requisiti sintetici presenti nel PDF.

Non descrive il database tecnico definitivo. Definisce invece:

- le entita di dominio principali;
- i legami logici tra esse;
- i campi minimi da garantire;
- gli stati operativi;
- le regole di business da rispettare.

## Principi del modello
- Il `Libro soci` e il centro del dominio.
- Una `domanda di ammissione` precede il `socio attivo`.
- La `delibera` abilita l'ingresso ufficiale nel libro soci.
- `Quote`, `ricevute`, `eventi` e `movimenti economici` si collegano al socio ma non ne sostituiscono la scheda principale.
- La `cessazione` chiude la posizione attiva ma non cancella lo storico.

## Entita principali

### 1. MemberApplication
Rappresenta la domanda iniziale di iscrizione.

Campi minimi:
- id pratica;
- stagione;
- data domanda;
- nome;
- cognome;
- luogo e data di nascita;
- codice fiscale;
- indirizzo di residenza e CAP;
- email;
- telefono;
- qualifica o note iniziali;
- eventuale patente o licenza sportiva;
- stato pratica.

### 2. Member
Rappresenta il socio registrato e ufficialmente ammesso.

Campi minimi:
- id socio;
- numero progressivo libro soci;
- stato socio;
- anagrafica completa;
- codice fiscale;
- contatti;
- indirizzo;
- qualifica;
- data prima ammissione;
- data cessazione, se presente;
- causa cessazione, se presente.

### 3. BoardDecision
Rappresenta il passaggio di delibera del consiglio o dell'organo abilitato.

Campi minimi:
- id decisione;
- riferimento domanda;
- data delibera;
- esito;
- note interne.

### 4. MembershipSeason
Rappresenta la stagione o annualita associativa.

Campi minimi:
- id stagione;
- nome stagione;
- data inizio;
- data fine;
- stato attiva o chiusa;
- importo quota standard.

### 5. MembershipRenewal
Rappresenta iscrizione o rinnovo del socio per una stagione.

Campi minimi:
- id rinnovo;
- socio;
- stagione;
- importo quota;
- data pagamento;
- stato rinnovo;
- riferimento ricevuta.

### 6. MembershipReceipt
Rappresenta la ricevuta collegata a quota o altro pagamento.

Campi minimi:
- id ricevuta;
- numero ricevuta;
- data ricevuta;
- importo;
- causale;
- soggetto collegato;
- stato documento.

### 7. Event
Rappresenta un evento a calendario.

Campi minimi:
- id evento;
- titolo;
- descrizione sintetica;
- data;
- luogo;
- quota evento, se prevista;
- stato pubblicazione.

### 8. EventRegistration
Rappresenta l'adesione di un socio a un evento.

Campi minimi:
- id adesione;
- evento;
- socio;
- data adesione;
- stato adesione;
- stato pagamento;
- presenza finale.

### 9. AccountingEntry
Rappresenta un movimento economico operativo.

Campi minimi:
- id movimento;
- data movimento;
- tipo `entrata` o `uscita`;
- categoria;
- causale;
- importo;
- soggetto collegato, se presente;
- riferimento documento o nota;
- stato fatturazione o export.

### 10. MemberDocument
Rappresenta documenti collegati al socio o alla pratica.

Campi minimi:
- id documento;
- socio o pratica collegata;
- tipo documento;
- filename o riferimento;
- data caricamento.

## Mappatura minima del foglio libro soci
Il file Excel condiviso contiene questi campi minimi, da trattare come riferimento strutturale del libro soci:

| Colonna sorgente | Significato nel dominio | Entita prevalente |
|------------------|-------------------------|-------------------|
| N. Ordine | progressivo libro soci | `Member` |
| Data Domanda | data della richiesta | `MemberApplication` |
| Data Delibera Consiglio | data approvazione | `BoardDecision` |
| Cognome e Nome | identificazione socio | `Member` |
| Luogo e Data di Nascita | anagrafica base | `Member` |
| Codice Fiscale | identificativo univoco principale | `Member` |
| Indirizzo di Residenza e CAP | recapito postale | `Member` |
| Email e Telefono | contatti | `Member` |
| Quota Versata | importo pagato | `MembershipRenewal` o `AccountingEntry` |
| Data e N. Ricevuta | documento di pagamento | `MembershipReceipt` |
| Data Cessazione | fine rapporto associativo | `Member` |
| Causa Cessazione | motivo di chiusura | `Member` |
| Note / Qualifica | annotazioni utili | `Member` |

## Relazioni concettuali

- una `MemberApplication` puo generare zero o un `Member`;
- un `Member` appartiene a una o piu `MembershipSeason` tramite `MembershipRenewal`;
- un `Member` puo avere zero o piu `MembershipReceipt`;
- un `Member` puo avere zero o piu `EventRegistration`;
- un `Event` puo avere molti `EventRegistration`;
- un `AccountingEntry` puo essere collegato a un `Member`, a un `Event` o restare generico;
- un `Member` puo avere zero o piu `MemberDocument`.

## Stati principali

### Stati domanda
- `bozza`
- `in attesa di verifica`
- `da integrare`
- `pronta per delibera`
- `respinta`
- `approvata`

### Stati socio
- `attivo`
- `da rinnovare`
- `scaduto`
- `sospeso`
- `cessato`

### Stati rinnovo
- `bozza`
- `in attesa pagamento`
- `pagato`
- `confermato`
- `annullato`

### Stati evento
- `bozza`
- `pubblicato`
- `chiuso`
- `annullato`

### Stati adesione evento
- `iscritto`
- `in lista attesa`
- `pagato`
- `partecipato`
- `assente`
- `annullato`

### Stati fatturazione o export
- `non preparato`
- `preparato`
- `inviato`
- `scartato`

## Regole di business

### Regole anagrafiche
- Il codice fiscale e il principale identificativo univoco del socio.
- Email e telefono devono essere trattati come dati utili al contatto, non come chiave primaria assoluta.
- I dati obbligatori devono essere sufficienti a popolare il libro soci senza re-lavorazioni manuali.

### Regole libro soci
- Il numero d'ordine del libro soci deve essere progressivo e non riutilizzato.
- Un socio attivo deve avere data domanda e data delibera registrate.
- La cessazione non elimina il record dal libro soci.
- Data cessazione e causa cessazione sono obbligatorie quando lo stato passa a `cessato`.

### Regole quote e ricevute
- Per ogni rinnovo confermato deve esistere importo quota.
- La ricevuta deve avere almeno data, numero e importo.
- Il sistema deve impedire, salvo override autorizzato, due rinnovi confermati per la stessa stagione e lo stesso socio.

### Regole economiche
- Ogni movimento economico deve avere data, tipologia, categoria e importo.
- Le entrate possono essere collegate a socio, sponsor, evento o vendita.
- Le uscite possono essere generiche, ma devono avere causale leggibile.
- Il modulo economico non sostituisce la contabilita ufficiale dell'associazione.

### Regole eventi
- Un evento `bozza` non deve essere visibile ai soci.
- Un evento con quota puo avere adesioni anche prima del pagamento, se il cliente conferma questo comportamento.
- Lo storico adesioni deve restare collegato al socio.

### Regole sicurezza
- I ruoli determinano cosa ogni utente puo leggere o modificare.
- La 2FA e obbligatoria almeno per ruoli amministrativi e auspicata per tutti i profili interni.
- Gli eventi di sicurezza rilevanti devono essere tracciati almeno in forma base.

### Regole export
- L'export del libro soci deve mantenere l'ordine logico del registro.
- Gli export devono distinguere almeno soci attivi e cessati.
- Gli export massivi verso enti terzi richiedono validazione del tracciato prima di essere considerati definitivi.

## Dati configurabili
- importo quota standard;
- stagioni associative;
- categorie entrata;
- categorie uscita;
- tipi evento;
- template ricevute;
- parametri export.

## Vincoli da non perdere
- Il sistema deve poter ricostruire con facilita la storia di un socio.
- Il libro soci deve restare sempre esportabile.
- Il cliente ha esplicitamente citato registrazione tramite app o mobile, quindi la UX responsive e un requisito operativo, anche se la prima fase non include per forza app nativa.
- L'integrazione con Fatture in Cloud e da intendere inizialmente come base o semi-automazione, salvo specifica piu dettagliata.

## Cosa approva il cliente in questo documento
- entita concettuali principali;
- relazioni logiche tra i dati;
- campi minimi del libro soci;
- stati principali delle pratiche;
- regole di business fondamentali del dominio.
