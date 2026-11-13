---
title: "i18n del tema Sixteen: indice"
type: index
theme: Sixteen
created: 2026-10-13
updated: 2026-10-13
qmd: "i18n multilingua indice lingua locale it en de es glossario fallback selettore lingua"
status: draft
tags: [docs, sixteen, i18n]
---

# i18n del tema Sixteen

Il sito serve `it`, `en`, `de`, `es` (`laravel/config/laravellocalization.php`). Questa cartella dice come funziona la lingua e come si traduce.

1. [00-comportamento-multilingua.md](00-comportamento-multilingua.md): come si sceglie e si dichiara la lingua, fallback, selettore, metadati, link interni, date e numeri, difetti noti con file e riga.
2. [01-glossario.md](01-glossario.md): registro, termini della PA, cosa non si traduce, come aggiungere una lingua.

Story: [DCP-120 copertura](../bmad/stories/dcp-120-multilingua-comportamenti-e-copertura.story.md), [DCP-123 lingua dichiarata e link localizzati](../bmad/stories/dcp-123-lingua-dichiarata-e-link-localizzati.story.md).

Guardia automatica: `laravel/Modules/Fixcity/tests/Unit/ContentLocaleParityTest.php`. Audit sul sito acceso: `bashscripts/docs/i18n-locale-audit.md`.
