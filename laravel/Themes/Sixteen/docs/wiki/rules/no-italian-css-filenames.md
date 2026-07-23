---
title: "No Italian CSS filenames"
type: rule
confidence: high
created: 2026-06-04
updated: 2026-06-04
tags: [css, naming, sixten]
related:
  - ../../../../../docs/wiki/decisions/css-filenames-english-no-italian.md
  - ../../architecture/css-filename-english-naming.md
---

# Regola tema — CSS filename in inglese

Stessa religione di [no-italian-component-names.md](./no-italian-component-names.md), applicata a `resources/css/`.

## Checklist

- [ ] Nessun termine italiano nel basename
- [ ] Template PA → prefisso `civic-design-`, non `design-comuni-`
- [ ] Dominio ticket → `ticket-`, non `segnalazione-`

## Verifica

```bash
bash bashscripts/ai/check-italian-names-in-code.sh
```
