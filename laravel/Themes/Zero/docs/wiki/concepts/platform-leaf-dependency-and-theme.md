---
title: "Direzione dipendenze piattaforma/leaf — vista tema Zero"
type: concept
module: Zero
tags: [architecture, dependency, theme, presentation, ptv]
created: 2026-07-22
updated: 2026-07-22
qmd: "theme Zero dependency direction platform leaf presentation no domain import"
related:
  - ../../../../../docs/wiki/rules/ptv-leaf-dependency-direction.md
  - ./module-directory-structure-boundary.md
  - ./duplicate-method-bodies.md
---

# Direzione dipendenze e tema Zero

## Legge (vista tema)

La gerarchia del progetto è: **Xot → Ptv (piattaforma) → leaf (Progressioni, Indennita*, Performance)**.
Il fiume scorre in una sola direzione: il leaf beve da Ptv, Ptv non beve dal leaf.

Il **tema** sta fuori dal fiume: è strato **presentazione** (Blade, CSS, asset).

| OK nel tema | Vietato nel tema |
|-------------|------------------|
| Layout, partial, asset, widget Filament richiamati dai moduli | `use Modules\<Dominio>\Models\...` |
| Chiamare componenti esposti dai moduli | Business logic / relazioni / calcoli dominio |
| — | Copiare metodi da Ptv/Progressioni nel tema |

## Perché

Se il tema importasse modelli di dominio, diventerebbe un "leaf mascherato" e ogni refactor
piattaforma/leaf romperebbe la UI. Il confine tema=presentazione è ciò che rende
sicure operazioni come lo spostamento CriteriPrecedenza → Ptv (2026-07-22): zero impatti sul tema.

## Canon

- [Legge completa](../../../../../docs/wiki/rules/ptv-leaf-dependency-direction.md)
- [Pattern Base* (Ptv)](../../../../Modules/Ptv/docs/wiki/concepts/base-criteri-precedenza.md)
- [Confine struttura tema](./module-directory-structure-boundary.md)
