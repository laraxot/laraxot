---
title: "Panels Filament (SRC) vs Themes Laraxot"
type: concept
tags: [themes, filament-panels, gestionale-commesse, fo]
created: 2026-07-23
updated: 2026-07-23
qmd: "Themes panels Operator Admin Folio gestionale_commesse Sixteen Zero"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./directory-structure-rules.md"
  - "./copy-normalize-modules-vs-themes.md"
  - "../../../docs/gestionale-commesse-comparison/replica-singular-phase.md"
  - "../../../docs/gestionale-commesse-comparison/module-mapping.md"
  - "../Sixteen/docs/gestionale-panels-vs-themes.md"
  - "../Zero/docs/gestionale-panels-vs-themes.md"
---

# Panels Filament (SRC) vs Themes Laraxot

> **Perché:** gestionale_commesse **non** ha package Themes. L’UX è multi-panel Filament. Noi: **moduli replica** (BO) + **Themes** (FO).  
> Fase: [replica-singular-phase.md](../../../docs/gestionale-commesse-comparison/replica-singular-phase.md) — BL nei moduli singolari, non nei temi.

## SRC — panel (estratto)

| Panel provider | Ruolo business |
|---|---|
| AdminPanelProvider | Backoffice gestionale |
| OperatorPanelProvider | Operatore campo (schedule, timbrature, firme) |
| CustomerPanelProvider | Portale cliente |
| SupplierPanelProvider | Fornitori |
| Timber / EnergyBroker / WTS / DDS / AsdSoci / Giveback / Thermo | Verticali brand |

Views FO limitate in `resources/views` (login/timber/wts…) — non Design System temi Laraxot.

## Nostro — Themes

| Theme | Ruolo |
|---|---|
| Sixteen | FO attivo (Design Comuni / BI) |
| Zero / TwentyOne / … | alternativi / sperimentali |
| Meetup, Barthelemy | nicchie |

## Regole di porting UX

1. **Operator flows** SRC (timbratura, firma, sketch) → Filament pages/widgets su panel operator **oppure** FO Folio se pubblico; non blade controller legacy.
2. **Login/register** → User Folio/Volt, non Auth controllers SRC.
3. **Skin verticale** (timber, energy) → token/theme variant o panel id, non nuovo Themes obbligatorio per ogni verticale.
4. CSS/JS di brand → `Themes/<Name>/` secondo [directory-structure-rules.md](./directory-structure-rules.md).

## Gap temi

- Nessun theme SRC da “copiare”: da progettare shell Operator + Customer sul FO/panel nostri.
- Parity visuale = nuovo lavoro UX, non sync file-for-file.
