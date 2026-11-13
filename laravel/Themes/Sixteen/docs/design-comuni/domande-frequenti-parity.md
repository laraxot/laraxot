---
title: "domande frequenti parity"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-10-20
qmd: "domande frequenti parity"
issues: []
discussions: []
status: draft
---

# Domande Frequenti Parity Report

## Stato

`/it/domande-frequenti` è `domande-frequenti.index`: blocco `content-page`,
ricerca, accordion `source: faq`, parere e contatti. Non esiste
`pages/domande-frequenti/index.blade.php`. Il perché:
[why-domande-frequenti-blade-php-must-not-exist.md](../bmad/knowledge/why-domande-frequenti-blade-php-must-not-exist.md).

`components/pages/faq.blade.php` non è nel JSON del tenant. Un vecchio vestito
a card con sidebar «Categorie» leggeva `$faqs` / `$categories` che nessuno
passa: il riferimento è hero, ricerca, accordion e «Carica altre domande».

Grammatica comune delle pagine studiate (argomenti, argomento,
lista-risorse, lista-risorse-categorie, novità, novità-dettaglio, eventi,
evento-dettaglio, domande frequenti):

| Pagina | Blocco proprio | Chiusura comune |
|---|---|---|
| Liste (argomenti, novità, eventi, lista risorse) | `cmp-hero` + card o ricerca | `cmp-rating` + `cmp-contacts` |
| Schede (argomento, dettaglio novità, dettaglio evento) | corpo + `cmp-navscroll` | `cmp-rating` + `cmp-contacts` |
| Domande frequenti | `cmp-hero` + ricerca + accordion + «Carica altre domande» | `cmp-rating` + `cmp-contacts` |

La sidebar a badge è il blocco delle categorie, non della FAQ. I blog
[AFC Formazione](https://www.afcformazione.it/blog/),
[A. Ferrari](https://www.aferrari.it/articoli/) e
[Asso della vendita](https://www.assodellavendita.it/blog/) confermano lo
scopo: ogni voce risponde a una domanda che una persona scriverebbe nella
ricerca, e la prima frase chiude il dubbio. Per questo le risposte parlano
di segnalazione, tracciamento e area personale, non di lorem ipsum.

Il vestito sta in `components/blocks/content-page/` e nel foglio
`resources/css/app/20-dc-components.css`. Tailwind + Alpine. Bootstrap Italia
è solo riferimento visivo.

## Storico

Data: 2026-04-03

## Obiettivo

Allineare la pagina locale `tests/domande-frequenti` al riferimento Design Comuni lavorando nel tema `Sixteen` e salvando artefatti e verifiche direttamente nella documentazione del tema.

## Differenze strutturali iniziali

- Il `main` locale non era al 90% del riferimento.
- Il riferimento usa: `breadcrumb > hero bianco > search > accordion FAQ > feedback rating > contacts`.
- La pagina locale usava: `breadcrumb > hero scuro > due accordion categoriali > cta`.
- I blocchi `hero`, `search`, `accordion` e `cta` non replicavano il markup AGID della pagina FAQ.

## Interventi applicati

- Riallineato il markup di `breadcrumb/default`, `hero/default`, `search/input`, `accordion/default`.
- Sostituito il blocco `cta` con `feedback/rating` e un nuovo blocco `contacts/faq`.
- Aggiornato il JSON CMS `tests.domande-frequenti.json` per riflettere la struttura della pagina di riferimento.
- Aggiunto scope CSS/JS tramite `main[data-page="domande-frequenti"]`.
- Aggiunti comportamento accordion e filtro live FAQ in `resources/js/app.js`.
- Creato lo script [../../../../bashscripts/design-comuni/faq-body-parity.sh](../../../../bashscripts/design-comuni/faq-body-parity.sh) per salvare il `body` senza script.

## Artefatti

- Screenshot reference/local: `docs/design-comuni/screenshots/faq/`
- Body extracts: `docs/design-comuni/faq-parity/`
- Script docs: [../../../../bashscripts/docs/design-comuni-faq-body-parity.md](../../../../bashscripts/docs/design-comuni-faq-body-parity.md)

## Pass 2 - CSS/JS Visual Refinement

Dopo il secondo confronto screenshot-guided sulle immagini top/full:
- ho consolidato il layer page-scoped `body.dc-faq-parity`
- ho rifinito header, breadcrumb, hero, search, accordion, rating e contacts in `faq-parity.css`
- ho reso il blocco rating piu vicino al riferimento in `domande-frequenti-parity.js`
- ho rilanciato `npm run build` e `npm run copy`

Bundle attivo dopo il pass 2:
- CSS: `app-h1RdzhA5.css`
- JS: `app-_l_wVd3x.js`

Residuo principale:
- il top fold e sensibilmente piu vicino, ma restano differenze fini di spacing/typography rispetto alla reference AGID
- il layer FAQ e ora abbastanza maturo da poter essere rifinito solo per micro-dettagli senza cambiare struttura

## Verifica corrente 2026-04-03

Analisi automatica aggiornata:
- [STRUCTURE-DIFF.md](../pages/domande-frequenti/structure-diff.md)
- [domande-frequenti-structure-comparison.json](../analysis/domande-frequenti-structure-comparison.json)
- [domande-frequenti-element-trees.txt](../analysis/domande-frequenti-element-trees.txt)

Esito corrente:
- parity strutturale misurata sul confronto automatico: `57%`
- quindi la pagina **non** supera ancora il gate operativo del `90%`
- di conseguenza un passaggio limitato a CSS/JS non e sufficiente a garantire parity reale

Differenze strutturali verificate oggi:
- il locale non rende `cmp-hero > section.it-hero-wrapper...` come nel riferimento
- il locale ha meno `container` strutturali (`9` vs `11`)
- la search area diverge anche come numero di `input` e wrapper utili (`2` vs `18` nel conteggio grezzo)
- l'accordion locale espone una struttura diversa e molto piu annotata (`faq-item` `9` vs `1`)
- dalla tree view emerge anche un blocco `.it-footer-main.py-12...` dentro `main`, estraneo al riferimento FAQ

Conclusione operativa:
- prima va riallineata la shell HTML utile della pagina FAQ
- solo dopo ha senso un vero pass CSS/JS di parity

## Pass 3 - Micro-tuning

Terzo pass applicato solo su `faq-parity.css` con override ad alta specificita per ridurre i delta residui nel top fold e nell'accordion.

Bundle attivo dopo il pass 3:
- CSS: `app-DKQCh3XW.css`
- JS: `app-_l_wVd3x.js`
