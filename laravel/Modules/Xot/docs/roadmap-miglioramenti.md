# Xot — cosa migliorerei se questo modulo fosse mio per un mese

> I numeri misurati (PHPStan/PHPMD/PHPInsights/casi test) sono in
> [`docs/cosa-migliorare.md`](cosa-migliorare.md), rilevati da un'altra sessione
> il 2026-09-01: PHPStan 0, PHPMD `app/` **1211** ⚠, Code 77.6, **Arch 50.0 —
> il peggiore di tutto il progetto**, 636 casi test. Questo file non
> rimisura: legge quei numeri e ci mette sopra la lente.

Xot è la spina dorsale: 626 file in `app/`, 36 pacchetti runtime, 21 pacchetti
dev (`larastan`, `pest` con 7 plugin, `phpstan-safe-rule`, `laravel/boost`) —
è l'unico modulo dei 18 con un `require-dev` degno di questo nome. Il resto
del monorepo dovrebbe copiare *questo* file, non il contrario. Detto questo,
tre cose mi tengono sveglio la notte.

## 1. `HasXotTable` è un trait che vuole essere due classi

C'è un'altra sessione che ci sta già lavorando (claim in
`docs/chat/claim-hasxottable-migrazione-famiglia-b-2026-09-01.md`), quindi non
la tocco, ma la diagnosi merita di essere scritta anche qui perché è il
sintomo di un pattern più grande: un trait con `method_exists($this, 'getResource')`
sparsi nel corpo (righe 340, 375, 427 al momento in cui scrivo) è un trait che
sta facendo dispatch a runtime quello che il type system dovrebbe fare a
compile time. Family A (`XotBaseResourceTable`, classe pura) e Family B
(componenti Filament con `HasTable`) non sono varianti dello stesso concetto,
sono due concetti — e ogni `method_exists` è un promemoria che nessuno ha
ancora scritto l'interfaccia che li separa.

## 2. 171 `dd()`/`dddx()` in `app/`, 14 `@phpstan-ignore`

Centosettantuno chiamate di debug dump dentro il codice applicativo di un
modulo con PHPStan a livello max e `require-dev` da manuale. Non tutte sono
debito — alcune sono in comandi console pensati per essere interattivi — ma
il rapporto (171 dump ogni 626 file, cioè più di uno ogni quattro file) dice
che il debug "temporaneo" qui è diventato architettura. La cosa visionaria da
fare non è cancellarli a raffica con un sed la settimana prossima: è
aggiungere una regola PHPMD/PHPStan che fallisca la build se `dd(`/`dddx(`
compare fuori da `Console/Commands/*` — così il debug rimane un'eccezione
dichiarata, non un'abitudine invisibile. I 14 `@phpstan-ignore` meritano lo
stesso trattamento della memoria "ogni soppressione nasconde un errore vero":
un audit riga per riga, non un baseline che li fossilizza.

## 3. `docs/` ha 2360 file markdown in root e 284 famiglie di doppioni

Non è iperbole, è `find Modules/Xot/docs -maxdepth 1 -name "*.md" | wc -l`.
`00-index.md`, `00-INDEX.md`, `00-index-v2.md`, `00-master-index.md`,
`00-MASTER-INDEX.md` — cinque file per lo stesso concetto di indice, prima
ancora di arrivare alla A. Questo è il vero costo nascosto: ogni agente (io
incluso, in un'altra sessione, poche ore fa) che deve orientarsi in questo
modulo perde tempo a capire quale dei cinque indici è quello vero, e la
risposta cambia a seconda di quando guardi. Non propongo un mega-merge — la
memoria del progetto (`feedback-dedup-breaks-links-silently.md`) dice
giustamente che de-duplicare a occhio rompe 544 link e nessun gate se ne
accorge. Propongo invece la cosa noiosa che funziona: uno script che
raggruppa per nome-normalizzato (tolto `-1`/`-2`/maiuscole) come ho appena
fatto qui sopra, produce un CSV con "gruppo → file → data ultima modifica →
dimensione", e un umano (o un agente con più tempo di questo) decide gruppo
per gruppo qual è il canonico. 284 decisioni, non 2360 file da leggere.

## La visione, in una riga

Xot ha già la disciplina di processo (tooling, phpstan, pest) ma il punteggio
Architecture di 50.0 — il peggiore del progetto, misurato da un'altra
sessione oggi — dice che la disciplina di *processo* non ha ancora prodotto
disciplina di *struttura*: è coerente con i tre sintomi sopra (trait che fa
dispatch a runtime invece di usare interfacce, 171 dump di debug, 2360 file
doc senza gerarchia). Ed è il modulo da cui ereditano TUTTI gli altri: un
punto di Architecture guadagnato qui vale più di un punto guadagnato ovunque
altro. Il prossimo passo giusto non è scrivere altro codice, è costruire lo
strumento che misura quanto codice/doc vecchio si può buttare via in
sicurezza — e sistemare l'Architecture di Xot per primo, non per ultimo.

---
*Analisi generata il 2026-09-01, dati verificati sul codice (grep/find), non
sulla documentazione esistente — coerente con lo standing order del progetto.*
