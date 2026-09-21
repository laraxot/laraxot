---
title: "Xot docs/ frontmatter — riconciliazione ~1092 file con hunk multipli"
status: backlog
module: Xot
created: 2026-09-16
related: ["docs-frontmatter-batch-enrichment-pattern"]
---

## Contesto

Batch enrichment frontmatter su Xot (sessione 2026-09-15/16): 2877 candidati reali trovati,
1785 committati in 4 batch atomici sicuri (commit `8cd32593`, `afa0d46f`, `e50b236c`,
`4a57eb16`). I restanti **~1092 file sono stati scritti su disco ma NON committati**: il
`git diff` per quei file conteneva hunk multipli non isolabili in sicurezza dal working tree
condiviso con altre fork concorrenti attive nella stessa sessione — committarli col diff
grezzo avrebbe rischiato di includere modifiche di altri agenti a mia insaputa.

## Stato

- I file hanno il frontmatter già scritto fisicamente su disco (verificare con
  `git status --short` in `Modules/Xot` — dovrebbero apparire come `M` non ancora aggiunti)
- Nessuna garanzia che il frontmatter generato sia stato verificato (il batch precedente ha
  fatto solo dry-run su un campione iniziale, non su questi 1092)

## Da fare per la ripresa

1. `git status` in Xot per confermare quali file risultano ancora modificati
2. Per ciascuno, isolare l'hunk del frontmatter (non l'eventuale altro contenuto modificato
   da fork concorrenti nel frattempo) — stessa tecnica di parsing usata per i 1785 già
   committati
3. Se il working tree è ormai troppo cambiato per isolare in sicurezza, ri-eseguire lo
   script da zero su questi file specifici invece di tentare un cherry-pick del diff vecchio
4. Commit in batch, stesso schema (`docs(xot): add minimal frontmatter to N files`)
5. Verifica finale: conteggio file senza frontmatter dovrebbe avvicinarsi a 0 (esclusi
   archivio)

## Riferimento

Pattern e script: `Modules/Xot/docs/stories/docs-frontmatter-batch-enrichment-pattern.story.md`
