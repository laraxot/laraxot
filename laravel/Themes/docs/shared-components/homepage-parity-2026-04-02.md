# Homepage Parity 2026-04-02

## Scope
Confronto tra:
- reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
- locale: `http://127.0.0.1:8000/it/tests/homepage`

Vincoli applicati:
- niente Bootstrap Italia runtime
- solo Tailwind CSS + Alpine.js
- in questa iterazione ho lavorato solo su CSS del tema `Sixteen`
- pagina locale generata da `resources/views/pages/tests/[slug].blade.php` + `config/local/fixcity/database/content/pages/tests.homepage.json`

## Stato HTML body
La struttura del `body`, esclusi gli script, e' sostanzialmente allineata alla reference.

Macro-sequenza rilevata in entrambe:
1. `div.skiplink`
2. `header.it-header-wrapper`
3. `main`
4. hero `#head-section`
5. governance + eventi `#calendario`
6. argomenti/siti tematici `.evidence-section`
7. ricerca/link utili `.useful-links-section`
8. feedback `.bg-primary`
9. contatti `.bg-grey-card`
10. search modal `#search-modal`
11. `footer#footer`

Differenza strutturale da tenere tracciata:
- nel blocco hero locale esiste ancora un nodo extra `div.cmp-search`, assente nella reference. Attualmente e' nascosto via CSS per non alterare la resa.

Conclusione:
- match strutturale del `body` superiore al 90%
- le differenze residue piu' importanti sono visive, non di gerarchia HTML

## Screenshot
- reference: `docs/screenshots/reference-homepage-20260402.png`
- locale dopo fix read-more governance: `docs/screenshots/local-homepage-readmore-fix.png`
- locale dopo secondo batch visuale: `docs/screenshots/local-homepage-after-batch2.png`

## Script di supporto
- [inspect-readmore.mjs docs](../../../../bashscripts/docs/inspect-readmore-tool.md)
- script: `../../../../bashscripts/inspectors/inspect-readmore.mjs`

## Fix eseguiti in questa sessione
File toccato:
- `laravel/Themes/Sixteen/resources/css/app.css`

Fix confermati:
- corretto `VAI ALLA PAGINA` nelle card governance: colore verde coerente e freccia non piu' spinta a destra
- rifinita la fascia `Argomenti in evidenza`
- compattati ricerca, feedback e contatti
- build e copy eseguiti con successo

Comandi eseguiti:
```bash
cd laravel/Themes/Sixteen
npm run build
npm run copy
```

## Differenze visive ancora aperte
1. hero: il rapporto visivo testo/immagine e' ancora vicino ma non identico
2. evidence section: il bilanciamento tra area verde, area bianca e banda blu destra va ancora rifinito
3. footer: la spaziatura generale e il peso tipografico sono vicini ma non perfetti
4. immagini `picsum.photos`: non sono stabili tra reference e locale, quindi non sono un indicatore affidabile di parity

## Prossimo step consigliato
1. rifinire hero e evidence section con un terzo batch CSS
2. rieseguire screenshot full-page
3. aggiornare questo report con delta residuo

## Link bidirezionali
- [Bashscripts docs index](../../../../bashscripts/docs/index.md)
- [Bashscripts inspect-readmore docs](../../../../bashscripts/docs/inspect-readmore-tool.md)
- [Theme docs README](./README.md)
- [Analisi precedente](./HOMEPAGE-VISUAL-ANALYSIS.md)
- [Report precedente](./HOMEPAGE-CSS-JS-FIXES-COMPLETE.md)
