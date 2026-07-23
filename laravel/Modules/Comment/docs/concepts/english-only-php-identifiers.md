---
title: "PHP identifiers — solo inglese nei path e nomi classe"
type: concept
module: Comment
tags: [naming, english, enum, i18n, code-religion]
created: 2026-06-10
updated: 2026-06-10
qmd: "english only PHP filename enum class TipoSottoscrizioneNotifica NotificationSubscriptionType"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/4"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/14"
related:
  - ../../../../docs/wiki/decisions/css-filenames-english-no-italian.md
---

# PHP — nomi file/classe solo inglese

## Regola

| Layer | Lingua |
|-------|--------|
| File `.php`, class, enum, trait, namespace | **Inglese** |
| Label UI, copy FO | `lang/it/` via `__()` |

**Vietato:** `TipoSottoscrizioneNotifica.php`, `InteractsWithCommenti`, `ConfigCommenti`.

## Caso NotificationSubscriptionType

- **Prima (merda):** `Enums/TipoSottoscrizioneNotifica.php` — case `Nessuna`, `Partecipante`…
- **Dopo:** `Enums/NotificationSubscriptionType.php` — `Participating`, `All`, `None`
- Traduzioni: `comment::notifications.enum_description_*`

## Checklist

```bash
test ! -f app/Enums/TipoSottoscrizioneNotifica.php
test -f app/Enums/NotificationSubscriptionType.php
bash bashscripts/ai/check-italian-names-in-code.sh
```
