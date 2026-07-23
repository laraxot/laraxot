# wizard nav responsive alignment

## Regola di Posizionamento CTA

Per massimizzare l'usabilità su dispositivi mobili e tablet, e garantire che l'azione logica segua immediatamente l'input dell'utente, il tasto **"Avanti"** (o "Invia") deve rispondere a queste specifiche:

1. **Posizione Verticale**: Deve trovarsi **direttamente sotto i campi di input** del modulo (es. sotto le checkbox della privacy o i campi dati).
2. **Layout Mobile/Tablet**:
   - Orientamento: `flex-column`.
   - Allineamento: `stretch` (larghezza 100% dell'area contenuto).
   - Altezza minima: `48px`.
3. **Rationale**: Su schermi piccoli, l'utente finisce di compilare un campo (es. l'ultimo checkbox) e il pollice si trova naturalmente nell'area sottostante. Un bottone steso a tutta larghezza è più facile da intercettare rispetto a un piccolo bottone allineato lateralmente.

## Stato Implementazione

In `ticket-create-wizard.blade.php`, il container `.steppers-nav` avvolge le azioni in un `d-flex flex-column w-100`.

- Se è presente solo "Avanti" (Step 1): Occupa la riga sotto il checkbox.
- Se sono presenti "Indietro" e "Avanti": "Indietro" è in alto (start) e "Avanti" è in basso (stretch).
