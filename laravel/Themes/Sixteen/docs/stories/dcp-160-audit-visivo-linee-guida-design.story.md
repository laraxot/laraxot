---
type: story
tags: [designers-italia, linee-guida-design, compliance]
created: 2026-10-14
updated: 2026-10-14
qmd: "linee guida design AGID CAD art 71 audit visivo"
issues: []
discussions: []
id: "2026-10-14-dcp-160"
epic: 160
title: "Audit visivo aderenza Linee guida di design (DCP-160)"
slug: audit-visivo-linee-guida-design
status: ready-for-dev
scope: theme:Sixteen
github:
  repository: https://github.com/laraxot/module_gdpr_fila5
  issues: https://github.com/laraxot/module_gdpr_fila5/issues/60
  discussions: https://github.com/laraxot/module_gdpr_fila5/discussions/61
  issue_status: "aperta"
related:
  - ../docs/stories/dcp-159-linee-guida-design-audit.story.md
  - ../docs/stories/dcp-156-pagine-legali-complete-a-norma.story.md
---

# 2026-10-14 DCP-160 Audit visivo aderenza Linee guida di design

## Obiettivo

Verificare visivamente che il tema Sixteen rispetti le Linee guida di design per i servizi digitali della PA (CAD ex art. 71).

## Dimensioni da auditare

1. **Cittadini al centro** — flussi chiari, linguaggio semplice, nessuna barriera.
2. **Accessibilità** — WCAG 2.1 AA, keyboard navigation, contrast, ARIA.
3. **Architettura dell'informazione** — IA coerente, breadcrumb, footer standard.
4. **Linguaggio** — 6º grado, elenchi, esempi, nessun gergo.
5. **UI Kit** — Bootstrap Italia vs Tailwind+Alpine+Lit+DaisyUI+Flowbite (gap noto).
6. **Cookie bar** — posizionamento, 3 fasi, testo corretto, link alla cookie policy.

## Accettazione

1. Report audit con punti di forza e gap.
2. Cookiebar: posizionamento fisso in basso, 3 fasi (accetta/rifiuta/preferenze), link cookie-policy.
3. Nessun file creativo in `app/Http/Controllers`.