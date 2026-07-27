---
title: "corpi metodo duplicati — tema Zero"
type: analysis
module: Zero
tags: [dry, duplication, census, theme, presentation]
created: 2026-07-22
updated: 2026-07-22
qmd: "duplicate method bodies theme Zero zero presentation boundary"

related:
  - ../../../../../../docs/wiki/duplicate-method-bodies-census.md
  - ./method-name-homonyms.md
---

# Corpi metodo duplicati — Theme Zero

## Esito scan

| Metrica | Valore |
|---------|--------|
| Gruppi con corpo identico nel tema | **0** |
| Data scan | 2026-07-22 |

## Perche' zero

Il tema **Zero** e' strato **presentazione** (Blade, CSS, asset): le poche classi PHP
non replicano logica. E' l'esito **atteso e da mantenere**: la business logic vive nei
moduli, il tema non copia metodi di dominio.

| OK nel tema | Da evitare |
|-------------|------------|
| Layout, partial Blade, asset | Model, relazioni Eloquent, calcoli dominio |
| Widget Filament richiamati dai moduli | Copia di metodi da `Modules/*` |

Debito reale: vedi [indice progetto](../../../../../../docs/wiki/duplicate-method-bodies-census.md)
(cluster nei moduli `Sigma`, `Notify`, `Pdnd`, `Performance`, `Progressioni`).

## Rigenerazione

```bash
python3 bashscripts/tools/census-duplicate-method-bodies.py
```
