# Pacchetto Documentazione Approvativa

## Scopo
Questa cartella contiene il pacchetto documentale di riferimento per il progetto `ASD Soci Ramdivision`.

L'obiettivo del pacchetto e:

- descrivere con chiarezza cosa verra realizzato;
- rendere leggibili interfacce, flussi, dati e logiche;
- definire in modo esplicito cosa rientra nel perimetro e cosa ne resta fuori;
- fornire al cliente una base approvativa stabile per sviluppo, collaudo e gestione delle variazioni.

## Documenti sorgente di partenza
Il pacchetto deriva dal materiale iniziale condiviso dal cliente:

- `Struttura Libro dei Soci ASD(2).xlsx`;
- `Web App Soci ramdivision.pdf`.

Questi materiali restano utili come base di analisi, ma i file di questa cartella li riorganizzano in forma piu approvabile, separando:

- mockup funzionale;
- visione e perimetro;
- flussi operativi;
- schermate e UX;
- modello dati concettuale e regole;
- governance di approvazione e collaudo.

## Elenco documenti

| File | Scopo | Cosa approva il cliente |
|------|-------|-------------------------|
| `01-mockup-app-soci.md` | Descrive il mockup funzionale del pannello ASD Soci | Struttura generale del prodotto, ruoli e macro-schermate |
| `02-visione-e-perimetro.md` | Definisce obiettivi, attori, inclusioni, esclusioni e fasi | Il perimetro generale del progetto |
| `03-flussi-operativi.md` | Descrive i processi operativi end-to-end | Il comportamento atteso del sistema nei casi d'uso principali |
| `04-ui-schermate.md` | Elenca schermate, campi, azioni e comportamento responsive | Cosa vedranno e useranno segreteria, tesoriere e soci |
| `05-modello-dati-e-regole.md` | Formalizza entita, relazioni, stati e dati minimi | Il modello concettuale del dominio |
| `06-accettazione-e-governance.md` | Definisce collaudo, approvazione e change request | Le regole di verifica e gestione delle variazioni |

## Ordine consigliato di lettura
1. `01-mockup-app-soci.md`
2. `02-visione-e-perimetro.md`
3. `03-flussi-operativi.md`
4. `04-ui-schermate.md`
5. `05-modello-dati-e-regole.md`
6. `06-accettazione-e-governance.md`

## Livello di dettaglio
Il pacchetto e intenzionalmente dettagliato ma non entra in aspetti tecnici interni non rilevanti per il cliente.

Sono quindi approvati:

- comportamento del sistema;
- dati trattati;
- schermate previste;
- regole operative;
- limiti del perimetro;
- criteri di accettazione.

Non sono invece oggetto di approvazione vincolante:

- naming definitivo delle tabelle;
- struttura interna del codice;
- dettagli di framework;
- scelte implementative interne non percepibili dall'utente;
- micro-variazioni estetiche che non alterano il comportamento concordato.

## Stato del pacchetto
Ogni documento dovra essere gestito con una versione esplicita:

- `Bozza interna`
- `Bozza cliente`
- `Versione approvata`
- `Versione revisionata`

## Regola generale
In caso di conflitto interpretativo, vale questo ordine:

1. `06-accettazione-e-governance.md`
2. `02-visione-e-perimetro.md`
3. `03-flussi-operativi.md`
4. `04-ui-schermate.md`
5. `05-modello-dati-e-regole.md`
6. `01-mockup-app-soci.md`

L'intento di questo ordine e evitare ambiguita fra perimetro approvato, descrizione operativa e implementazione logica.
