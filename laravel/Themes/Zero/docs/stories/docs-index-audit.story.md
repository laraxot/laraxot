---
title: "Docs index audit — Theme Zero"
type: story
status: done
created: 2026-09-03
id: zero-docs-stories-docs-index-audit-story
slug: docs-index-audit-story
description: "Audit of Themes/Zero/docs/ (213 .md files). Rewrote docs/index.md as the single"
document_type: story
category: bmad-story
tags: []
updated: "2026-10-08"
qmd: "docs index audit - theme zero"
issues: []
discussions: []
---

# Docs index audit — Theme Zero

**BMAD phase:** Build (docs-only maintenance, no application code).

Audit of `Themes/Zero/docs/` (213 `.md` files). Rewrote `docs/index.md` as the single
topic-organized entry point covering all 213 files, without deleting, renaming or moving
any existing file. Detected duplicate/superseded clusters (exact md5 duplicates, deprecated
snake_case stubs, uppercase/lowercase twins, redundant index files, historic conflict-resolution
family) and grouped them under "Storico / da consolidare" in the new index, each still linked
at its original path per `docs-archive-policy.md`. Verified via link-vs-filesystem diff that
all 213 files are reachable from `index.md`.

## Update 2026-09-11

Verificato contro il codice reale (non assunto): il deliverable di questa story
("index.md riorganizzato per argomento, sezione 'Storico / da consolidare'") **non
esiste piu'** nell'`index.md` attuale. Il commit radice `0d6cf4c` (2026-09-09,
ricreazione della storia git di questo repo tema da un working tree preesistente)
ha portato in `docs/` un `index.md` diverso: un elenco piatto auto-generato per
cartella (179 righe), senza la sezione "Storico / da consolidare", e con un link
morto verso `legacy/duplicates/...` (directory mai esistita in questo repo).
Lo stesso commit ha introdotto ~18 nuove coppie di file duplicati case/underscore
in `docs/` root (vedi `stories/docs-theme-zero-audit-2026-09-11.story.md` per
l'elenco completo), non presenti al momento di questa story.

Non e' un errore di questa story: e' un artefatto sovrascritto da un evento
successivo e indipendente. Segnalato qui per lo stesso motivo per cui il second
brain del monorepo diffida di fidarsi ciecamente della documentazione esistente
("Story già 'done' ma falsa" e' un pattern gia' visto altrove nel monorepo).
`index.md` e' stato rigenerato in `docs-theme-zero-audit-2026-09-11.story.md`
come inventario completo verificato (235 file, tutti i link controllati contro
il filesystem), stavolta con provenienza scriptata (non a mano) cosi' e'
riproducibile: vedere quella story per il comando esatto.
