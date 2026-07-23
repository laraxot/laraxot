---
name: map-fullscreen-issues
description: Problemi di visualizzazione della mappa in fullscreen nel wizard Segnalazione
type: concept
---

# Problemi di Mappa in Fullscreen – Wizard Segnalazione

## Contesto
Nel wizard **Segnalazione** (`/it/tests/segnalazione-crea?step=form.dati-della-segnalazione::data::wizard-step`) la mappa presenta i seguenti comportamenti anomali quando si attiva la modalità fullscreen:

1. **Scrollbar verticale** sopra la mappa, impedendo la visualizzazione completa.
2. Un **box informativo** si sovrappone alla mappa, bloccandone l’interazione.
3. La mappa **non si carica correttamente** (tiles mancanti, visualizzazione parziale).

## Cause note
- Il component Lit `coordinate-picker-lit` non gestisce il ridimensionamento del container al passaggio a fullscreen.
- Stili CSS con `overflow: auto` o `height: 100vh` sul wrapper della mappa generano la scrollbar.
- Il box informativo è inserito tramite `<div class="info-box">` statico che non viene nascosto in fullscreen.

## Correzioni proposte
1. **Rimuovere scrollbar**: impostare `overflow: hidden` e `height: 100%` sul container `.map-wrapper`.
2. **Nascondere box informativo** in fullscreen con media query:
   ```css
   .map-fullscreen .info-box { display: none !important; }
   ```
3. **Trigger resize**: aggiungere un `ResizeObserver` o chiamare `map.invalidateSize()` al cambiamento di fullscreen (evento `fullscreenchange`).
4. **Aggiornare CSS tema** in `filament-wizard-parity.css` per includere le regole precedenti.

## Best practices
- Utilizzare **CSS a livello di componente** (niente selector per pagina).
- Gestire il cambio di stato fullscreen con **event listeners** nel Lit component (`coordinate-picker-lit.js`).
- Verificare che il wrapper non erediti regole di overflow da stili globali.

---
