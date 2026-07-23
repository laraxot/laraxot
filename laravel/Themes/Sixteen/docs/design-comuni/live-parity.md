# Live Parity Audit 2026-04-04

## Scope
Pagine verificate con screenshot live e confronto strutturale aggiornato:
- `lista-risorse`
- `amministrazione`
- `ticket-list`

## Evidence
- [Lista Risorse screenshot diff](../pages/lista-risorse/VISUAL-COMPARISON-DETAILED.md)
- [Amministrazione screenshot diff](../pages/amministrazione/VISUAL-COMPARISON-DETAILED.md)
- [Segnalazioni Elenco screenshot diff](../pages/ticket-list/VISUAL-COMPARISON-DETAILED.md)
- [Live body parity report](./LIVE_BODY_PARITY_REPORT.md)
- [Script doc](../../../bashscripts/docs/design-comuni-live-body-parity.md)

## Current findings

### lista-risorse
- Dopo il fix CSS/JS le card mostrano immagini e il blocco `Esplora tutte le novità` è visivamente molto più vicino al reference.
- Restano differenze nella parte bassa: feedback/contact band non ancora identica e contenuti di supporto inferiori non ancora allineati al reference.
- Questo slug è adesso un buon candidato per ulteriori rifiniture CSS/JS.

### ticket-list
- Il layout è abbastanza vicino per continuare con CSS/JS: filtri, tabs, mappa, CTA e rating sono presenti e leggibili.
- Il fix ha compattato il rating e migliorato la resa della mappa.
- Restano differenze di spaziatura e densità nei blocchi superiori e nelle cards contatto/problemi.

### amministrazione
- Il delta principale non è CSS: nel reference esistono sezioni `In evidenza`, griglia esplorazione, rating e contatti che nella pagina locale attuale non esistono nel contenuto reso.
- CSS/JS possono solo rifinire hero, spacing e cards correnti.
- Per arrivare a parità reale serve intervenire su Blade/JSON, non solo sugli asset.

## Changes applied in this pass
- `resources/css/app.css`: override mirati per `lista-risorse`, `ticket-list`, `amministrazione`.
- `resources/js/app.js`: fallback immagini per `lista-risorse` e correzione del key sintatticamente invalido `all-services`.
- `bashscripts/design-comuni/live-body-parity.mjs`: nuovo audit live del body reale corrente.

## Decision
- Continuare il lavoro CSS/JS su `lista-risorse` e `ticket-list`.
- Trattare `amministrazione` come blocker strutturale fino a quando non vengono riallineati i blocchi sorgente.
