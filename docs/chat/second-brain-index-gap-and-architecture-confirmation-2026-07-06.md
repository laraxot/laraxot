---
title: "Second brain — index/ mancante creato + conferma architettura — 2026-07-06"
type: chat
created: 2026-07-06
updated: 2026-07-06
tags: [second-brain, qmd, llm-wiki, multi-agent]
related:
  - ./second-brain-qmd-cache-bug-2026-07-06.md
---

# Second brain — index/ mancante + conferma architettura — 2026-07-06 (notte)

Continua da [second-brain-qmd-cache-bug-2026-07-06.md](./second-brain-qmd-cache-bug-2026-07-06.md).

## Cosa ho verificato

Ricerca esterna sul pattern "LLM Wiki / second brain" (Karpathy, 2026) per
capire se l'architettura di questo repo è allineata alle best practice
correnti: **sì**, quasi esattamente — separazione raw/wiki, index.md,
log.md, taxonomy concepts/entities/sources/comparisons, CLAUDE.md come
"agent behavior spec", qmd come layer di ricerca ibrida (BM25 + vettoriale)
invece di RAG a chunk. Nessuna azione di ristrutturazione necessaria.

`bash bashscripts/tools/audit-wiki-junctions.sh` segnalava un unico warning:

```
⚠️  docs/wiki/index/ dovrebbe essere directory reale con llm-wiki.md
```

`docs/wiki/index/` non esisteva affatto. Creato con
`docs/wiki/index/llm-wiki.md` — mappa di lettura del second brain (SSoT,
junction, qmd, gap noti, regola dual-writing memory+wiki). Audit ora al
100%: tutte le junction root conformi.

## Conferma: `qmd update` non indicizza `docs/wiki/index/`

Verificato con `qmd update` dopo la creazione — `0 new` file indicizzati.
La collection `wiki` di qmd punta esplicitamente a `bashscripts/ai/wiki/`
(non a `docs/wiki/`), e `docs/wiki/index/` è l'unica cartella reale locale
sotto `docs/wiki/` (per design, vedi `wiki-sacred-structure-rule.md`) — non
essendo una junction verso il SSoT, resta fuori dall'indice qmd. **Non è un
bug**: `index/` è pensato per lettura umana/agente diretta (mappa di
navigazione), non per essere ricercato semanticamente come "conoscenza".

## Non toccato (lock attivi di altri agenti)

- `bashscripts/docs/llm-wiki-qmd.sh` — lock presente (21:33), bug
  `XDG_CACHE_HOME` già diagnosticato nella nota precedente, fix non ancora
  applicato al momento di questa nota.
- `docs/wiki/log.md` — lock presente (21:33).
- `qmd embed` — processo attivo in background (PID visto: node qmd embed,
  partito 21:40), 618/5058 vettori al momento di questa nota. Non duplicato.

## Stato finale second brain (root) a fine sessione

- Junction: 100% conformi (`audit-wiki-junctions.sh` verde).
- Indice qmd: 5058 file, ricerca lessicale (`qmd search`) pienamente
  funzionante; ricerca ibrida/vettoriale (`qmd query`) parziale finché
  l'embedding in background non completa.
- Wiki di modulo/tema: confermate "locali autonome" per design, non
  cercabili via qmd senza una collection dedicata — chi vuole ricerca
  semantica su `Modules/*/docs/wiki/` può aggiungere una collection con
  `qmd collection add laravel/Modules --name modules-wiki --mask "**/docs/wiki/**/*.md"`
  (non eseguito in questa sessione, proposta da valutare).

— Claude (`claude-sonnet-5`)
