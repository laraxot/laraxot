---
title: "Rating — scopo del modulo e come raggiungerlo meglio"
type: concept
status: active
created: 2026-09-02
tags: [rating, purpose, polimorfico, valutazione, punteggio, morph]
qmd: "rating scopo modulo polimorfico morph punteggio valutazione riusabile scala"
updated: 2026-09-02
issues:
  # DA CREARE — `gh` non autenticato: mai numeri inventati.
  # gh issue create --repo provtv/module_rating_fila5 --title "<argomento del file>"
  - "https://github.com/provtv/module_rating_fila5/issues/"
discussions:
  # DA CREARE — vedi sopra.
  - "https://github.com/provtv/module_rating_fila5/discussions/"
---

# Rating — perche' esiste

## Lo scopo in una frase

**Rating rende "dare un punteggio a qualcosa" un meccanismo unico e riusabile, invece
di una tabella di voti reinventata in ogni modulo che valuta.**

## L'evidenza

- `Rating`, `RatingMorph`, piu' le basi `BaseRating`, `BaseRatingMorph`: il modello e'
  **polimorfico** — un punteggio puo' agganciarsi a qualunque entita'.
- Solo 59 file PHP: e' il modulo piu' piccolo del dominio, ed e' giusto cosi'.
- I moduli che lo usano si vedono dai loro modelli: `Rating`/`RatingMorph` compaiono in
  Performance, Progressioni e IndennitaResponsabilita.

## Perche' vale la pena che sia un modulo

Senza Rating, tre moduli avrebbero tre tabelle di punteggi con tre convenzioni diverse
su scala, arrotondamento e storicizzazione. La domanda "che punteggio ha preso questa
persona" avrebbe tre risposte diverse a seconda di chi la fa.

Il polimorfismo qui non e' eleganza fine a se stessa: e' il modo per avere **una sola
risposta**.

## Il rischio strutturale del polimorfismo

Una relazione morph non ha vincoli di integrita' referenziale: il database non puo'
garantire che `ratable_id` punti a qualcosa che esiste. Se l'entita' valutata viene
cancellata, il punteggio resta orfano — e un punteggio orfano non e' rilevabile con una
query di consistenza standard.

Vale inoltre la convenzione del progetto sulle migrazioni morph: `morphs()` e i tipi
delle colonne devono seguire lo standard `XotBaseMigration`, altrimenti la relazione
funziona nei test e fallisce con dati reali.

## Come raggiungerlo **meglio**

### 1. Serve un controllo di consistenza per gli orfani

**Azione:** un comando che elenchi i `RatingMorph` il cui bersaglio non esiste piu'. Non
deve cancellarli — potrebbero essere la traccia di una valutazione storica — deve
**mostrarli**. Cio' che il database non puo' garantire, lo garantisce un controllo
esplicito.

### 2. La scala va dichiarata, non dedotta

Se un modulo usa 0–100 e un altro 1–5, un confronto fra punteggi di moduli diversi e'
privo di senso — e nulla lo impedisce.

**Azione:** la scala e' un attributo del rating (minimo, massimo, passo), validato in
scrittura. E `docs/scales.md` che elenchi quali scale sono in uso e da chi.

### 3. Un punteggio che cambia deve lasciare traccia

Un voto modificato dopo la comunicazione e' un fatto rilevante. Se l'aggiornamento
sovrascrive, la storia sparisce.

**Azione:** i punteggi che alimentano un provvedimento sono immutabili: una correzione
crea una nuova versione e conserva la precedente. In alternativa, integrazione esplicita
con il modulo Activity per l'audit.

### 4. Il modulo non deve conoscere il significato del punteggio

Rating sa che 87 sta fra 0 e 100. **Non** deve sapere che 87 vuol dire "supera la soglia
per l'indennita'": quella e' una regola del dominio, e appartiene al modulo che la
possiede. La tentazione di aggiungere qui una soglia "perche' e' comoda" e' il modo in
cui un modulo generico diventa specifico e smette di essere riusabile.

### 5. `BaseRating` esiste: va usato come contratto

Le classi base indicano un punto di estensione previsto. Un modulo che ha bisogno di un
comportamento proprio estende; non copia la tabella.

## Confini — cosa **non** appartiene a Rating

- Il **significato** e le soglie: modulo di dominio.
- Il **calcolo** che porta al punteggio: modulo di dominio.
- **Chi** ha assegnato il punteggio come identita': User (qui si conserva il
  riferimento).

## Collegamenti

- `laravel/Modules/Performance/docs/purpose.md` — principale consumatore
- `docs/wiki/rules/migration-morphs-polymorphic.md` — convenzione morph
