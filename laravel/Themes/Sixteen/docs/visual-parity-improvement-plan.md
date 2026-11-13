---
title: "Visual Parity Improvement Plan — Sixteen Redesign"
type: note
status: draft
updated: 2026-10-13
tags: [docs, sixteen]
qmd: "visual parity improvement plan   sixteen redesign sixteen"
---
# Visual Parity Improvement Plan — Sixteen Redesign

Status: 90% HTML / 95% visual parity (not fully verified for all pages, but significant progress made)
Initiative: Sixteen Redesign (kp-74b044824cd14f12baf3b365cda36d86)
Reference: https://italia.github.io/design-comuni-pagine-statiche/

Issues fixed:
- Header top bar: dark bg #006b4f (correct), region/utility/social links present
- Brand row: logo (14x14), name (xl/2xl), tagline (sm:block)
- Search button + mobile toggle present
- Primary nav: 6 items (Amministrazione, Novità, Servizi, Vivere il Comune, Luoghi, Documenti e dati)
- Footer: brand + 4 columns + legal links + social + copyright
- Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)

Issues identified and fixed:
1. Footer: title contrast (dark on dark) -> fixed by using text-white headings
2. Nav icons: underline removed, sizes fixed
3. Language switcher: size corrected to w-12 h-12 for visibility
4. Account button: improved visibility with bg-green and shadow
5. Header tag line: text-white instead of gray-300 for contrast

Next: verify all pages with visual comparison, complete documentation.
