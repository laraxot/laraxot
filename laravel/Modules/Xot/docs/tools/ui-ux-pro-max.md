---
title: "UI UX Pro Max — design intelligence skill"
module: "xot"
type: reference
status: approved
tags: [ai, skill, ui, ux, design-system, tailwind, figma, responsive]
created: 2026-08-19
updated: 2026-08-19
qmd: "ui ux pro max skill design system generator colors typography patterns responsive accessibility tailwind figma claude code"
related:
  - "./ponytail.md"
  - "./bmad-method.md"
---

# UI UX Pro Max

> [github.com/nextlevelbuilder/ui-ux-pro-max-skill](https://github.com/nextlevelbuilder/ui-ux-pro-max-skill) — 118k stars

## Scopo

Skill AI per design intelligence: genera design system completi, palette colori,
tipografia, pattern layout — tutto context-aware per il progetto.

## Feature principali (v2.0)

- **Design System Generator** — analizza requisiti e genera sistema completo
- **Pattern matching** — Hero-Centric, Social Proof, Dashboard, ecc.
- **Style intelligence** — Soft UI, Brutalist, Glassmorphism, ecc.
- **Typography pairing** — Google Fonts con mood matching
- **Pre-delivery checklist** — accessibilità, responsive, contrast ratio

## Checklist integrata

- No emoji come icone (SVG: Heroicons/Lucide)
- `cursor-pointer` su tutti gli elementi cliccabili
- Contrasto testo 4.5:1 minimum (light mode)
- Focus states visibili per keyboard nav
- `prefers-reduced-motion` rispettato
- Responsive: 375px, 768px, 1024px, 1440px

## Installazione

Skill per Claude Code / Cursor / Codex. Copiare `SKILL.md` nella cartella skills.

## Integrazione progetto

Utile per i widget Filament custom e il Tema `One/`. Il design system generator
può informare le variabili Tailwind in `tailwind.config.js`.

Già presente come skill locale: `/home/zorin/.agents/skills/ui-ux-pro-max/SKILL.md`
