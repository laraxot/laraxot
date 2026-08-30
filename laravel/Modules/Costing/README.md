# Costing

Centri di lavoro e ricarichi condivisi tra i moduli di preventivazione.

## Perché esiste

Signage e MetalWork avevano fatto crescere ciascuno la propria tabella di centri
di lavoro, con le stesse colonne e la stessa aritmetica del ricarico duplicata.
Il modulo tiene una sola tabella (`costing_work_centers`) separata per
`context_code`, e una sola implementazione della regola di risoluzione del
ricarico.

## Cosa contiene

- `Models\WorkCenter` — dove si esegue una riga di distinta e a che margine si
  vende. Interrogarlo **sempre** con `forContext()`: la tabella è condivisa e una
  lettura senza filtro mescolerebbe le officine di due aziende diverse.
- `Support\MarkupResolver` — sceglie il ricarico da un elenco ordinato di
  candidati, dal più specifico al più generico: vince il primo non nullo.
  I livelli li decide il chiamante (le insegne risolvono voce → centro → listino →
  config, la carpenteria preventivo → centro → config); quello che non cambia è
  la regola e l'aritmetica.
- `Datas\MarkupDecision` — la percentuale **e** da dove arriva. La provenienza
  viaggia col numero perché all'operatore mostriamo il calcolo di ogni riga e deve
  poter distinguere «il centro di lavoro dice 40%» da «non l'ha detto nessuno,
  quindi 0%».

## Nota sul modello

Uno zero impostato di proposito non è la stessa cosa di un valore assente:
`firstOf()` si ferma sul primo candidato **non nullo**, quindi un ricarico a zero
deciso sulla voce vince sul 40% del centro di lavoro. È voluto.
