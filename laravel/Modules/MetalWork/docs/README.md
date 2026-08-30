# MetalWork

Preventivi di carpenteria metallica a partire dai disegni CAD.

## Il problema che risolve

Un pezzo di carpenteria si preventiva pesando l'acciaio e sommando le lavorazioni.
Farlo a mano su un disegno vuol dire aprire il CAD, misurare gli ingombri, stimare
quanto materiale serve e ricordarsi a che prezzo il fornitore ha fatturato l'ultima
volta. Il modulo fa i primi tre passaggi da solo e tiene il quarto aggiornato
leggendo le fatture fornitore.

## Flusso

1. **Import STEP** — il file viene letto in streaming (non è un mesh: un STEP è una
   rappresentazione a contorno, quindi il volume esatto non è calcolabile senza un
   tassellatore). Si ricava l'ingombro, il numero di solidi, i cilindri (fori) e se
   il pezzo è un assieme.
2. **Distinta** — se i solidi si distinguono, il pezzo si costa riga per riga: ogni
   riga è un acquisto reale a un prezzo fatturato. Altrimenti si ricade sul percorso
   a pezzo singolo, dove il peso viene dall'ingombro per un fattore di riempimento
   che l'operatore può correggere a schermo — un numero che può verificare sul
   disegno, invece di una cifra a scatola chiusa.
3. **Costo e ricarico** — il ricarico si risolve con `Costing\Support\MarkupResolver`:
   preventivo → centro di lavoro → default di sistema, vince il primo impostato.
4. **Preventivo** — la stima diventa un preventivo del modulo Quotation.

## Import fatture fornitore

I PDF delle fatture vengono letti per tenere aggiornato il listino: una materia prima
fatturata l'ultima volta mesi fa sta preventivando a un prezzo che oggi nessuno
onorerebbe, e la dashboard lo dice.

## Pannello

Vive su `/metalwork`, con dashboard propria: chi preventiva pezzi ci lavora tutto il
giorno e non deve passare dal pannello generale.
