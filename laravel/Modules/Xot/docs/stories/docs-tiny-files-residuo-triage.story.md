---
title: "Xot docs/ tiny files — 470 rimanenti, cluster xotbase-extension-rules richiede triage manuale"
status: backlog
module: Xot
created: 2026-09-16
related: []
---

## Contesto

Cleanup file `.md` tiny (<5 righe) in Xot (sessione 2026-09-15/16): 814 file trovati totali.
337 rimossi come scaffold anti-pattern (cartelle `_archive/`, `_integration/`, `legacy/`,
`raw/root-import/`, `root-md-files/` — overlap 90%+ fra coppie, regola
`docs/no-ai-tool-scaffold-dirs.md`), 7 rimossi come file a 0 byte in `docs/old_tasks/`.
Commit: `fd84e736`, `e7de94e8`.

**470 file rimasti, non automatizzabili con lo stesso criterio.**

## Cluster critico: xotbase-extension-rules

16 varianti duplicate dello stesso contenuto (`UPPERCASE`/`snake_case`/`kebab-case`/`-1`/`-2`/
`-variant`/`-conflict`/`-comprehensive`) sparse fra `docs/`, `docs/historical/`,
`docs/wiki/consolidated/`. Richiede identificare **quale versione è quella viva** prima di
eliminare le altre — non è uno scaffold vuoto, ha contenuto reale che potrebbe divergere fra
varianti.

## Da fare per la ripresa

1. `diff` a coppie fra le 16 varianti — capire se sono identiche o divergenti nel contenuto
2. Se identiche: tenere quella in `docs/` (canonica), eliminare le altre 15
3. Se divergenti: leggere ognuna, capire quale riflette lo stato attuale del codice
   (verificare contro `HasXotTable.php` reale), consolidare in una sola versione aggiornata
4. Audit dei restanti 470 - 16 = ~454 file tiny per altri cluster simili prima di procedere
   file-per-file

## Nota

Questo NON è un task automatizzabile come lo scaffold cleanup già fatto — richiede lettura e
giudizio umano (o agente con contesto) su quale versione è autorevole.
