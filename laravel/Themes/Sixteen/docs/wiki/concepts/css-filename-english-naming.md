---
title: "CSS filenames — English only"
type: concept
confidence: high
created: 2026-06-04
updated: 2026-06-04
tags: [sixteen, css, naming, i18n]
related:
  - ../../architecture/css-filename-english-naming.md
  - ../../../../../docs/wiki/decisions/css-filenames-english-no-italian.md
  - ../rules/no-italian-component-names.md
---

# CSS filenames — solo inglese

## Regola

I path sotto `resources/css/` non contengono termini italiani (`segnalazione`, `argomenti`, `comuni`, `servizi`, `amministrazione`).

Il riferimento [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche) resta nei **commenti**, non nel filename.

## Mapping canonico

| ❌ | ✅ |
|----|-----|
| `segnalazione-parity.css` | `ticket-parity.css` |
| `argomenti-parity.css` | `topics-parity.css` |
| `design-comuni-*.css` | `civic-design-*.css` |
| `servizi-parity-fix.css` | `services-parity-fix.css` |
| `amministrazione-parity-fix.css` | `administration-parity-fix.css` |

## Verifica

```bash
bash bashscripts/ai/check-italian-names-in-code.sh
```

## Collegamenti

- [Architettura](../../architecture/css-filename-english-naming.md)
- [ADR root](../../../../../docs/wiki/decisions/css-filenames-english-no-italian.md)
