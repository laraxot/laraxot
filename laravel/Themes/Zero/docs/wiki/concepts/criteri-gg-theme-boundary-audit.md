---
title: "Tema Zero — niente formule gg duplicate (boundary audit)"
type: concept
module: Zero
tags: [theme, boundary, gg, criteri-esclusione, dry]
created: 2026-07-22
updated: 2026-07-22
qmd: "theme zero no duplicate gg formula criteri esclusione materialised fields A_config"
related:
  - ./gg-integ-params-no-asz-theme-boundary.md
  - ./method-name-homonyms.md
  - ../../performance-actions-reference.md
  - ../../../../Modules/Ptv/docs/wiki/concepts/criteri-esclusione-action-taxonomy.md
  - ../../../../Modules/Sigma/docs/wiki/concepts/scheda-gg-getter-duplication-audit.md
  - ../../../../Modules/Progressioni/docs/wiki/concepts/duplicate-method-bodies.md
  - ../../../../Modules/Ptv/docs/wiki/concepts/ptv-leaf-dependency-direction.md
  - ../../../../Modules/Ptv/docs/wiki/concepts/base-criteri-precedenza.md
  - ../../../../Modules/Ptv/docs/wiki/concepts/base-valutatore.md
  - ../../../../Modules/Progressioni/docs/wiki/concepts/no-services-queueable-actions.md
---

# Boundary tema: campi gg materializzati

## Scopo

Dopo l’audit moduli (Ptv/Sigma/Progressioni): il tema **non** è il posto dove “sistemare” duplicati di calcolo.

## Cosa resta nei moduli (non nel tema)

| Tipo | Dove | Tema |
|------|------|------|
| Action criterio `MinGg*` / `Lista*` | Ptv | solo eventuale label/vista |
| Accessor `gg_*` / `*_dalal` | Sigma | mostra valore già salvato |
| Fillable/colonne `schede` | Progressioni | n/a |
| Legacy `TrovaEsclusiAction` Progressioni | Progressioni | n/a |

## `MinGg*` = A_config (non consolidare nel tema né nei moduli)

Corpi *simili* ma campi diversi → **A_config**, non B_business. Il tema mostra valori materializzati; non unisce né ricalcola. Dettaglio: [duplicate-method-bodies Progressioni](../../../../Modules/Progressioni/docs/wiki/concepts/duplicate-method-bodies.md) · [tassonomia Ptv](../../../../Modules/Ptv/docs/wiki/concepts/criteri-esclusione-action-taxonomy.md).

## Replica “giusta” vs “sbagliata” lato UI

- **Giusto:** due TextInput Filament per `gg_integ_params` e `gg_integ_params_no_asz` (due campi config/UI).
- **Sbagliato:** Blade che ricalcola `integ − asz` o confonde esperienza con integ no_asz.

## Collegamenti

- [gg-integ theme boundary](./gg-integ-params-no-asz-theme-boundary.md)
- [performance-actions-reference](../../performance-actions-reference.md)
- [Ptv taxonomy](../../../../Modules/Ptv/docs/wiki/concepts/criteri-esclusione-action-taxonomy.md)
- [Progressioni duplicate bodies (A vs B)](../../../../Modules/Progressioni/docs/wiki/concepts/duplicate-method-bodies.md)
- [Sigma getter audit](../../../../Modules/Sigma/docs/wiki/concepts/scheda-gg-getter-duplication-audit.md)
