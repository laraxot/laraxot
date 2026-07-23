---
title: no domain css classes in theme
type: concept
theme: sixteen
updated: 2026-05-29
related:
  - ../../../../../../docs/wiki/concepts/theme-as-vestito-philosophy.md
  - ./theme-page-shell.md
---

# Sixteen — classi CSS presentation-only

## Regola

Il tema non conosce il dominio Fixcity. In HTML/CSS del tema:

| Vietato | Alternativa |
|---------|-------------|
| `class="ticket-elenco"` | `#main-container` + `cmp-*` |
| `class="ticket-list"` | `#main-container .fi-tabs` in CSS |
| `class="ticket-layout"` | `row align-items-start gx-4` |
| `segnalazione-dettaglio-page` | `cms-detail-page` (sandbox test) |

## Esempio CSS (Filament tabs)

```css
#main-container .fi-tabs { ... }
```

Non `.ticket-list .fi-tabs`.

## Verifica

```bash
bash bashscripts/ai/check-domain-classes-in-theme.sh
```
