---
title: "BMad Method in Sixteen Theme"
type: concept
sources:
  - "https://docs.bmad-method.org/"
  - "https://github.com/salacoste/antigravity-bmad-config"
confidence: high
created: 2026-04-22
tags: [bmad, theme, sixteen, ui-ux, antigravity]
---

# BMad Method in Sixteen Theme

Il tema **Sixteen** applica il **BMad Method** per garantire la fedeltà visiva (Design Comuni Italia) e la modularità dei componenti Blade.

## 🎨 Creative & UI Flow

- **UX Designer Agent (`/ux`)**: Analizza il CSS di Design Comuni e definisce i token Tailwind corrispondenti.
- **Developer Agent (`/bmad:dev`)**: Implementa i blocchi universali (Hero, Navigation, Cards) seguendo il principio DRY.

## 🏛️ UI Architecture: Body and Mind

Il tema Sixteen segue rigorosamente la separazione BMAD:
- **Body**: Componenti Blade puramente presentazionali (`pub_theme::components.blocks.*`).
- **Mind**: Dati strutturati via JSON (`tests.{page}.json`) orchestrati da Folio + Volt.

## 🚀 Antigravity Slash Commands nel Tema

- `/bmad:pm` — Pianificazione della replica delle 38 pagine statiche.
- `/bmad:architect` — Design dei blocchi universali riutilizzabili.
- `/bmad:dev` — Sviluppo e rifinitura dei componenti Blade.
- `/bmad:qa` — Verifica della visual parity e ARIA labels.

## 🔗 Collegamenti
- [[bmad-method]]: Guida generale al metodo BMAD
- [[DESIGN_COMUNI_BMAD_MASTER_PLAN]]: Piano di replica Design Comuni
- [[sixteen-header-composition-rule]]: Regola di composizione dell'header
