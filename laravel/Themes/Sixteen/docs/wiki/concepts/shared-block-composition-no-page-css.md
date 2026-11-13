---
qmd: "pagina composizione blocchi condivisi content-page nessun css di pagina data-page data-dc tailwind inerte cmp"
issues: []
discussions: []
title: "Pagine = composizione di blocchi condivisi, nessun CSS di pagina"
type: concept
module: Sixteen
tags: [css, design-comuni, content-page, data-dc, tailwind, agent-guardrail, faq]
created: 2026-10-20
updated: 2026-10-20
related:
  - ../../bmad/stories/dcp-260-faq-composizione-blocchi-condivisi.story.md
  - ../../bmad/faq-route-ownership.md
  - ../../page-directory-structure.md
  - ../../bmad/decision-log.md
status: active
---

# Pagine = composizione di blocchi condivisi, nessun CSS di pagina

## Regola

1. Una pagina reale è un **JSON CMS** che compone blocchi condivisi. Nessuna view Folio per pagina, nessun blocco monolitico.
2. Il CSS si riusa **per componente** (`cmp-*`, `card-wrapper`, `it-hero-*`…), mai per pagina: vietati `main[data-page="x"]`,
   `body.dc-x-parity`, `.page-content[data-slug="x"]` per pagine reali, `<style>` inline di pagina, JS che aggiunge classi di pagina.
3. Un comportamento nuovo si aggiunge **al componente condiviso** con un flag opzionale nel JSON (senza flag il markup non cambia).

## Perché (misurato 2026-10-20)

- **Il riferimento lo fa così.** `italia/design-comuni-pagine-statiche`: `src/pages/sito/domande-frequenti.hbs` assembla otto partial
  `cmp-*`; ogni componente ha il proprio `src/components/cmp-*/_cmp-*.scss`; nessuna regola nomina una pagina.
- **Su pagine `data-dc` le utility Tailwind sono spente.** La shell `pages/[container0]/index.blade.php` mette sempre `data-dc` e
  `postcss/isolate-legacy-important.cjs` emette ogni utility come `.x:not(:where(body:has(.page-content[data-dc]), body:has(.page-content[data-dc]) *))`.
  Esempio reale: `text-xl`, `max-w-[720px]`, `px-4`, `h-8 w-8` erano nel CSS servito ma `getComputedStyle` dava 40px, 1280px, 0×0.
  Su `data-dc` si usano le classi Design Comuni già definite dal tema. Il grep della classe **non** è una prova.
- **Il CSS di pagina è peso morto.** `faq-parity.css`: 700 righe / 143 selettori sotto `dc-faq-parity`, classe mai impostata (JS disattivato),
  138 occorrenze nel bundle servito. Rimosso nella DCP-260.
- **Composizione già pronta = parity alta gratis.** `/it/tests/domande-frequenti` (composta) 99,31% HTML vs riferimento; la stessa pagina scritta a mano 11,59%.

## Come si compone una pagina

JSON `pages/<slug>/index.json`, per lingua, un solo blocco:

```json
{ "type": "content-page", "data": {
  "view": "pub_theme::components.blocks.content-page.design-comuni",
  "title": "…", "breadcrumb": {"items": [{"label": "Home", "url": "/it"}], "current": "…"},
  "hero": {"text": "…", "shadow": false},
  "sections": [ {"type": "search", …}, {"type": "accordion", …} ],
  "feedback": {…}, "contacts": {…} } }
```

## Contratto del blocco `content-page/design-comuni`

| Parte | Chiavi | Note |
|---|---|---|
| `breadcrumb` | `items[{label,url}]`, `current`, `class?` | partial `content-page/partials/breadcrumb` |
| `hero` | `text`, `shadow?` | titolo = `data.title` |
| `sections[].style` | `plain` (default, `container py-5`), `bare` (`container`), `grey` (fascia `bg-grey-card`) | `sections[].id?` |
| `sections[].type` | `search`, `accordion`, `search-list`, `cards-simple`, `cards-document`, `grid-overlay` | nuovo tipo = nuovo file in `content-page/sections/` |
| `search` | `id,name,label,placeholder,button_id,button,spacing?,result?`, **`filter?`** | `filter` = `accordion_id` da filtrare mentre si scrive (evento `list-filter`) |
| `accordion` | `accordion_id,col_class,more{col_class,label}`, `items[{id,question,answer}]` **oppure `source`** | `source: "faq"` → `ListPublishedFaqsAction`; `visible` (prime N, "carica altre" mostra il resto), `filterable`, `empty` (testo senza risultati) |
| `feedback` | `title,legend,star_label(%d),thanks,positive{question,answers[]},negative{…},detail{question,label,help},back,next,col_class` | partial `dc-rating` |
| `contacts` | `main{title,items[{icon,label,url}]}`, `problems{…}` | partial `dc-contacts`; `icon` = id dello sprite (`it-search`, `it-user`, `it-map-marker-circle`) |

Senza `visible`/`filterable`/`filter`/`source` il markup è quello statico del riferimento (replica pixel-parity invariata).

## Verifica (non dedurre dalla classe)

```bash
# 1. quale regola vince davvero (browser): getComputedStyle + scorrere document.styleSheets per selettore, !important e foglio
# 2. parity HTML main vs riferimento
node bashscripts/tools/visual/compact-markup.mjs <url> --tag main --out /tmp/x.html
node bashscripts/tools/visual/compare-body-html.mjs /tmp/ref.html /tmp/x.html
# 3. struttura senza testo: confrontare la sequenza tag+classe (la copy di dominio abbassa solo il numero 2)
```

Test guardiani: `FaqPageCompositionContractTest` (composizione + nessun CSS/JS che nomini la pagina), `NoSemanticFolioPageDirectoriesTest`
(nessuna cartella né file Folio omonimo), `FolioPageMountContractTest` (shell).

## Anti-pattern

`faq-page`-style blocchi monolitici · `pages/<slug>/` o `pages/<slug>.blade.php` · `parity_page` nel JSON per agganciare CSS ·
utility Tailwind nel markup di una pagina `data-dc` · copy nelle view invece che nel JSON di lingua · un test CSS che non è mai stato visto fallire.

Folio 1.2.0 (`Router::matchAtPath`): `MatchLiteralDirectories` precede `MatchWildcardDirectories` precede `MatchLiteralViews`: la **cartella**
omonima intercetta l'URL; un **file** omonimo perde contro `[container0]` ma resta una seconda sorgente morta.
