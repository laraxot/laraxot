---
name: segnalazione-crea-header-and-map-visual-regression-contract
description: Visual regression guardrails for header and map on segnalazione-crea
type: concept
---

# Segnalazione Crea Header And Map Visual Regression Contract

Quality bar for `segnalazione-crea` step 2:

- header nav items remain readable with coherent green styling across `Amministrazione`, `Novita`, `Servizi`, `Vivere il Comune`, `Iscrizioni`, `Estate in citta`, `Polizia locale`, `Tutti gli argomenti`;
- map does not flicker;
- map does not show empty tile blocks;
- fullscreen map does not expose underlying wizard/sidebar overlays.

Verification contract:

- use screenshot capture on real runtime URL (not static mocks);
- inspect computed style for nav link background/text colors;
- inspect tile counts / tile-loaded counts in map container after settling;
- reject changes that improve one axis (e.g. tiles) while regressing another (e.g. flicker).
