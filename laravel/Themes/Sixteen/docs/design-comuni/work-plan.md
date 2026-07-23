# Piano di Lavoro: Homepage Visual Parity

**Data**: 2026-04-02
**Stato**: 🟡 In esecuzione
**Agente**: Codex
**Metodo**: BMAD + GSD, con verifica screenshot-driven
**Condiviso**: Tutti gli agenti AI che lavorano sul tema

---

## Obiettivo

Rendere `http://127.0.0.1:8000/it/tests/homepage` il piu' vicino possibile a
`https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`

Vincoli confermati:
- solo CSS/JS nel tema `Sixteen`
- niente Bootstrap Italia runtime come dipendenza di rendering finale
- mantenere Blade e JSON esistenti salvo verifica strettamente necessaria

---

## Stato reale verificato oggi

| Metrica | Stato reale |
|---------|-------------|
| Struttura `body` senza script | buona, oltre 90% |
| Header/navbar | molto vicini |
| Hero | migliorata, non ancora pixel-identica |
| Blocchi centrali | buoni ma ancora con differenze di spacing/min-height |
| Footer | vicino alla reference |
| Asset serviti | riallineati manualmente dopo build |

---

## BMAD Tracking

### Discover
- completato confronto strutturale `body`
- confermato che le differenze residue sono soprattutto visive
- isolate le aree critiche: hero, card governance, section rhythm

### Design
- scelta confermata: override scoped a `.dc-homepage-parity`
- evitare refactor Blade/JSON in questo pass
- usare screenshot full-page e first viewport come source of truth

### Build
- override aggiunti in `resources/css/app.css`
- normalizzazione leggera immagini in `resources/js/app.js`
- build e copy eseguiti

### Measure
- screenshot salvati in `docs/design-comuni/screenshots/`
- report aggiornati con limiti residui reali
- parity visiva migliorata ma non dichiarata completata

---

## GSD Tracking

### Step completati
- [x] analisi reference e locale
- [x] confronto struttura HTML
- [x] screenshot desktop e full-page
- [x] primo pass CSS/JS
- [x] build `npm run build`
- [x] copy `npm run copy`
- [x] riallineamento manifest/assets realmente serviti
- [x] documentazione tecnica e visuale

### Next loop
- [ ] secondo pass screenshot-driven sulla hero
- [ ] secondo pass sulle teaser card del calendario
- [ ] affinare evidence section e useful links
- [ ] nuova build + nuovi screenshot
- [ ] aggiornare report con delta visivo

---

## File attivi del pass corrente

- `resources/css/app.css`
- `resources/js/app.js`
- `docs/design-comuni/homepage-structure-diff-2026-04-02.md`
- `docs/design-comuni/screenshots/homepage-visual-pass-2026-04-02.md`

---

## Regole operative per agenti paralleli

1. Non dichiarare 100% parity senza screenshot comparativi aggiornati.
2. Non toccare Blade o JSON se il problema e' risolvibile in CSS/JS.
3. Ogni pass deve lasciare screenshot e nota di esito.
4. Se `npm run copy` non aggiorna gli asset effettivamente serviti, verificare `manifest.json` in `public_html/themes/Sixteen`.

---

## Riferimenti

- [homepage-structure-diff-2026-04-02.md](./homepage-structure-diff-2026-04-02.md)
- [screenshots/homepage-visual-pass-2026-04-02.md](./screenshots/homepage-visual-pass-2026-04-02.md)
