---
title: rules-skills-compaction-on-demand
created: 2026-07-27
---

# Compattazione `.agents/rules/` e `.agents/skills/` — bridge on-demand

## Richiesta utente

`bashscripts/ai/.agents/skills` e `bashscripts/ai/.agents/rules` vanno sintetizzate,
rese più compatte/efficienti/efficaci, e trasformate in bridge on-demand verso il
second brain LLM wiki (`bashscripts/ai/wiki/rules/`, già indicizzato da qmd).

## Scala del problema (misurata 2026-07-27 ~16:40)

- `rules/`: 1123 file (907 `.mdc`, 216 `.md`), **14MB**. 130 `.mdc` con
  `alwaysApply: true` = 673KB sempre caricati come "project instructions" a ogni
  turno; i 212 `.md` legacy senza frontmatter (78KB) risultano ANCH'essi sempre
  caricati (osservato empiricamente: erano tutti nel primissimo system prompt di
  questa sessione, prima di qualunque mia azione).
- `skills/`: 631 directory, **42MB**. Le skill sono già "on demand" per come
  funziona il tool Skill (solo `description` breve è sempre visibile, il body
  carica solo se invocata) — priorità diversa da rules, non urgente allo stesso modo.

## Pattern bridge già validato (6 file preesistenti prima di oggi)

```md
# {Titolo} — bridge on-demand

**Contenuto canonico**: [docs/wiki/rules/{slug}.md](../../../../docs/wiki/rules/{slug}.md)

Questo file è solo un bridge. Non pre-caricare: leggi la fonte canonica solo quando il
task lo richiede (vedi [Trigger Map](../../../../docs/wiki/rules/00-TRIGGER_MAP.md)).
```

`docs/wiki/` a root è un symlink verso `bashscripts/ai/wiki/` — stesso identico
contenuto fisico, due path di accesso.

## ⚠️ Trovato in corso (agente concorrente, non io): bulk-bridging di `rules/`

Tra un check e l'altro in questa sessione: file già-bridged passati da 6 a 209 in
pochi minuti — un altro agente sta processando `rules/` in blocco con lo stesso
pattern sopra. **Non ho duplicato lo sforzo** — ho fermato il mio script dopo la
dry-run per questo motivo.

## ⚠️ Problema serio scoperto durante la dry-run: `wiki/rules/` è già pieno di duplicati

Esempio concreto (`BLADE_COMPONENTS_RULES.mdc`, contenuto NON ancora bridgeato al
momento del check): in `wiki/rules/` esistono **18 file diversi** sull'argomento
"blade components", con naming incoerente e spesso senza estensione `.md` valida:

```
blade-components-rule
blade-components-usage.mdc
BLADE_COMPONENTS_RULES.mdc          <- stesso nome esatto del file sorgente
blade-components-usage-mdc.old
blade-components.rule
blade-components-mdc
blade-component-extraction.md
blade-components-usage-mdc
blade-component-best-practices.mdc
blade-components.mdc
frontend/blade-components-rule       <- stesso set duplicato sotto frontend/
frontend/blade-components-usage.mdc
frontend/blade-components.rule
frontend/blade-components-mdc
frontend/blade-components-usage-mdc
frontend/blade-component-best-practices.mdc
frontend/blade-components.mdc
filament/filament-table-vs-blade-component.mdc
```

`wiki/rules/frontend/` da solo contiene **66 file** che sembrano duplicare
contenuto già presente in `wiki/rules/` root con nomi leggermente diversi.

**Perché è un problema per il bulk-bridging**: uno script che fa match
`slug(file) == wiki/rules/{slug}.md` (come il mio, e probabilmente simile a
quello dell'altro agente) NON troverà `BLADE_COMPONENTS_RULES.mdc` come match per
`BLADE_COMPONENTS_RULES.md` (estensione diversa!) — rischio concreto di creare
un **19° duplicato** invece di consolidare.

## Cosa NON fare

- Non lanciare uno script di bulk-bridge che scrive ciecamente `wiki/rules/{slug}.md`
  senza prima cercare varianti di nome/estensione già esistenti (case, `.mdc` vs
  `.md`, `_` vs `-`, suffissi `.old`/`.rule`, sottocartelle tipo `frontend/`).
- Non cancellare contenuto per "pulire" senza prima confrontare — potrebbe essere
  l'unica copia buona in mezzo a 17 copie scadenti.

## Cosa serve (non ancora fatto da nessun agente, per quanto verificato)

Una vera deduplicazione di `wiki/rules/` (probabilmente centinaia di gruppi come
l'esempio blade-components) PRIMA o IN PARALLELO al bridging di `.agents/rules/` —
altrimenti il bridging si limita a spostare il problema di duplicazione a monte,
non lo risolve.

## Stato del mio contributo in questa sessione

- Fermato il bulk-bridge di `rules/` per il motivo sopra (lasciato all'agente
  concorrente già in corso, non ho toccato file suoi).
- Sposto il mio lavoro su `skills/` (631 dir, 42MB) — priorità diversa, meno
  rischio di collisione, verifico stato e agisco separatamente.

GitHub issue: laraxot/base_workorder_fila5#14

## Aggiornamento — esempio concreto risolto

`wiki/rules/` blade-components: 18 file (11 copie esatte, 6 senza estensione `.md`
valida → invisibili a qmd) consolidati in **1 solo file** `blade-components.md`
(4 sezioni distinte, provenienza tracciata in frontmatter). `blade-component-extraction.md`
lasciato separato (topic distinto, referenziato da TRIGGER_MAP riga 234). Zero perdita
di contenuto — verificato con md5sum prima di cancellare, contenuto univoco di ogni
gruppo copiato integralmente nel merge.

Pattern riutilizzabile per altri cluster duplicati in `wiki/rules/` (ce ne sono
probabilmente molti altri, non ancora auditati — `wiki/rules/frontend/` da solo ha
66 file che sembrano rispecchiare `wiki/rules/` root).
