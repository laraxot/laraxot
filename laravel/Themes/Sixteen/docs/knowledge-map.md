---
title: "Sixteen documentation knowledge map"
type: index
tags: [sixteen, design-comuni, qmd, graphify, second-brain, bmad]
qmd: "sixteen theme documentation knowledge map"
status: draft
updated: 2026-10-11
---

# Mappa della conoscenza di Sixteen

## Scopo

Questa mappa evita che QMD, Graphify, BMAD e gli agenti leggano report storici
come se fossero decisioni correnti. Il codice del tema resta la fonte del
comportamento; questa documentazione è la fonte del perché, dei contratti e
delle verifiche.

## Percorsi canonici

| Area | Percorso | Contiene | Autorità |
|---|---|---|---|
| indice | `00-INDEX.md` | ingresso unico | canonica |
| regole | `wiki/rules/` | vincoli on-demand | canonica |
| architettura | `architecture/`, `bmad/` | decisioni e contratti | canonica |
| design | `design-comuni/`, `design-system/` | token e struttura visuale | canonica |
| pagine | `pages/` | contratto per pagina | canonica |
| storie | `stories/` | BMAD, AC e scope | canonica |
| parity | `visual-parity/`, `html-parity-reports/` | risultati verificati | evidence |
| analisi | `investigation/`, `analysis/` | ipotesi e diagnosi | evidence |
| sorgenti | `sources/`, `raw/`, `Main_files/` | materiale importato | non canonica |
| screenshot | `screenshots/`, `visual-parity-screenshots/` | output binario | non indicizzata semanticamente |

## Regola di lettura

1. Leggere questo file e `00-INDEX.md`.
2. Cercare in QMD per concetto, non per nome file.
3. Recuperare il documento completo con `qmd get`, non usare lo snippet come verità.
4. Se una regola è coinvolta, leggere la regola wiki pertinente.
5. Per una modifica, leggere la story BMAD e il documento parity della pagina.
6. In caso di conflitto prevalgono: codice verificato, regola wiki corrente,
   architecture/BMAD, parity più recente, evidence grezza.

## QMD, Graphify, Second Brain e BMAD

- QMD recupera il contesto locale dalla collezione `sixteen-theme`.
- Graphify indicizza i Markdown e rende visibili collegamenti e duplicati.
- Second Brain conserva il perché e le correzioni durevoli.
- BMAD conserva brief, PRD, architettura, stories e decision log.

I documenti nuovi devono avere front matter YAML con `title`, `type`, `tags` e
`qmd`. I tipi ammessi sono `index`, `rule`, `architecture`, `decision`,
`story`, `parity`, `investigation`, `source` e `archive`.

Non si rinominano in massa i file storici: si aggiungono indici e mappe di
migrazione per non rompere riferimenti già usati dagli agenti.
